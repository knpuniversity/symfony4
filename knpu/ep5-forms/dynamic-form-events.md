# Dynamic Form Events

Alright, here's the issue and it is *super* technical. If we change the Location
from "Near a Star" to "Solar System", *even* if we "hack" the `specificLocationName`
field so that it submits the value "Earth", it doesn't work! It fails validation!

This *is* a real problem, because, in a few minutes, we're going to add JavaScript
to the page so that when we change `location` to "The Solar System", it will
dynamically update the `specificLocationName` dropdown down to be the list of planets.
But for that to work, our form system needs to be smart enough to realize - at
the *moment* we're submitting - that the location has changed. And then, before it
validates the `ChoiceType`, it needs to *change* the choices to be the list of
planets.

Don't worry if this doesn't make complete sense yet - let's see some code!

## Adding an Event Listener

There's one piece of the form system that we haven't talked about yet: it has
an *event* system, which we can use to hook into the form loading & submitting
process.

At the end of the form, add `$builder->get('location')->addEventListener()` and
pass this `FormEvents::POST_SUBMIT`. This `FormEvents` class holds a constant for
each "event" that we can hook into for the form system. Pass a callback as a
second argument: Symfony will pass that a `FormEvent` object.

Let's `dd()` the `$event` so we can see what it looks like.

[[[ code('494037c6d2') ]]]

But before we check it out, two important things. First, when you build a form, it's
actually a big form tree. We've seen this inside of the form profiler. There's
a `Form` object on top and then each individual field below is itself a full `Form`
object. The same is true with the "form builder": we normally just interact with
the top-level `$builder` by adding fields to it. When we call `$builder->add()`,
that creates another "form builder" object for that field, and you can fetch it later
by saying `$builder->get()`.

Second, we're attaching the event to only the location *field* - not the entire
form. So, when the form submits, Symfony will call this function, but the
`$event` object will only have information about the `location` field - not the
entire form.

Let's actually *see* this! Refresh to re-submit the form. There it is! The
`FormEvent` contains the raw, submitted data - the `solar_system` string - *and*
the entire `Form` object for this one field.

## Dynamically Updating the Field

This gives us the hook we need: we can use the submitted data to *dynamically*
change the `specificLocationName` field to use the correct choices, *right* before
validation occurs. Actually, this hook happens *after* validation - but we'll
use a trick where we remove and re-add the field, to get around this.

To start, create a new `private function` called `setupSpecificLocationNameField()`. 
The job of this function will be to dynamically add the `specificLocationName`
field with the correct choices. It will accept a `FormInterface` - we'll talk
about that in a minute - and a `?string $location`, the `?` part so this can be
`null`.

[[[ code('ddae7fdbbf') ]]]

Inside, first check if `$location` is `null`. If it is, take the `$form` object and
actually  `->remove()` the `specificLocationName` field and `return`. Here's the
idea: if when I originally rendered the form there was a location set, then, thanks
to our logic in `buildForm()`, there *will* be a `specificLocationName` field. But
if we changed it to "Choose a location", meaning we are *not* selecting a location,
then we want to *remove* the `specificLocationName` field before we do any validation.
We're kind of trying to do the same thing in *here* that our future JavaScript will
do instantly on the frontend: when we change to "Choose a location" - we will want
the field to disappear.

[[[ code('30fb74c246') ]]]

Next, get the `$choices` by using `$this->getLocationNameChoices()` and pass that
`$location`. Then, similar to above, `if (null === $choices)` remove the field
and return. This is needed for when the user selects "Interstellar Space": that
doesn't have any specific location name choices, and so we don't want that field
at all.

[[[ code('5dbb4fd319') ]]]

Finally, we *do* want the `specificLocationName` field, but we want to use our
new choices. Scroll up and copy the `$builder->add()` section for this field, paste
down here, and change `$builder` to `$form` - these two objects have an identical
`add()` method. For `choices` pass `$choices`.

[[[ code('6e62074eb1') ]]]

Nice! We created this new function so that we can call it from inside of our listener
callback. Start with `$form = $event->getForm()`: that gives us the actual `Form`
object for this one field. Now call `$this->setupSpecificLocationNameField()` and,
for the first argument, pass it `$form->getParent()`.

[[[ code('771c9b2491') ]]]

This is tricky. The `$form` variable is the Form object that represents just
the `location` field. But we want to pass the *top* level `Form` object into
the function so that the `specificLocationName` field can be added or removed from
*it*.

The second argument is the `location` itself, which will be `$form->getData()`,
or `$event->getData()`.

[[[ code('e6481aeeff') ]]]

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
