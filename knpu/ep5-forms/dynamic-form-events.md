# Form Events

All right, here's the issue and it is *super* technical. If we change the Location
from "Near a Start" to "Solar System", *even* if we "hack" the `specificLocationName`
field so that it submits the value "Earth", it doesn't work! It fails validation!

This *is* a real problem because, in a few minutes, we're going to add JavaScript
to the page so that when we change the `location` to "The Solar System", it will
dynamically update the `specificLocationName` dropdown down to be the list of planets.
But for that to work, our form system needs to be smart enough to realize - at
the *moment* we're submitting - that the location has changed. And then, before it
validates the `ChoiceType`, it needs to *change* the choices to be the list of
planets.

Don't worry if this doesn't make complete sense yet - let's see some code@

## Adding an Event Listener

There's one piece of the form system that we haven't talked about yet: it has
an *event* system, which we can use to hook into the form loading & submitting
process.

At the end of the form, add `$builder->get('location')->addEventListener()` and
pass this `FormEvents::POST_SUBMIT`. This `FormEvents` class holds a constant for
each "event" that we can hook into with the form system. Then, pass a callback
as a second argument: Symfony will pass us a `FormEvent` object.

Let's `dd()` the `$event` so we can see what it looks like.

Before we look at that, two important things. First, when you build a form, it's
actually a big a form tree. We've seen this inside of the form profiler. There's
a `Form` object on top and then each individual field below is itself a full `Form`
object. The same is true with the "form builder": we normally just interact with
the top-level `$builder` by adding fields to it. But, call `$builder->add()`, that
creates another "form builder" object for that field, and you can fetch it later
by saying `$builder->get()`.

Second, we're attaching the event to only the location *field* - not the entire
form. So, when the form submits, Symfony will call this function, but this
`$event` object will only have information about the `location` field - not the
entire form.

Let's actually *see* this! Refresh to re-submit the form. There it is! The
`FormEvent` contains the raw, submitted data - the `solar_system` string - *and*
the entire `Form` object for this one field.

## Dynamically Updating the Field

This give us the hook we need: we can use the submitting data to *dynamically*
change the `specificLocationName` field to use the correct choices, *right* before
validation occurs. Actually, this hook happens *after* validation - but we'll
use a trick to get around that.

To start, create a new `private function` called `setupSpecificLocationNameField()`. 
The job of this function will be to dynamically add the `specificLocationName`
field with the correct choices. It will accept a `FormInterface` - we'll talk
about that in a second - and a `?string $location`, the `?` part so this can be
`null`.

Inside, first if `$location` is `null`. If it is, take the `$form` object and actually 
`->remove()` the `specificLocationName` field and `return`. Here's the idea: if
I originally rendered the form and there was a location, then, thanks to our logic
in `buildform()`, there *will* be a `specificLocationName` field. But if we changed
it to "Choose a location", meaning we are *not* selecting a location, then we want
to *remove* the `specificLocationName` field before we do any validation. We're
kind of trying to do the same thing on *submit*, that our future JavaScript will
do instantly on the frontend.

Next, get the `$choices` by using `$this->getLocationNameChoices()` and pass that
`$location`. Then, similar to above, `if (null === $choices )` remove the field
and return. This is needed for when the user selects "Interstellar Space": that
doesn't have any specific location name choices, and so we don't want that field
at all.

Finally, we *do* want the `specificLocationName` field, but we want to use our
new choices. Scroll up and copy the `$builder->add()` line for this field, paste
down here, and change `$builder` to `$form` - these two objects have an identical
`add()` method. For `choices` pass `$choices`.

Nice! We created this new function so that we can call it from inside of our listener
callback. Start with `$form = $event->getForm()`: that gives us the actual `Form`
object for this one field. Now call `$this->setupSpecificLocationNameField()` and,
for the first argument, pass it `$form->getParent()`.

This is tricky. The `$form` variable is the Form object that represents just
the `location` field. But we want to pass the *top* level `Form` object into
the function so that the `specificLocationName` field can be added or removed from
*it*.

The second argument is the `location` itself, which will be `$form->getData()`,
or `$event->getData()`.

Okay guys, I know this is craziness, but we're ready to try it! Refresh to resubmit
the form. It saves. Now change the Location to "Near a Star". In a few minutes,
our JavaScript will reload the `specificLocationName` field with the new options.
To fake that, inspect the element. Let's go copy a real star name - how about
`Sirius`. Change the selected option's value to that string.

Hit update! Yes! It saved! We were able to change both the `location` *and*
`specificLocationName` fields at the same time.

And *that* means that we're ready to swap out the field dynamically with JavaScript.
But first, we're going to leverage another form event to remove some duplication
from our form class.
