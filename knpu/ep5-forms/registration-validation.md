# UniqueEntity & Validation Directly on Form Fields

The registration form works, but we have a few problems. First, geez, it looks
terrible. We'll fix that a bit later. More *importantly*, it *completely* lacks
validation... except, of course, for the HTML5 validation that we get for free. But,
we can't rely on that.

No problem: let's add some validation constraints to `email` and `plainPassword`!
We know how to do this: add annotations to the class that is bound to this form:
the `User` class. Find the `email` field and, above, add  `@Assert\NotBlank()`.
Make sure to hit tab to auto-complete this so that PhpStorm adds the `use` statement
that we need on top. Also add `@Assert\Email()`.

[[[ code('e75c2108b9') ]]]

Nice! Move back to your browser and inspect the form. Add the `novalidate` attribute
so we can skip HTML5 validation. Then, enter "foo" and, submit! Nice! Both of these
validation annotations have a `message` option - let's customize the `NotBlank`
message: "Please enter an email".

[[[ code('d3a64e9b6e') ]]]

Cool! `email` field validation, done!

## Unique User Validation

But... hmm... there's one *other* validation rule that we need that's related to
email: when someone registers, we need to make sure their email address isn't already
registered. Try `geordi@theenterprise.org` again. I'll add the `novalidate` attribute
so I can leave the password empty. Register! It *explodes*!

> Integrity constraint violation: duplicate entry "geordi@theenterprise.org

Ok, *fortunately*, we *do* have the `email` column marked as unique in the database.
But, we *probably* don't want a 500 error when this happens.

This is the *first* time that we need to add validation that's not just as simple
as "look at this field and make sure it's not blank", "or a valid email string".
This time we need to look into the database to see if the value is valid.

When you have more complex validation situations, you have two options. First, try
the `Callback` constraint! This allows you do *whatever* you need. Well, *mostly*.
Because the callback lives inside your entity, you don't have access to any services.
So, you couldn't make a query, for example. If `Callback` doesn't work, the solution
that *always* works is to create your very own custom validation constraint. That's
something we'll do later.

Fortunately, we don't need to do that here, because validating for uniqueness is
*so* common that Symfony has a built-in constraint to handle it. But, instead of
adding this annotation above your property, it lives above your *class*. Add
`@UniqueEntity`. Oh, and notice! This added a *different* `use` statement because
this class happens to live in a different namespace than the others.

This annotation needs at least one option: the `fields` that, when combined, need
to be unique. For us, it's just `email`. You'll probably want to control the message
too. How about: `I think you've already registered`.

[[[ code('da4563087e') ]]]

Oh, and just a reminder: if you have the PHP annotations plugin installed, you can
hold command or control and click the annotation to open its class and see all its
options.

Let's try it! Move over and refresh! Got it! That's a *much* nicer error.

## Adding Validation Directly to Form Fields

There is *one* last piece of validation that's missing: the `plainPassword` field. At
the very least, it needs to be *required*. But, hmm. In the form, this field is
set to `'mapped' => false`. There *is* no `plainPassword` property inside `User`
that we can add annotations to!

No problem. Yes, we *usually* add validation rules via annotations on a class. But,
if you have a field that's not mapped, you can add *its* validation rules directly
to the form field via a `constraints` array option. What do you put inside? Remember
how each annotation is represented by a concrete class? That's the key! *Instantiate*
those as objects here: `new NotBlank()`. To pass options, use an array and set
`message` to `Choose a password!`.

Heck, while we're here, let's also add `new Length()` so we can require a minimum
length. Hold command or control and click to open that class and see the options.
Ah, yea: `min`, `max`, `minMessage`, `maxMessage`. Ok: set `min` to, how about 5
and `minMessage` to `Come on, you can think of a password longer than that!`

[[[ code('2e543f4867') ]]]

Done! These constraint options will work *exactly* the same as the annotations.
To prove it, go back and refresh! Got it! Now, validating an unmapped field is no
problem. We rock!

Next: the registration form is missing one *other* field: the boring, but, unfortunately,
all-important "Agree to terms" checkbox. The solution... is interesting.
