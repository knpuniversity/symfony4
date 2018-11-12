# HTML5 & "Sanity" Validation

Does our form have any validation yet? Well... sort of? The form *is* going through
a validation process. When we POST to this endpoint, `handleRequest()` reads the
data *and* executes Symfony's validation system. If validation fails, then
`$form->isValid()` returns false and we immediately render the template, except
that *now* errors will be displayed by each field with an error.

Of course we haven't *seen* this yet... because we haven't added any validation
rules!

## HTML5 Validation

But, check this out: leave the form completely blank and try to submit. It stops
us! Wait... who... stopped us? Actually, it was the *browser*. Many of you may recognize
this: it's HTML5 validation.

When Symfony renders a field, depending on our config, it often adds a
`required="required"` attribute. This isn't *real* validation - there's *nothing*
on our server that's checking to make sure this value isn't blank. It's just nice
client-side validation. HTML5 is cool... but limited. There *are* a few other things
it can validate. Like, a `datetime-local` field will require you to enter a valid
date. Or, an `<input type="number">` will require a number. But, not much more.

## The Annoying required Attribute

To control whether or not you want that `required` attribute, *every* field type
has an option called `required`: just set it to `true` or `false`. Actually, this
option is kinda confusing. It defaults to *true* for *every* field... no matter
what... which can be kind of annoying & surprising. However, when you bind your
form to an entity class, the form field guessing system uses the `nullable` Doctrine
option to choose the correct `required` option value for you. In fact, if we look
at the textarea field... yep! This has no `required` attribute. Oh, by the way, all
those extra attributes are coming from a browser plugin I have installed - not the
form system.

To make things a bit *more* confusing, the `required` option is only "guessed"
from your Doctrine config if you omit or pass `null` to the second argument of `add()`.
If you specify the type manually, the form type guessing system does nothing and
you'll need to configure the `required` option manually. Honestly, the
`required` option is kind of a pain in the butt. Be careful to make sure that an
optional field doesn't accidentally have this attribute.

## Installing Validation

*Anyways*, even if you use HTML5 validation, you will *still* need proper server-side
validation so that a "bad" user can't just disable that validation and send weird
data. To do that, well, first we need to install the validator!

Find your terminal and run:

```terminal
composer require validator
```

Validation is a separate component in Symfony, which is *cool* because it means
that you can use it independent of the form system.

And... done! There are actually *two* types of server-side validation: what I call
"sanity validation" versus "business rules validation".

## Form Field Sanity Validation

Let's talk about sanity validation first. Sanity validation is built into the form
fields themselves and makes sure that the submitted value isn't completely... insane!
For text fields like `title` and `content`, there is no sanity validation: we can
submit anything to those fields and it basically makes sense: it's a string. But
the `EntityType` *does* have built-in sanity validation.

Check this out: inspect element in your browser and find the select field. Let's
change one of these values to be something that's *not* in the database, like
`value=100`.

Select this user and hit Create. Oh, duh! The HTML5 validation on the other fields
stops us. To work around this, find the form class and add a `novalidate` attribute:
that tells the browser to get a hobby and skip HTML5 validation. It's a nice trick
when you're testing your server-side validation. Hit Create again.

Yay! Our first, *real* validation error!

> This value is not valid

This error comes from the "sanity" validation that's built into `EntityType`:
if you try to submit a value that should *not* be in the drop-down, boom! You
get an error. Sanity validation is great: it saves us, *and*... we don't need to
think about it! It just works.

To control the message, pass an option called `invalid_message`. Set it to:

> Symfony is too smart for your hacking!

[[[ code('cbc9d16908') ]]]

Move back and refresh to re-POST the form. Nice! I don't *usually* set the
`invalid_message`, only because, for most fields, you only see these errors
if you're doing something *really* weird - like messing with the HTML.

We've talked about HTML5 validation and learned about sanity validation. Next, let's
get to the *good* stuff: the *real* validation that *we* need to add.
