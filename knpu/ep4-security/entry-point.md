# Entry Point: Helping Users Authenticate

We now have a database table full of API Tokens where each is related to a `User`.
Here's our new goal: when an API request sends us a valid API token string, we'll
read it and *authenticate* that request as the `User` who owns the token.

## make:auth ApiTokenAuthenticator

This will be the *second* way that users can authenticate in our system. So, we
need a *second* authenticator. Find your terminal and run:

```terminal
php bin/console make:auth
```

If you see a question about choosing which *type* of authentication you want, choose
an "Empty authenticator". I'm using an older version of the command, which *only*
generates empty authenticators. Call it `ApiTokenAuthenticator`. Oh, and you may
also be asked a question about an "Entry point". We'll talk about this soon, but
choose the `LoginFormAuthenticator` option.

Ok, go check that out! Hey! We know this class: it's that same, big, empty authenticator
we saw earlier. To tell Symfony to use this, open `config/packages/security.yaml`
and add the new class under `authenticators`. If you're using that newer, fancier
version of this command, the command already did this for you. Nice!

As *soon* as we do this, the `supports()` method will be called at the beginning
of *every* request. But... refresh. Woh! Big error!

> Because you have multiple guard authenticators, you need to set the
> "guard.entry_point" key to one of your authenticators.

## What is an Entry Point?

If you did *not* see this error, it's because the newer `make:auth` command took
care of this step for you! But, it's *super* important to understand. Move back
to `security.yaml` and, under `guard`, make sure you have config key called
`entry_point`. Tour `make:auth` command probably added it for you. If not, add
it, copy the `LoginFormAuthenticator` class and paste here.

So... what the heck is an entry point anyways? Your firewall has exactly one "entry point"
and its job is simple: to determine what should happen when an anonymous user tries
to access a protected page. So far, the "entry point" has been redirecting users
to the login form.

But, where does that entry point code live? Actually, it's inside our
`LoginFormAuthenticator`. Well, really, it's in its parent class. Hold Command or
Ctrl and click to open `AbstractFormLoginAuthenticator`.

Every authenticator has a method called `start()` and *it* is the entry point:
*this* is the method that Symfony calls when an anonymous user tries to access a
protected page. And, no surprises: it redirects you to the login page.

Nice! Except... there's a slight problem: while you can have as *many* authenticators
as you want on a firewall, you can only have *one* entry point. Why? Think about
it: when an anonymous user tries to access a protected page, well, they're not using
any of your authenticators yet: it's just an anonymous user sending *no* authentication
info. So, Symfony doesn't know *which* of your authenticators it should use as the
entry point. That's why we need to tell it *specifically* which authenticator's
`start()` method to use.

In our app, we will *always* redirect anonymous users to the login form. Of course,
if you want to make this logic smarter, you could override the `start()` method
in `LoginFormAuthenticator` and make it do different things under different
conditions. Like, maybe you return an API response instead of redirecting in
certain situations.

Anyways, when we refresh now, it works just like we expect: it redirects us to the
login page. Log back in with password `engage` and.... awesome! We're back!

Time to start filling in our authenticator!
