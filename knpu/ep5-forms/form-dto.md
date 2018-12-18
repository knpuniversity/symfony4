# Form Model Classes (DTOs)

I want to talk about a *different* strategy that we could have used for the registration
form: a strategy that many people really love. The form class behind this is
`UserRegistrationFormType` and it's bound to our `User` class. That makes sense:
we ultimately want to create a `User` object. But this was an interesting form
because, out of its three fields, *two* of them don't map back to a property on our
`User` class! There is no `plainPassword` property or `agreeTerms` property on
`User`. To work around this, we used a nice trick - setting `mapped` to `false` -
which allowed us to have these fields without getting an error. Then, in our
controller, we just need to read that data in a different way: like with
`$form['plainPassword']->getData()`

This is a *great* example of a form that doesn't look *exactly* like our entity
class. And when your form starts to look different than your entity class, or maybe
it looks more like a combination of several entity classes, it might *not* make
sense to try to bind your form to your entity at all! Why? Because you might have
to do all *sorts* of crazy things to get that to work, including using *embedded*
forms, which isn't even something I like to talk about.

What's the better solution? To create a model class that looks just like your form.

## Creating the Form Model Class

Let's try this out on our registration form. In your `Form/` directory, I like to
create a `Model/` directory. Call the new class `UserRegistrationFormModel`.
The purpose of this class is *just* to hold data, so it doesn't need to extend
anything. And because our form has three fields - `email`, `plainPassword` and
`agreeTerms` - I'm going to create three *public* properties:
`email`, `plainPassword`, `agreeTerms`.

[[[ code('638e4df587') ]]]

Wait, why public? We *never* make public properties! Ok, yes, we *could*
make these properties private and then add getter and setter methods for them.
That *is* probably a bit better. But, because these classes are *so* simple and have
just this *one* purpose, I often cheat and make the properties public, which works
fine with the form component.

Next, in `UserRegistrationFormType`, at the bottom, instead of binding our class
to `User::class`, bind it to `UserRegistrationFormModel::class`.

[[[ code('0bdc4352b9') ]]]

And... that's it! Now, instead of creating a new `User` object and setting the
data onto it, it will create a new `UserRegistrationFormModel` object and put the
data there. And *that* means we can remove both of these `'mapped' => false` options:
we *do* want the data to be mapped back onto that object.

In the controller, the *big* difference is that `$form->getData()` will *not* be
a `User` object anymore - it will be a `$userModel`. I'll update the inline doc
above this to make that obvious.

[[[ code('50e4b6e008') ]]]

When you use a model class, the downside is that you need to do a bit more work to
*transfer* the data from our model object into the entity object - or *objects* -
that actually need it. That's why these model classes are often called
"data transfer objects": they just hold data and help transfer it between systems:
the form system and our entity classes.

Add `$user = new User()` and `$user->setEmail($userModel->email)`. For the `password`
field, it's almost the same, but now the data comes from `$userModel->plainPassword`.
Do the same thing for `$userModel->agreeTerms`.

[[[ code('9c72a7a9c3') ]]]

The benefit of this approach is that we're using this nice, concrete PHP class,
instead of referencing specific array keys on the form for unmapped fields.
The downside is... just more work! We need to transfer *every* field from the
model class back to the `User`.

And also, if there were an "edit" form, we would need to create a new
`UserRegistrationFormModel` object, populate *it* from the existing `User` object,
and pass *that* as the second argument to `->createForm()` so that the form is
pre-filled. The best solution is up to you, but these data transfer objects - or
DTO's, are a pretty clean solution.

Let's see if this actually works! I'll refresh just to be safe. This time,
register as `WillRyker@theenterprise.org`, password `engage`, agree to the terms,
register and... got it!

## Validation Constraints

Mission accomplished! Right? Wait, no! We forgot about validation! For example,
check out the `email` field on `User`: we *did* add some `@Assert` constraints 
above this! But... now that our form is not bound to a `User` object, these constraints
are *not* being read! It is *now* reading the annotations off of *these* properties...
and we don't have any!

Go back to your browser, inspect element on the form and add the `novalidate` attribute.
Hit register to submit the form blank. Ah! We *do* have *some* validation: for
the password and agree to terms fields. Why? Because those constraints were added
into the form class itself.

Let's start fixing things up. Above the `email` property, paste the two existing
annotations. I *do* need a use statement for this: I'll cheat - add another
`@Email`, hit tab - there's the `use` statement - and then delete that extra line.

[[[ code('4f3587f05e') ]]]

At this point, if you *want* to, you can remove these annotations from your `User`
class. But, because we might use the `User` class on a form somewhere else - like
an edit profile form - I'll keep them there.

One of the really nice things about using a form model class is that we can remove
the constraints from the form and put them in the model class so that we have everything
in one place. Above `$plainPassword`, add `@Assert\NotBlank()` and
`@Assert\Length()`. Let's pass in the same options: `message=""` and copy that
from the form class. Then copy the `minMessage` string, add `min=5`,
`minMessage=` and paste.

Finally, above `agreeTerms`, go copy the message from the form, and add the same
`@Assert\IsTrue()` with `message=` that message.

[[[ code('8210b51fd6') ]]]

Awesome! Let's celebrate by removing these from our form! Woo! Time to try it!
Find your browser, refresh and... ooook - annotations parse error! It's a Ryan
mistake! Let's go fix that - ah - what can I say? I love quotes!

Try it again. Much better! All the validation constraints are being cleanly read
from our model class.

Except... for one. Go back to your `User` class: there was *one* more validation
annotation on it: `@UniqueEntity()`. Copy this, go back into `UserRegistrationFormModel`
and paste this above the class. We need a special `use` statement for this, so
I'll re-type it, hit tab and... there it is! This annotation happens to live in
a different namespace than all the others.

[[[ code('aef263d438') ]]]

Let's try this - refresh. Woh! Huge error!

> Unable to find the object manager associated with an entity of class `UserRegistrationFormModel`

It thinks our model class is an entity! And, bad news friends: it is *not* possible
to make `UniqueEntity` work on a class that is *not* an entity class. That's a
bummer, but we *can* fix it: by creating our very-own custom validation constraint.
Let's do that next!
