# Adding Extra "Unmapped" Fields

`UserRegistrationFormType` has a `password` field. But *that* means, when the
user types in their password, the form component will call `setPassword()` and pass
it that *plaintext* property, which will be stored on the `password` property.

That's both *weird* - because the `password` field should *always* be encrypted -
*and* a potential security issue: if we somehow accidentally save the user at this
moment, that plaintext password will go into the database.

And, yea before we save, we *do* encrypt that plaintext password and set that *back*
on the `password` property. But, I don't like doing this: I don't like *ever* setting
the plaintext password on a property that could be persisted: it's just risky, and,
kind of strange to use this property in two ways.

Go back to `UserRegistrationFormType`. Change the field to `plainPassword`. Let's
add a comment above about why we're doing this.

[[[ code('e2ce52c13d') ]]]

But... yea! This *will* break things! Go back to the form and try to register with
a different user. Boom!

> Neither the property `plainPassword` nor one of the methods `getPlainPassword()`
> blah, blah, blah, exist in class `User`.

And we know why this is happening! Earlier, we learned that when you add a field
to your form called `email`, the form system, calls `getEmail()` to read data off
of the `User` object. And when we submit, it calls `setEmail()` to set the data
back *on* the object. Oh, and, it *also* calls `getEmail()` on submit to so it can
*first* check to see if the data changed at all.

Anyways, the form is basically saying:

> Hey! I see this `plainPassword` field, but there's no way for me to get or
> set that property!

There are two ways to fix this. First, we *could* create a `plainPassword` property
on `User`, but make it *not* persist it to the database. So, *don't* put an `@ORM\Column`
annotation on it. Then, we could add normal `getPlainPassword()` and `setPlainPassword()`
methods... and we're good! That solution is simple. But it also means that we've
added this extra property to the class *just* to help make the form work.

## Unmapped (mapped => false) Fields

The *second* solution is... a bit more interesting: we can mark the field to not be
"mapped". Check it out: pass `null` as the second argument to `add()` so it continues
guessing the field type for now. Then, pass a new option: `mapped` set to `false`.

That changes everything. This tells the form system that we *do* want to have this
`plainPassword` field on our form, but that it should *not* get or set its data back
onto the `User` object. It means that we *no* longer need `getPlainPassword()`
and `setPlainPassword()` methods!

## Accessing Unmapped Fields

Woo! Except... wait, if the form doesn't set this data onto the `User` object...
how the heck can we access that data? After all, when we call `$form->getData()`,
it gives us the `User` object. Where will that `plainPassword` data live?

In your controller, `dd($form['plainPassword']->getData())`.

[[[ code('2ed985793e') ]]]

Then move over, refresh and... oh! Form contains extra fields. My fault: I never
fully refreshed the form after renaming `password` to `plainPassword`. So, we were
*still* submitting the old password field. By default, if you submit *extra* fields
to a form, you get this validation error.

Let's try that again. This time... Yes! It hits our dump and die and *there* is
our plain password!

This uncovers a *really* neat thing about the form system. When you call
`$this->createForm()`, it creates a Form object that represents the whole form. But
also, each individual *field* is *also* represented as its *own* `Form` object,
and it's a *child* of that top-level form. Yep, `$form['plainPassword']` gives us
a `Form` object that knows everything about this *one* field. When we call
`->getData()` on it, yep! That's the value for this *one* field.

This is a *super* nice solution for situations where you need to add a field to your
form, but it doesn't map cleanly to a property on your entity. Copy this, remove the
`dd()` and, down below, use *that* to get the plain password.

[[[ code('967d551132') ]]]

Let's try it! Move back over, refresh and... got it! We are *registered*!

## Using the PasswordType Field

Go back to `/register` - there is *one* more thing I want to fix before we keep
going: the password field is a normal, plaintext input. That's not ideal.

Find your form class. The form field guessing system has *no* idea what type
of field `plainPassword` is - it's not even a property on our entity! When guessing
fails, it falls back to `TextType`.

Change this to `PasswordType::class`. This won't change how the field *behaves*,
only how it's rendered. Yep! A proper `<input type="password">` field.

[[[ code('784809ab59') ]]]

Next: time to add validation! Which, hmm, is going to be a bit interesting. First,
we need to validate that the user is unique in the database. And second, for the
first time, we need to add validation to a form field where there is *no* corresponding
property on our class.
