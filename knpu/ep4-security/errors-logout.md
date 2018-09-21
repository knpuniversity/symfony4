# Customizing Errors & Logout

If we enter an email that doesn't exist, we get this

> Username could not be found

error message. And, as we saw a moment ago, if we return `false` from
`checkCredentials()`, the error is something about "Invalid credentials".

The point is, depending on *where* authentication fails, the user will see one of
these two messages.

The question *now* is, what if we want to customize those? Because, username could
not be found? Really? In an app that doesn't use usernames!? That's... confusing.

## Customizing Error Messages

There are two ways to control these error messages. The first is by throwing
a very special exception class from anywhere in your authenticator. It's called
`CustomUserMessageAuthenticationException`. When you do this, you can create your
own message. We'll do this later when we build an API authenticator.

The second way is to *translate* this message. No, this isn't a tutorial about
translations. But, if you look at your login template, when we print this
`error.messageKey` thing, we are *already* running it through Symfony's translation
filter:

[[[ code('36c5d2f177') ]]]

Another way to look at this is on the web debug toolbar. See this little translation
icon? Click that! Cool: you can see all the information about translations that
are being processed on this page. Not surprisingly - since we're not trying to translate
anything - there's only one: "Username could not be found."... which... is being
translated into... um... "Username could not be found."

Internally, Symfony ships with translation files that will translate these authentication
error messages into most other languages. For example, if we were using the `es`
locale, we would see this message in Spanish.

Ok, so, why the heck do we care about all of this? *Because*, the errors are passed
through the translator, we can *translate* the English into... *different* English!

Check this out: in your `translations/` directory, create a `security.en.yaml` file:

[[[ code('c1f75861ab') ]]]

This file is called *security* because of this `security` key in the translator.
This is called the translation "domain" - it's kind of a translation category - a
way to organize things.

Anyways, inside the file, copy the message id, paste that inside quotes, and assign
it to our newer, hipper message:

> Oh no! It doesn't look like that email exists!

[[[ code('2d1eff7a44') ]]]

That's it! If you go back to your browser and head over to the login page, in theory,
if you try failing login now, this should work instantly. But... no! Same message.
Today is *not* our lucky day.

This is thanks to a small, um, bug in Symfony. Yes, yes, they *do* happen sometimes,
and this bug only affects our development... slightly. Here's the deal: whenever
you create a *new* translation file, Symfony won't see that file until you manually
clear the cache. In your terminal, run:

```terminal
php bin/console cache:clear
```

When that finishes, go back and try it again: login with a bad email and... awesome!

## Logging Out

Hey! Our login authentication system is... done! And... not that I want to rush
our moment of victory - we did it! - but now that our friendly alien users can log
*in*... they'll probably need a way to log *out*. They're just never satisfied...

Right now, I'm still logged in as `spacebar1@example.com`. Let's close a few files.
Then, open `SecurityController`. Step 1 to creating a logout system is to create
the route. Add `public function logout()`:

[[[ code('0459f9e482') ]]]

Above this, use the normal `@Route("/logout")` with the name `app_logout`:

[[[ code('b3c3879565') ]]]

And *this* is where things get interesting... We *do* need to create this route...
but we *don't* need to write any *logic* to log out the user. In fact, I'm feeling
so sure that I'm going to throw a `new Exception()`:

> will be intercepted before getting here

[[[ code('e7d27fa98a') ]]]

Remember how "authenticators" run automatically at the beginning of every request,
before the controllers? The logout process works the same way. All *we* need to do
is tell Symfony what *URL* we want to use for logging out.

In `security.yaml`, under your firewall, add a new key: `logout` and, below that,
`path` set to our logout route. So, for us, it's `app_logout`:

[[[ code('f795a5cca4') ]]]

That's it! *Now*, whenever a user goes to the `app_logout` route, at the beginning
of that request, Symfony will automatically log the user out and then redirect them...
*all* before the controller is ever executed.

So... let's try it! Change the URL to `/logout` and... yes! The web debug toolbar
reports that we are once again floating around the site anonymously.

By the way, there *are* a few other things that you can customize under the `logout`
section, like *where* to redirect. You can find those options in the Symfony reference
section.

But now, we need to talk about CSRF protection. We'll also add remember me functionality
to our login form with almost no effort.
