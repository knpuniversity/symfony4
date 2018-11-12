# Validation Constraints with @Assert

When you talk about validation, what you're *really* talking about is business
rules validation. That's where you tell Symfony that the title is required and
needs to be a certain length, or that some field should be a valid email address,
or that the password must contain 2 upper case letters, 2 lower case letters, 3
unicode characters and at least 4 emojis. It's about making the data constrain to
*your* application's rules.

## Adding your First Assert Annotation

Symfony's validation is kinda interesting because you do *not* apply the validation
rules to the form. Nope, you apply them to your class via annotations. Check this
out: I want the `title` field to be required. To do that, type `@NotBlank` and
hit tab to autocomplete to `@Assert\NotBlank`. Because I have the PHP annotations
plugin installed, when I auto-completed that, it added a `use` statement on top
that we need: `use Symfony\Component\Validator\Constraints as Assert`.

[[[ code('d337a291e1') ]]]

Without doing *anything* else, refresh the form - the `title` field *is* empty.
Yes! That's our error!

> This value should not be blank.

To customize that, add a `message` key to the annotation:

> Get creative and think of a title!

[[[ code('584abe6ecb') ]]]

Try it again - refresh and... nice!

## The Built-in Validation Constraints

On the docs, click to go back to the documentation homepage. Then, under guides,
find the "Validation" guide. Just like with the form fields, there are a *bunch*
of built-in validation constraints that... can help you validate almost anything!
And... just like with form field types, each validation constraint has different
options that control its behavior.

For example - check out `Length`: you can set the `min` length with the `min` option,
or `max` with `max`. Control their error messages with `minMessage` and `maxMessage`.

Oh, *another* way to see what the options are is to remember that every annotation
has a concrete PHP *class* behind it. Thanks to the PHP annotations plugin, I can
hold Command or Ctrl and click the annotation to jump to that class.

Nice! Every property becomes an *option* that you can pass to the annotation. We'll
see this again later when we create our *own* custom validation constraint.

Anyways, we won't talk too much about validation constraints because... they're
honestly pretty easy: it's usually a matter of finding which validation constraint
you need and the options to pass to it.

## The Callback Constraint

Oh, but there *is* one really cool constraint called `Callback`. This is *the* tool
when you need to go rogue and do something totally custom. Check it out: create a
method in your class and add `@Assert\Callback()` above it. Then, during validation,
Symfony will call your method!

Let's copy this, find our `Article` class, go all the way to the bottom, and paste.
Oh, I need to retype the end of `ExecutionContextInterface` and auto-complete it to
get the `use` statement. Then, inside... it's awesome! We can do whatever we want!

[[[ code('d087967557') ]]]

Let's make sure that the `title` of this `Article` doesn't contain the string
`the borg`... cause they're scary. So, if `stripos()` of  `$this->getTitle()` and
`the borg` does not equal false... error! To create the error, use
`$context->buildViolation()`:

> Um.. the Borg kinda makes us nervous

Apparently *so* nervous that I typed "the Bork" instead! Resistance to typos is
futile...

Next, choose which field to attach the error to with `->atPath('title')` and finish
with `->addViolation()`. That's it!

[[[ code('3742dce5fc') ]]]

Go back to our form, write an article about how you *really* want to join the
borg and Create!

Got it! Custom validation logic with a custom error.

Next: let's talk a little more about how we can control the *rendering* of these
fields. Because, right now, we're just sort of rendering them all at once... without
much control over their look and feel.
