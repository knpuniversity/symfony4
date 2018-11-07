# Agree Terms Checkbox Field

The `User` class has an `agreedTermsAt` property that expects a `DateTime` object.
But, our form has an `agreeTerms` field that is really good at setting true/false
values. How can we make these work together? There are two options.

First, we could be clever! There is no `agreeTerms` property on `User`. But, we
*could* create a `setAgreeTerms()` *method* on `User`. When that's called, we would
*actually* set the `agreedTermsAt` property to the current date. We would also need
to create a `getAgreeTerms()` method that would return a boolean based on whether
or not the `agreedTermsAt` property were set.

This is a *fine* solution. But, this is *also* a good example of how the form system
can start to make your life *harder* instead of *easier*. When your form and your
class don't look the same, *sometimes* you can find a simple and natural solution.
But sometimes, you might need to dream up something *crazy* to make it all work.
If the solution isn't obvious to you, move on to option two: make the field unmapped.

Let's try that: set `agreeTerms` to `mapped` `false`. To *force* this to be checked,
add `constraints` set to a new `IsTrue()`... because we *need* the underlying value
of this field to be `true`, not `false`. Set a custom message:

> I know, it's silly, but you must agree agree to our terms

Excellent! Thanks to the `mapped = false`, the form should *at least* load. Try
it - refresh! Yes! Well... oh boy - our styling is *so* bad, the checkbox is hiding
off the screen! Let's worry about that in a minute.

Right now, thanks to the `mapped => false`, the data from the checkbox does *not*
affect our `User` object in any way when we submit. No problem: in
`SecurityController`, let's handle it manually with
`if (true === $form['agreedTerms']->getData())`. This *might* seem... redundant!
After all, we have form validation that *forces* the box to be checked. And, you're
totally right! I'm just being *extra* careful for legal reasons.

Inside, we *could* call `$user->setAgreedToTermsAt()` and pass the current date.
*Or*, we can do something a bit cleaner Find the  `setAgreedTermsAt()` method and
rename it to `agreeTerms()`, but with no arguments. Inside say
`$this->agreedTermsAt = new \DateTime()`.

This gives us a clean, *meaningful* method. In `SecurityController`, call that:
`$user->agreeTerms()`.

Ok team, let's try this. Refresh the page. *Annoyingly*, I still can't see the
checkbox. Let's hack that for now - add a little extra padding on this div. There
it is!

Registered as `geordi3@theenterprise.org`, password `engage`, hit enter, and...
yes! We *know* column was set correctly in the database because it's *required*.

Here's the big takeaway: whenever you need a field on your form that doesn't exist
on your entity, there *may* be a clever solution. But, if it's not obvious, make
the field unmapped and add a little bit of glue code to your controller that does
whatever you need.

Later, we'll discuss a *third* option: creating a custom *model* class for your
form.

## Fixing your Fixtures

Before we move on, try to reload the fixtures:

```terminal
php bin/console doctrine:fixtures:load
```

It... explodes! Duh! I made the new `agreedTermsAt` field *required* in the
database, but we are *not* setting it in the fixtures. No problem: open `UserFixture`.
In the first block, add `$user->agreeTerms()`. Copy that, and do the same for
the admin users.

Cool! Try it again:

```terminal-silent
php bin/console doctrine:fixtures:load
```

And.... all better!

Next: let's fix the styling in our registration form by creating our own form *theme*.
