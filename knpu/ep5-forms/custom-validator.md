# Custom Validator

Unfortunately, you can't use the `@UniqueEntity()` validation constraint above a
class that is *not* an entity: it's just a known limitation. But, *fortunately*,
this gives us the *perfect* excuse to create a custom validation constraint! Woo!

When you can't find a built-in validation constraint that does what you need, the
*next* thing to try is the `@Assert\Callback` constraint. We use this in the `Article`
class. But, it has one limitation: because the method lives inside an entity class -
we do *not* have access to any services. In our case, in order to know whether or
not the `email` is taken yet, we need to make a query and so we *do* need to access
a service.

## Generating the Constraint Validator

When that's your situation, it's time for a custom validation constraint. They're
awesome anyways *and* we're going to cheat! Find your terminal and run:

```terminal
php bin/console make:validator
```

Call the class, how about, `UniqueUser`. Oh, this created *two*  classes: `UniqueUser`
and `UniqueUserValidator`. You'll find these inside a new `Validator/` directory.
Look at `UniqueUser` first: it's basically a dumb configuration object. *This*
will be the class we use for our annotation.

[[[ code('251c9f6aa9') ]]]

The actual validation is handled by `UniqueUserValidator`: Symfony will pass it the
value being validated *and* a `Constraint` object - which will be that `UniqueUser`
object we just saw. We'll use it to read some options to help us get our job done.
For example, in the generated code, it reads the `message` property from the
`$constraint` and sets that as the validation error. That's literally reading this
public `$message` property from `UniqueUser`.

[[[ code('959d5a11c1') ]]]

## Configuring the Annotation

Ok: let's bring this generated code to life! Step 1: make sure your annotation
class - `UniqueUser` - is ready to go. In general, an annotation can either be added
above a class *or* above a property. Well, you can *also* add annotations above
methods - that works pretty similar to properties.

If you add a validation annotation above your class, then during validation, the
*value* that's passed to that validator is the entire *object*. If you add it above
a property, then the value that's passed is *just* that property's value. So, if you
need access to multiple fields on an object for validation, then you'll need to
create an annotation that can be used above the class. In this situation, I'm going
to delete `@UniqueEntity` and, instead, add the new annotation above my `$email`
property: `@UniqueUser`. Hit tab to auto-complete that and get the `use` statement.

[[[ code('4959b65bca') ]]]

Nice! Now, go back to your annotation class, we need to do a bit more work. To
follow an example, press shift+ shift and open the core `NotBlank` annotation class.
See that `@Target()` annotation above the class? This is a special annotation...
that configures, um, the annotation system! `@Target` tells the annotation system
*where* your annotation is allowed to be used. Copy that and paste it above
our class. This says that it's okay for this annotation to be used above a property,
above a method or even inside of another annotation... which is a bit more of a
complex case, but we'll leave it.

[[[ code('1f4d95b89f') ]]]

What if you instead want your annotation to be put above a class? Open the
`UniqueEntity` class as an example. Yep, you would use the `CLASS` target. The
*other* thing you would need to do is override the `getTargets()` method. Wait,
why is there an `@Target` annotation *and* a `getTargets()` method - isn't that
redundant? Basically, yep! These provide more or less the same info to two different
systems: the annotation system and the validation system. The `getTargets()` method
defaults to `PROPERTY` - so you only need to override it if your annotation should
be applied to a class.

## Configuring your Annotation Properties

Phew! The *last* thing we need to do inside of `UniqueUser` is give it a better
default `$message`: we'll set it to the same thing that we have above our `User`
class: `I think you've already registered`. Paste that and... cool!

[[[ code('1e63f2e70b') ]]]

If you need to be able to configure more things on your annotation - just create
more public properties on `UniqueUser`. Any properties on this class can be set
or overridden as options when using the annotation. In `UserRegistrationFormModel`,
I won't do it now, but we *could* add a `message=` option: that string would ultimately
be set on the `message` property.

Before we try this, go to `UniqueUserValidator`. See the `setParameter()` line?
The makes it possible to add wildcards to your message - like:

> The email {{ value }} is already registered

We could keep that, but since I'm not going to use it, I'll remove it. And... cool!
With this setup, when we submit, this validator will be called and it will *always*
fail. That's a good start. Let's try it!

## Filling in the Validator Logic

Move over and refresh to resubmit the form. Yes! Our validator *is* working... it
just doesn't have any logic yet! This is the easy part! Let's think about it: we
need to make a query from inside the validator. Fortunately, these validator
classes are *services*. And so, we can use our *favorite* trick: dependency injection!

Add an `__construct()` method on top with a `UserRepository $userRepository` argument.
I'll hit alt+Enter to create that property and set it. Below, let's say
`$existingUser = $this->userRepository->findOneBy()` to query for an email set to
`$value`. Remember: because we put the annotation above the `email` property, `$value`
will be that property's value.

Next, very simply, `if (!$existingUser)` then `return`. That's it.

[[[ code('971eac360d') ]]]

One note: if this were an edit form where a user could *change* their email, this
validator would need to make sure that the existing user wasn't actually just *this*
user, if they submitted without changing their email. In that case, we would need
`$value` to be the entire object so that we could use the `id` to be sure
of this. To do that, you would need to change `UniqueUser` so that it lives above
the *class*, instead of the property. You would also need to add an `id` property
to `UserRegistrationFormModel`.

But, for us, this is it! Move back over, refresh and... got it! Try entering a new
user and adding the `novalidate` attribute so we can be lazy and keep the other fields
blank. Submit! Error gone. Try `WillRyker@theenterprise.org` with the same `novalidate`
trick. And... the error is back.

Custom validation constraints, check! Next, we're going to update our Article form
to add a few new drop-down select fields, but... with a catch: when the user selects
an option from the first drop-down, the options of the *second* drop-down will need
to update dynamically. Woh.
