# Entry Point: Helping Users Authenticate

We now have a database table full of API Tokens where each is related to a `User`.
I can *already* feel the API power! So here's our new goal: when an API request
sends a valid API token string, we'll read it and *authenticate* that request *as*
the `User` who owns the token:

[[[ code('224759e601') ]]]

## make:auth ApiTokenAuthenticator

This will be the *second* way that users can authenticate in our app. So, we
need a *second* authenticator. Find your terminal and run:

```terminal
php bin/console make:auth
```

If you see a question about choosing which *type* of authentication you want, choose
an "Empty authenticator". I'm using an older version of the command, which *only*
generates empty authenticators. Call it `ApiTokenAuthenticator`. Oh, and you may
also be asked a question about an "Entry point". We'll talk about that soon, but
choose the `LoginFormAuthenticator` option.

Ok, go check this out!

[[[ code('d40df35e9a') ]]]

Hey! I know this class! It's that same, big, adorable empty authenticator we saw earlier.
To tell Symfony to use this, open `config/packages/security.yaml` and add the new
class under `authenticators`:

[[[ code('f5c0a93821') ]]]

If you're using that newer, fancier version of this command, it already did this
for you. Lucky you!

As *soon* as we do this, the `supports()` method will be called at the beginning
of *every* request. But... refresh. Woh! Big error!

> Because you have multiple guard authenticators, you need to set the
> "guard.entry_point" key to one of your authenticators.

## What is an Entry Point?

If you did *not* see this error, it's your lucky day! Well, really, it's because
the newer `make:auth` command took care of this step for you! But, it *is* important
to understand. Move back to `security.yaml` and, under `guard`, make sure you have
key called `entry_point`. Your `make:auth` command probably added it for you. If
not, add it, copy the `LoginFormAuthenticator` class and paste:

[[[ code('3f21092b74') ]]]

So... what the heck is an entry point anyways? Your firewall has exactly one "entry point"
and its job is simple: to determine what should happen when an anonymous user tries
to access a protected page. So far, if we, for example, went to `/admin/comment`
without being logged in, our "entry point" has been redirecting users to `/login`.

But, where does that entry point code live? Actually, it's inside our
`LoginFormAuthenticator`! Ok, really, it's in the parent class. Hold `Command` or
`Ctrl` and click to open `AbstractFormLoginAuthenticator`.

Every authenticator has a method called `start()` and *it* is the entry point.
*This* is the method that Symfony calls when an anonymous user tries to access a
protected page. And, no surprises: it redirects you to the login page.

Nice! Except... there's a slight problem: while you can have as *many* authenticators
as you want for a firewall, you can only have *one* entry point. Why? Think about
it: when an anonymous user tries to access a protected page, well, they're not using
any of our authenticators yet: it's just an anonymous user sending *no* authentication
info. So, Symfony doesn't know *which* of your authenticators it should use as the
entry point. That's why we need to tell it *specifically* which authenticator's
`start()` method to use.

In our app, we will *always* redirect anonymous users to the login form. Of course,
if you want to make this logic smarter, you could override the `start()`
method in `LoginFormAuthenticator` and make it do different things under different
conditions. Like, maybe you return an API response instead of redirecting if the
URL starts with `/api`.

Anyways, when we refresh now, it works just like we expect: it redirects us to `/login`.
Log back in with password `engage` and.... awesome! We're back!

Time to start filling in our authenticator!
