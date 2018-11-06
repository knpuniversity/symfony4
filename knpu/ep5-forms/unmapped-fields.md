# Adding Extra "Unmapped" Fields

Not to be confused with not being used with Jordy. All right, so head back to
`/register`.

Okay. Because

as I mentioned, there is one thing that's bothering me and I mentioned the fact that
if you look at our `UserRegistrationFormType`, the plain password is actually set on
a `password` field, which means when we submit that the `Form` component calls set
password and actually sets that on our `password` field, but the password field is
meant to be an encoded password. And of course before we save it, we do call user get
password to get that plain text password and then we encode it and set up back on
that field. So ultimately it saves in the database as an encoded password. But I
don't like doing this. I don't like ever setting the plain text password on a
password on a field that could be persisted. So go to our `UserRegistrationFormType`
and we're going to change this to `plainPassword`. And I'll put a little comment above
here about why we're doing this. Now, of course this is going to be a problem if we
go over. Now you try to register with the different user. It's going to blow up
neither the property plain password nor one of the methods. Get Plain Password has
plain password, blah blah blah, um, has access on the user. And we know why this is
happening. I mentioned earlier when you make a field in your form called email,

then the `Form` system, we'll use the `getEmail()`

method to read data off of the `User` object. And when we submit these set email method
to put data back on, so we're effectively saying an error here that says, look, you
don't have a get plain password and a set plain password properties or a methods. So
there are two ways to fix this. First, we could actually create a `plainPassword`
property on our entity, make it not persisted, so don't put an `@ORM\Column` on it and
then create a good `plainPassword` and a `setPlainPassword()` method. Or we can mark
this field to not be mapped. Check this out. I'm gonna past `null` the second argument
so it keeps guessing the field for now though, in a second we're going to turn that
into a password field and then we're going to say `'mapped' => false`. What that does is,
and now says that I want to have a field called `plainPassword` on my form just like
before, but now it should not get or set this data back onto our `User` object. So of
course the question then is a, if it's not setting the data on the user, how do we
get the data after all? That's the `User` objects, what we get back from the form
system, so where will that `plainPassword` data live and your controller 
`dd($form['plainPassword']->getData()`,

then move over, refresh and Oh, I get this. Form snack contain extra fields. That's
because I never fully refreshed the new form after renaming my password field. So we
were actually still submitting the old password field and southern new ones. So let's
try that again. This time. Yes, it hits our DI statement. So here's the cool thing
about the `Form` system. There's a `Form` object on top, but then each individual field
is it's for own form object. So this form Lusko back plain password is its own `Form`
object and you can ask individually for its data which will be that plain text
password. This is a super powerful way to handle a situations where you sometimes
have a field that you need in your form, but it doesn't really map cleanly to
something on your entity. So we can copy this, remove the DD and then down below, use
that instead on our field. Now, before we try this, one last thing I do want to
change, is that our. Actually No. So let's move back over. Refresh and this time nice
it submits perfectly. All right, so go back to `/register` one last time before I move
on. One thing I do want to fix is the fastest path that the plan tax. The password
field is actually plain text.

So go back to your form type and obviously even though, um, the form system has no
idea what type of field `plainPassword` is. It's not even a property on our entities,
so it can't really do any type of form guessing, so it just assumes it's a `TextType`.
So change this to `PasswordType::class`. Nothing will change with the way it's
submitted, but it's now going to render as a proper `password` field. Perfect. Next,
let's talk about adding form validation to this, which is going to be a little bit
special because we need to validate that the user is unique in the database, which is
a special constraint and we also need to apply validation to for the first time to a
field that's actually not actually part of our entity class.