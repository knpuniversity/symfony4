# Agree to Terms Checkbox Field

The `User` class has an `agreedTermsAt` property that expects a `DateTime` object.
But, our form has an `agreeTerms` field that, on submit, will give us a true/false
boolean value. How can we make these work together? As I *so* often like to say:
there are two options.

First, we could be clever! There is *no* `agreeTerms` property on `User`. But, we
*could* create a `setAgreeTerms()` *method* on `User`. When that's called, we would
*actually* set the `agreedTermsAt` property to the current date. We would also need
to create a `getAgreeTerms()` method that would return a boolean based on whether
or not the `agreedTermsAt` property was set.

This is a *fine* solution. But, this is *also* a good example of how the form system
can start to make your life *harder* instead of *easier*. When your form and your
class don't look the same, *sometimes* you can find a simple and natural solution.
But sometimes, you might need to dream up something *crazy* to make it all work.
If the solution isn't obvious to you, move on to option two: make the field unmapped.

Let's try that: set `agreeTerms` to `mapped` `false`. To *force* this to be checked,
add `constraints` set to a new `IsTrue()`... because we *need* the underlying value
of this field to be `true`, not `false`. Set a custom message:

> I know, it's silly, but you must agree to our terms

[[[ code('fe3883a9b1') ]]]

Excellent! Thanks to the `mapped = false`, the form should *at least* load. Try
it - refresh! Yes! Well... oh boy - our styling is *so* bad, the checkbox is hiding
off the screen! Let's worry about that in a minute.

Thanks to the `mapped => false`, the data from the checkbox does *not* affect our
`User` object in any way when we submit. No problem: in `SecurityController`, let's
handle it manually with `if (true === $form['agreeTerms']->getData())`. Wait...
that looks redundant! We *already* have form validation that *forces* the box to
be checked. You're totally right! I'm just being *extra* careful... ya know... for
legal reasons.

[[[ code('015dd4a720') ]]]

Inside, we *could* call `$user->setAgreedTermsAt()` and pass the current date.
*Or*, we can do something a bit cleaner. Find the  `setAgreedTermsAt()` method and
rename it to `agreeTerms()`, but with no arguments. Inside say
`$this->agreedTermsAt = new \DateTime()`.

[[[ code('76649a9c08') ]]]

This gives us a clean, *meaningful* method. In `SecurityController`, call that:
`$user->agreeTerms()`.

[[[ code('a47f21d16f') ]]]

Ok team, let's try this. Refresh the page. *Annoyingly*, I still can't see the
checkbox. Let's hack that for now: add a little extra padding on this div. There
it is!

Register as `geordi3@theenterprise.org`, password `engage`, hit enter, and...
yes! We *know* the datetime column was just set correctly in the database because
it's *required*.

Here's the big takeaway: whenever you need a field on your form that doesn't exist
on your entity, there *may* be a clever solution. But, *if* it's not obvious, make
the field unmapped and add a little bit of glue code in your controller that does
whatever you need.

Later, we'll discuss a *third* option: creating a custom *model* class for your
form.

## Fixing your Fixtures

Before we move on, try to reload the fixtures:

```terminal
php bin/console doctrine:fixtures:load
```

It... explodes! Duh! I made the new `agreedTermsAt` field *required* in the
database, but forgot to update it in the fixtures. No problem: open `UserFixture`.
In the first block, add `$user->agreeTerms()`. Copy that, and do the same for
the admin users.

[[[ code('bba1f6f59b') ]]]

Cool! Try it again:

```terminal-silent
php bin/console doctrine:fixtures:load
```

And.... all better!

Next: let's fix the styling in our registration form by creating our very own
form *theme*.
