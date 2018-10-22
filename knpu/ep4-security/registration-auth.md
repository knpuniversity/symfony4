# Manual Authentication / Registration

Hey! You've made it through almost this *entire* tutorial! Nice work! I have
just a *few* more tricks to show you before we're done - and they're good ones!

## Creating the Registration Form

First, I want to create a registration form. Find your code and open
`SecurityController`. In addition to `login` and `logout`, add a new
`public function register()`:

[[[ code('5e4df14615') ]]]

Give it a route - `/register` and a name: `app_register`:

[[[ code('d3e6cd4344') ]]]

Here's the interesting thing about registration. It has *nothing* to do with
security! Think about it. What *is* registration? It's just a form that creates a
new record in the `User` table. That's it! That's just database stuff.

So then... why are we even *talking* about this in a security tutorial? Well...
to create the *best* user experience, there will be just a *little* bit of security
right at the end. Because, after registration, I want to instantly authenticate the
new user.

More on that later. Right now, render a template:
`$this->render('security/register.html.twig')`:

[[[ code('6c7842eabc') ]]]

Then... I'll cheat: in `security/`, copy the `login.html.twig` template, paste
and call it `register.html.twig`:

[[[ code('3bebd2235a') ]]]

Let's see: change the title, delete the authentication error stuff and I am
going to add a little comment here that says that we should replace this with
a Symfony form later:

[[[ code('1539b8eaea') ]]]

We haven't talked about the form system yet, so I don't want to use it
here. But, normally, I *would* use the form system because it handles validation
and automatically adds CSRF protection.

But, to show off how to manually authenticate a user after registration, this HTML
form will work *beautifully*. Change the `h1`, remove the `value=` on the `email`
field so that it always starts blank and take out the CSRF token:

[[[ code('c190e4c848') ]]]

We *do* need CSRF protection on this form... but I'll skip it for now, because
we'll refactor this into a Symfony form in a future tutorial.

And finally, hijack the "remember me" checkbox and turn it into a terms box.
We'll say:

> Agree to terms I for sure read

[[[ code('3fb1324ce8') ]]]

Oh, and update the button: Register:

[[[ code('955e0cd2e9') ]]]

Let's see how it looks! Move over, go to `/register` and... got it! Logout, then
move back over and open up `base.html.twig`. Scroll down just a little bit to find
the "Login" link. Let's create a second link that points to the new `app_register`
route. Say, "Register":

[[[ code('5fb6ee821a') ]]]

Move back and check it out. Not bad!

## Handing the Registration Submit

Just like with the `login` form, because there is no `action=` on the form, this
will submit right back to the same URL. But, *unlike* login, because this is just
a normal page, we *are* going to handle that submit logic right inside of the
controller.

First, get the Request object by adding an argument with the `Request` type hint:
the one from HttpFoundation. Below, I'm going to add *another* reminder to use the
Symfony form & validation system later:

[[[ code('1eb7adbaf0') ]]]

Then, to only process the data when the form is being submitted, add
`if ($request->isMethod('POST'))`:

[[[ code('c16b681806') ]]]

Inside... our job is simple! Registration is nothing more than a mechanism to
create a new `User` object. So `$user = new User()`. Then set some data on it:
`$user->setEmail($request->request->get('email'))`:

[[[ code('d76ee21ade') ]]]

Remember `$request->request` is the way that you get `$_POST` data. And, the *names*
of the fields on our form are `name="email"` and `name="password"`. But before
we handle the password, add `$user->setFirstName()`. This field is required
in the database... but, we don't *actually* have that field on the form.
Just use `Mystery` for now:

[[[ code('d87b447c37') ]]]

In a real app, I would either add this field to the registration form, or make
it `nullable` in the database, so it's optional.

Finally, let's set the password. But... of course! We are never ever, ever, ever
going to save the *plain* password. We need to encode it. We already did this
inside of `UserFixture`:

[[[ code('9907519028') ]]]

Ah yes, the key was the `UserPasswordEncoderInterface` service. In our controller,
add another argument: `UserPasswordEncoderInterface` `$passwordEncoder`:

[[[ code('8dbdeb999f') ]]]

Below, we can say `$passwordEncoder->encodePassword()`. This needs the `User`
object and the plain password that was just submitted:
`$request->request->get('password')`:

[[[ code('c34abd7c59') ]]]

We are ready to save! Get the entity manager with `$em = $this->getDoctrine()->getManager()`.
Then, `$em->persist($user)` and `$em->flush()`:

[[[ code('ddfc69604e') ]]]

All delightfully boring code. This looks a lot like what we're doing in our
fixtures.

Finally, after *any* successful form submit, we always redirect. Use
`return $this->redirectToRoute()`. This is the shortcut method that we were looking
at earlier. Redirect to the account page: `app_account`:

[[[ code('cb2d29e00e') ]]]

Awesome! Let's give this thing a spin! I'll register as `ryan@symfonycasts.com`,
password `engage`. Agree to the terms that I for sure read and... Register!
Bah! That smells like a Ryan mistake! Yep! Use `$this->getDoctrine()->getManager()`:

[[[ code('27d54b56fb') ]]]

*That's* what I meant to do.

Move over and try this again: `ryan@symfonycasts.com`, password `engage`, agree
to the terms that I read and... Register!

## Authentication after Registration

Um... what? We're on the *login* form? What happened? First, according to the
web debug toolbar, we are still anonymous. That makes sense: we *registered*,
but we did *not* login. After registration, we were redirected to `/account`...

[[[ code('59b08054b3') ]]]

But because we are *not* logged in, that sent us here.

This is *not* the flow that I want my users to experience. Nope, as *soon* as
the user registers, I want to log them in automatically.

Oh, and there's also *another* problem. Open `LoginFormAuthenticator` and find
`onAuthenticationSuccess()`:

[[[ code('dc2b4bbd7e') ]]]

We added some extra code here to make sure that if the user went to, for example,
`/admin/comment` as an anonymous user, then, after they log in, they would be
sent *back* to `/admin/comment`.

And... hey! I want that *same* behavior for my registration form! Imagine that you're
building a store. As an anonymous user, I add some things to my cart and finally
go to `/checkout`. But because `/checkout` requires me to be logged in, I'm sent
to the login form. And because I don't have an account yet, I instead click to register
and fill out that form. After submitting, where should I be taken to? That's easy!
I should *definitely* be taken *back* to `/checkout` so I can continue what I was
doing!

These two problems - the fact that we want to automatically authenticate the user
after registration *and* redirect them intelligently - can be solved at the same
time! After we save the `User` to the database, we're basically going to tell
Symfony to use our `LoginFormAuthenticator` class to authenticate the user and
redirect by using its `onAuthenticationSuccess()` method.

Check it out: add two arguments to our controller. First, a service called
`GuardAuthenticationHandler $guardHandler`. Second, the authenticator that you
want to authenticate through: `LoginFormAuthenticator $formAuthenticator`:

[[[ code('63c5e4fe18') ]]]

Once we have those two things, instead of redirecting to a normal route use
`return $guardHandler->authenticateUserAndHandleSuccess()`:

[[[ code('1f74b4ffc7') ]]]

This needs a few arguments: the `$user` that's being logged in, the `$request` object,
the authenticator - `$formAuthenticator` and the "provider key". That's just the name
of your firewall: `main`:

[[[ code('00ac8c7e54') ]]]

Cool! Let's try it! Click back to register. This time, make sure that you register
as a different user, password `engage`, agree to the terms, submit and... nice!
We're authenticated *and* sent to the correct place.

Next - we're going to start talking about a *very* important and *very* fun feature
called "voters". Voters are *the* way to make more *complex* access decisions,
like, determining that a User can edit *this* Article because they are its author,
but not an Article created by someone else.
