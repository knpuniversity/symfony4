# Form Rendering Functions: form_*

To render the form, we're using a few form functions: one that makes the form start
tag, one that makes the end tag and one that renders all the fields, labels and
errors inside.

This was *easy* to set up. The *problem* is that we have almost *no* control over
the HTML markup that's used! Sure, we were able to activate a "form theme" that
told it to use Bootstrap-friendly markup. But, what if you need more control?

This is probably *the* hardest part of Symfony's form system. But don't worry:
we're going to learn several different strategies to help you get the markup you
need... without going crazy... probably.

## The Form Rendering Functions

Go to your other tab and Google for "Symfony form rendering functions" to find a
page that talks all about the functions we're using and a few others.

First, `form_start()`, yes, this *does* just render the form start tag, which might
seem kind of silly, but it can come in handy when you add a file upload field to
your form: it automatically add the `enctype` attribute.

Oh, but notice: `form_start()` has a second argument: an array of *variables* that
can be passed to customize it. Apparently you can pass `method` to change the
`method` attribute or `attr` to add any *other* attributes to the form tag - like
a class.

Next: find `form_end()`. This one seems even *sillier* because it literally
prints... yep! The form closing tag! But, it has a hidden superpower: it *also* renders
any fields that we forgot to render. Now, that might not make sense yet because this
magic `form_widget()` function seems to be rendering *everything* automatically.
But, in a moment, we'll render the fields one-by-one. When we do that, *if* we've
forgotten to render any of the fields, `form_end()` will render them for us... and
*then* the closing tag.

That *still* may not seem like a good feature... and, in many ways, it's not! In
reality, the *purpose* of this is *not* so that we can be lazy and `form_end()` will
save us. Nope - the *true* purpose is that `form_end()` will render any hidden fields
automatically, without us needing to even think about them. Most importantly, it
will render your form's CSRF token

## CSRF Token

Inspect element near the bottom of the form. Woh! Without us doing *anything*,
we have a hidden `input` tag called `_token`. *This* is a CSRF token and it was
automatically added by Symfony. And, even *cooler*, when we submit, Symfony automatically
validates it.

Without even knowing it, all of our forms are protected from CSRF attacks.

## form_widget and form_row()

Back to the form rendering goodness! To print the form fields themselves, the
*easiest* way is to call `form_widget()` and pass the entire form. But, if you
need a *little* bit more control, instead of `form_widget()`, you can call
`form_row()` and render each field individually. For example, `articleForm.title`.
Copy that and paste it three more times. Render `articleForm.content`,
`articleForm.publishedAt` and `articleForm.author`.

[[[ code('014fab892f') ]]]

Before we talk about this function, move over, refresh and... ok! It looks
*exactly* the same. That's no accident! Calling `form_widget()` and passing
it the entire form is just a shortcut for calling `form_row()` on each field individually.

This introduces an important concept in Symfony's form rendering system: the "row".
The `form_row()` function basically adds a "wrapper" around the field - like a div -
then renders the *4* components of each field: the label, the "widget" - that's the
form field itself, the help text and, if needed, the validation errors.

At first, it looks like using `form_row()` isn't much more flexible than what we
had before, except that we can reorder the fields. But, in reality, we've just unlocked
quite a *lot* of control via a system called "form variables". Let's check those
out next!
