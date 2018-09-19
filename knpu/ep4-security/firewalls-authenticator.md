# Firewalls & Authenticator

We built a login form with a traditional route, controller and template. And so
you *might* expect that because the form submits back to this same URL, the submit
logic would live right inside this controller:

[[[ code('695952a844') ]]]

Like, if the request method is POST, we would grab the email, grab the password and
do some magic.

## What are Authentication Listeners / Authenticators?

Well... we are *not* going to do that. Symfony's security works in a bit of a
"magical" way, at least, it feels like magic at first. At the beginning of every
request, Symfony calls a set of "authentication listeners", or "authenticators".
The job of each authenticator is to look at the request to see if there is any authentication
info on it - like a submitted email & password or maybe an API token that's stored
on a header. *If* an authenticator finds some info, it then tries to use it to find
the user, check the password if there is one, and log in the user! *Our* job is to
write these authenticators.

## Understanding Firewalls

Open up `config/packages/security.yaml`. The *most* important section of this
file is the `firewalls` key:

[[[ code('942e4ef121') ]]]

Ok, what the heck is a "firewall" in Symfony language? First, let's back up.
There are *two* main parts of security: authentication and authorization.
Authentication is all about finding out *who* you are and making you prove it.
It's the login process. Authorization happens after authentication:
it's all about determining whether or not you have access to something.

The *whole* job of the firewall is to *authenticate* you: to figure out who you are.
And, it *usually* only makes sense to have *one* firewall in your app, *even* if
you want your users to have *many* different ways to login - like a login form
or API authentication.

But... hmm... Symfony gave us *two* firewalls by default! What the heck? Here's
how it works: at the beginning of each request, Symfony determines the *one* firewall
that matches the current request. It does that by comparing the URL to the regular
expression `pattern` config. And if you look closely... the first firewall is a fake!

[[[ code('4c41489db1') ]]]

It becomes the active firewall if the URL starts with `/_profiler`, `/_wdt`, `/css`,
`/images` or `/js`. *When* this is the active firewall, it sets security to `false`.
Basically, this firewall exists *just* to make sure that we don't make our site
*so* secure that we block the web debug toolbar or some of our static assets.

In reality, we only have *one* real firewall called `main`:

[[[ code('f7ec5ea472') ]]]

And because it does *not* have a `pattern` key, it will be the active firewall
for *all* URLs, except the ones matched above. Oh, and, in case you're wondering,
the names of the firewalls, `dev` and `main` are totally meaningless.

Anyways, because the job of a firewall is to authenticate the user, most of the config
that goes below a firewall relates to "activating" new authentication listeners -
those things that execute at the beginning of Symfony and try to log in the user.
We'll add some new config here pretty soon.

Oh, and see this `anonymous: true` part?

[[[ code('e09d08a770') ]]]

Keep that. This allows *anonymous* requests to pass through this firewall so that
users can access your public pages, without needing to login. *Even* if you want
to require authentication on *every* page of your site, keep this. There's a different
place - `access_control` - where we can do this better:

[[[ code('ca4f64d436') ]]]

## Creating the Authentication with make:auth

Ok, let's get to work! To handle the login form submit, we need to create our very
first authenticator. Find your terminal and run `make:auth`:

```terminal-silent
php bin/console make:auth
```

Call the new class `LoginFormAuthenticator`.

***TIP
Very soon, this command will contain more interactive questions and be able
to generate your entire login form code. That's awesome! But to follow with this
tutorial choose the "empty" authenticator option.
***

Nice! This creates one new file: `src/Security/LoginFormAuthenticator.php`:

[[[ code('4c53e58309') ]]]

This class is awesome: it basically has a method for each step of the authentication
process. Before we walk through each one, because this authenticator will be for
a login form, there's a different base class that allows us to... well... do less
work!

Instead of `extends AbstractGuardAuthenticator` use `extends AbtractFormLoginAuthenticator`:

[[[ code('bb12700cb9') ]]]

I'll remove the old `use` statement.

Thanks to this, we no longer need `onAuthenticationFailure()`, `start()` or
`supportsRememberMe()`: they're all handled for us:

[[[ code('f14f31f028') ]]]

But don't worry, when we create an API token authenticator later, we *will* learn
about these methods. We *do* now need one *new* method. Go to the "Code"->"Generate"
menu, or `Command`+`N` on a Mac, and select "Implement Methods" to generate `getLoginUrl()`:

[[[ code('10fb7f5cda') ]]]

## Activating the Authenticator in security.yaml

Perfect! Unlike a lot of features in Symfony, this authenticator won't be activated
automatically. To tell Symfony about it, go *back* to `security.yaml`. Under the
`main` firewall, add a new `guard` key, a new `authenticators` key below that,
and add one item in that array: `App\Security\LoginFormAuthenticator`:

[[[ code('8b2d8c862a') ]]]

The whole authenticator system comes from a part of the Security component called
"Guard", hence the name. The important part is that, as *soon* as we add this,
at the beginning of *every* request, Symfony will call the `supports()` method on
our authenticator.

To prove it, add a `die()` statement with a message:

[[[ code('f8759ff1c8') ]]]

Then, move over and, refresh! Got it! And it doesn't matter *what* URL we go to:
the `supports()` method is always called at the start of the request.

And now, we're in business! Let's fill in these methods and get our user logged in.
