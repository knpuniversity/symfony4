# Data Transformer

We built a custom field type called `UserSelectTextType` and we're already using
it for the `author` field. That's cool, except, thanks to `getParent()`, it's really
just a `TextType` in disguise!

Internally, `TextType` basically has no data transformer: it takes whatever value
is on the object and tries to print it as the `value` for the HTML input! For the
`author` field, it means that it's trying to echo that property's value: an entire
`User` object! Thanks to the `__toString()` method in that class, this prints the
first name.

Let's remove that and see what happens. Refresh! Woohoo! A big ol' error:

> Object of class User could not be converted to string

More importantly, *even* if we put this back, yes, the form would render. But when
we submitted it, we would just get a *different* huge error: the form would try to
take the submitted *string* and pass *that* to `setAuthor()`.

To fix this, our field needs a data transformer: something that's capable of taking
the `User` object and rendering its `email` field. And on submit, transforming that
`email` string back into a `User` object.

## Creating the Data Transformer

Here's how it works: in the `Form/` directory, create a new `DataTransformer/`
directory, but, as usual, the location of the new class won't matter. Then add a new
class: `EmailToUserTransformer`.

The only rule for a data transformer is that it needs to implement a
`DataTransformerInterface`. I'll go to the Code -> Generate menu, or Command+N
on a Mac, select "Implement Methods" and choose the two from that interface.

I love data transformers! Let's add some debug code in each method so we can see when
they're called and what this value looks like. So `dd('transform', $value)` and
`dd('reverse transform', $value)`.

[[[ code('52f246d005') ]]]

To make `UserSelectTextType`  use this, head back to that class, go to the
Code -> Generate menu again, or Command + N on a Mac, and override one more method:
`buildForm()`.

Hey! We know this method! This is is the method that we override in our *normal*
form type classes: it's where we add the fields! It turns out that there are a few
*other* things that you can do with this `$builder` object: one of them is
`$builder->addModelTransformer()`. Pass this a `new EmailToUserTransformer()`.

[[[ code('b47cd2ae1f') ]]]

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

[[[ code('698c979373') ]]]

Let's rock! Move over, refresh and.... hello email address!

## The reverseTransform() Method

*Now*, let's submit this. Boom! This time, we hit `reverseTransform()` and *its*
data is the literal string email address. Our job is to use that to query for
a `User` object and return it. And to do *that*, this class needs our `UserRepository`.

Time for some dependency injection! Add a constructor with
`UserRepository $userRepository`. I'll hit alt+enter and select "Initialize Fields"
to create that property and set it.

[[[ code('39675b436a') ]]]

Normally... that's all we would need to do: we could instantly use that property below.
But... this object is *not* instantiated by Symfony's container. So, we
*don't* get our cool autowiring magic. Nope, in this case, *we* are creating
this object ourselves! And so, *we* are responsible for passing it whatever
it needs.

It's no big deal, but, we do have some more work. In the field type class, add
an identical `__construct()` method with the same `UserRepository` argument. Hit
Alt+Enter again to initialize that field. The form type classes *are* services,
so autowiring *will* work here.

[[[ code('a4b4618dff') ]]]

Thanks to that, in `buildForm()` pass `$this->userRepository` manually into
`EmailToUserTransformer`.

[[[ code('4ff208d910') ]]]

Back in `reverseTransform()`, let's get to work: `$user = $this->userRepository` and
use the `findOneBy()` method to query for `email` set to `$value`. If there is
*not* a user with that email, throw a new `TransformationFailedException()`. This
is important - and its `use` statement was even pre-added when we implemented the
interface. Inside, say:

> No user found with email %s

and pass the value. At the bottom, `return $user`.

[[[ code('fdd3f96f2e') ]]]

The `TransformationFailedException` is special: when this is thrown, it's a signal
that there is a *validation* error.

Check it out: find your browser and refresh to resubmit that form. Cool - it *looks*
like it worked. Try a different email: `spacebar3@example.com` and submit! Nice!
If I click enter on the address to get a fresh load... yep! It *definitely* saved!

But now, try an email that does *not* exist, like `spacebar300@example.com`. Submit
and... validation error! *That* comes from our data transformer. This
`TransformationFailedException` causes a validation error. Not the type of 
validation errors that we get from our annotations - like `@Assert\Email()` or
`@NotBlank()`. Nope: this is what I referred to early as "sanity" validation:
validation that is built right into the form field itself.

We saw this in action back when we were using the `EntityType` for the `author`
field: if we hacked the HTML and changed the `value` attribute of an `option` to
a non-existent id, we got a sanity validation error message.

Next: let's see how we can customize this error and learn to do a few other
fancy things to make our custom field more flexible.
