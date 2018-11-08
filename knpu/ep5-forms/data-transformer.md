# Data Transformer

We built a custom field type called `UserSelectTextType` and we're already using
it for the `author` field. That's cool, except, thanks to `getParent()`, it's just
a `TextType` in disguise!

Internally, `TextType` basically has no data transformer: it takes whatever value
is on the object and tries to print it! For the `author` field, it means it's
just echo'ing the `User` object! Our `__toString()` makes that not blow up.

Let's remove that and see what happens. Refresh! Woohoo! A *huge* error:

> Object of class User could not be converted to string

More importantly, even if you put this back, the form renders, but when you submit,
a different huge error: the form system takes that submitted *string* and tries
to pass it to `setAuthor()`.

To fix this, our field needs a data transformer: something that's capable of taking
the `User` object and rendering its `email`. And on submit, transforming that `email`
back into a `User` object.

## Creating the Data Transformer

Here's how it works: in the `Form/` directory, create a new `DataTransformer/`
directory, as usual, the location of the new class won't matter. Then add a new
class: `EmailToUserTransformer`.

The only rule for a data transformer is that it needs to implement a
`DataTransformerInterface`. I'll go to the Code -> Generate menu, or Command+N
on a Mac, select "Implement Methods" and choose the two from the interface.

I love data transformers! Let's add some debug code in each so we can see when
they're called and what the value looks like. So `dd('transform', $value)` and
`dd('reverse transform', $value)`.

Yo make `UserSelectTextType`  use this, head back to that class, go to the
Code -> Generate menu again, or Command + N on a Mac, and override one more method:
`buildForm()`.

Hey! We know this method! This is is the method that we override in our *normal*
form type classes: it's where we add the fields! It turns out, there are a few
*other* things that you can do with this `$builder` object: one of them is
`$builder->addModelTransformer()`. Pass this a `new EmailToUserTransformer()`.

## The transform() Method

Let's try it! I'll hit enter on the URL in my browser to re-render the form with
a GET request. And... boom! We hit the `transform()` method! And the value is our
`User` *object*.

This is awesome! That's the whole point of `transform()`! This method is called.
when the form is *rendering*: it takes the raw data for a field - in our case the
`User` object that lives on the `author` property - and our job is to transform
that into a representation that can be used for the form field. In other words,
the `email` string.

First, if null is the value, just return an empty string. Next, let's add a sanity
check: `if (!$value instanceof User)`, then *we*, the developer, are trying to
do something crazy. Throw a new `LogicException()` that says:

> The `UserSelectTextType` can only be used with User objects.

Finally, at the bottom, so nice, `return $value` - which we now know is a `User`
object `->getEmail()`.

Let's rock! Move over, refresh and.... hello email address!

## The reverseTransform() Method

*Now*, let's submit this. Boom! This time, we hit `reverseTransform()` and *its*
data is the literal string email address. Our job is to use that to query for
 `User` object and return it. To do *that*, this class needs our `UserRepository`.

Time for some dependency injection! Add a constructor with
`UserRepository $userRepository`. I'll hit alt+enter and select "Initialize Fields"
to create that property and set it.

Normally, that's all we would need to do! We could use that property down below.
But... this object is *not* instantiated by the Symfony's container. So, we
*don't* get our cool autowiring magic. Nope, in this case, *we* are literally creating
this object by ourselves. And so, it is *our* responsibility to pass it whatever
it needs.

It's no big deal, but, we do have some more work. In the field type class, add
an identical `_construct()` method with the same `UserRepository` argument. Hit
Alt+Enter again to initialize that field. The form type classes *are* services,
so autowiring *will* work here.

Thanks to that, in `buildForm()` pass `$this->userRepository` manually into
`EmailToUserTransformer`.

Back in `reverseTransform()`, let's do our `$user = $this->userRepository` and
call the `findOneBy()` method to query for `email` set to `$value`. If there is
*not* a user with that email, throw a new `TransformationFailedException()`. This
important - and its `use` statement was even pre-added when we implemented the
interface. Inside, say:

> No user found with email %s

and then we'll pass the value. At the bottom, `return $user`.

The `TransformationFailedException` is important: when this is thrown, it's a signal
that there is a *validation* error.

Check it out: find your browser and refresh to resubmit that form. Cool - it *looks*
like it worked. Try a different email: `spacebar3@example.com` and submit! Nice!
If I click enter on the address to get a fresh load... yep! It *definitely* saved!

But now, try an email that doesn't exist, like `spacebar300@example.com`. Submit
and... validation error! *That* comes from our data transformer. This
`TransformationFailedException` causes a validation error. Not the type of 
validation errors that we get from our annotations - like `@Assert\Email()` or
`@NotBlank()`. Nope - this is what I referred early to as "sanity" validation:
validation that is built right into the form field itself.

We saw this in action back when we were using the `EntityType` for the `author`
field: if we hacked the options HTML and changed the `value` attribute to a non-existent
id, we got a sanity validation error message.

Next: let's see how we can customize this error and learn do a few of other
fancy things to make our custom field more flexible.
