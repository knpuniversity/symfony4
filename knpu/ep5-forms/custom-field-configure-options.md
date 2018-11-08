# Custom Field: configureOptions() & Allowing Empty Input

Thanks to our data transformer: specifically the fact that the it throws a
`TransformationFailedException` when a bad email is entered, our
`UserSelectTextType` has some built-in sanity validation! 

But, the message we passed to the exception is *not* what's shown to the user.
That's just internal. To control the message, well, we already know the answer!
By adding an `invalid_message` option when we create the field.

## configureOptions(): Defining Field Options / Default

*Or*... instead of configuring that option when we're adding the individual field,
we can give this option a default value for this form type. Open `UserSelectTextType`,
go back to the Code -> Generate menu, or Command + N on a Mac, and this time,
override `configureOptions()`. Inside, add `$resolver->setDefaults()` and give
the `invalid_message` option a different default: "User not found:.

Try that out! Go back, refresh and... very nice!

And hey! We've seen this `configureOptions()` method before inside our normal
form classes! When you're building an entire form, `configureOptions()` is used
to set some options on your, whole form. There aren't that many things to configure
at this level.

But when you're creating a custom field type: `configureOptions()` is used to
set the options for that specific *field*. We've just changed the default value
for the `invalid_message` option. The *cool* thing is that this can *still* be
overridden if we want: we could add an `invalid_message` option to the author field
and *it* would win!

## Fixing Empty Value Case in the Data Transformer

I want to talk more about field options - they can unlock some powerful possibilities.
But first, there is a *teenie*, tiny bug with our data transformer. Clear out the
`author` text box and try to submit. Duh - disable HTML5 validation by adding the
`novalidate` attribute. Hit update!

Oh! Our sanity validation *still* fails: User not found. That's not *quite* what
we want. Instead of failing, our data transformer should probably just return
`null`.

Go back to `EmailToUserTransformer`. In `reverseTransform()`, if `$value` is empty,
just `return`. So, if the field was submitted empty, `null` should be passed to
`setAuthor()`. 

But, hmm the problem *now* is that, while it's *technically* ok to call `setAuthor()`
with a `null` argument, we *want* that field to be required!

Re-submit the form! Oof - an integrity constraint violation: it's trying to save
to the database with `null` set as the `author_id`. We purposely made this required.
This is a *great* example of... messing up! We're missing an important piece of
business validation. No worries: open the `Article` class, find the `$author` field
and, above it, add `@Assert\NotNull()` with a message of `Please set an author`.

Try that again. Excellent! *This* is the behavior and error we expect.

Next: how could could we make our custom field type behave *differently* if it was
used in different forms? Like, if in one form, the user should be able to enter
*any* user's email address but in *another* form, you can only enter admin user
email addresses. Let's learn more about the power of form field options.
