# API Auth: Do you Need it? And its Parts

Before we dive into the code, we need to have a heart-to-heart about API authentication.
Because... I think there's some confusion out there that tends to make people
over-complicate things. And I *never* want to over-complicate things.

## Do you Need API Authentication

First, you need to ask yourself a very important question:

> Do you actually need an API token authentication system?

There's a *pretty* good chance that the answer is... no, *even* if your app has
API endpoints:

[[[ code('1a34e1b4b1') ]]]

If you're creating API endpoints *solely* so that your *own* JavaScript for your
*own* site can use them, then, you do *not* need an API token authentication system.
Nope! Your life will be much simpler if you use a normal login form and session-based
authentication.

Yep! You probably already know, that, once you login via a login form, you can
instantly make authenticated AJAX requests from JavaScript, because those requests
send the session cookie. So, if the only thing that needs to use your API is your
own JavaScript, just use `LoginFormAuthenticator`.

Oh, and if you need to be fancier with your login form, sure! You can *totally* use
JavaScript to make the login form submit via AJAX. Nothing would need to change in
your authenticator, except that you would probably want to send back JSON on success,
instead of redirecting:

[[[ code('a44af57764') ]]]

You would also override `onAuthenticationError()` and the `start()` method to do
the same. We'll learn more about those methods soon.

Of course, even if your JavaScript will be the only thing using your API, you
*can* still build an API token authentication system, if you want. And if you
need *other* things to be able to access your API, then you *need* a token system.

## Two Sides of API Token Authentication

If you're still here, then you've either decided that you *do* need an API token
authentication system, or you just want to nerd out with us on this topic. Me too!

This brings us to important topic number 2! An API token authentication system
has two, quite *unrelated* parts. The first is *how* your app processes an existing
API token and logs in the user. The second is how those API tokens are *created*
and *distributed*.

## Part 1: Processing an API Token

For the first part, no matter how you build it, an API token is just a string
that is somehow connected to a `User` in your system. The client that makes the
request sets the token string on a header and then they become authenticated *as*
that User. There are some variations on this, like, giving tokens "scopes" or
"permissions" so that they can only do *some* things that a user can do, but that's
the basic idea.

The *way* that the token string is related to the user can be done in a few
different ways. For example, you could have an API token database table where each
random API token has a relationship to a row in the user table. It's simple: our
app reads the token string from a header, finds that API token in the database,
finds the `User` it's related to, and authenticates as that user. We're going to
build exactly this.

Another variation is JSON web tokens. In this case, instead of the token being
a random string, the user's information - like the user id - is used to create a
signed string. In that case, your app reads the header, verifies the signature
on the string, and uses the `id` inside that string to query for the `User`.

Anyways, *that* is the first part of API token authentication: designing your app
to be able to read API tokens from an API request, and use that information - somehow -
to find the correct `User` and authenticate them.

## Part 2: Creating & Distributing API Tokens

The *second* part of an API authentication system asks this question:

> How are these API tokens created and distributed?

It turns out that *this* is a totally separate conversation. And, once again,
there are several valid answers. I'll give you 3 examples with when each should
probably be used. Actually, the GitHub API is an example of a system that allows
you to do all *three* of these.

First, you could allow API tokens to be created through a web interface. Like, a
user logs in, they navigate to some API token page, and then they create one or
more API tokens that are tied to their account. This solution is dead simple.
The negative is that there is no automated way to create an API token: you can't
write a script that can create them. It must be done manually.

Second, you could write an API endpoint whose jobs is to create & return tokens.
In this example, you would send your email & password to the API endpoint, it would
validate them, then create & return the token. This is *still* pretty simple, but
now it's programmable: you can write a script that can create tokens on its own.
The *downside* is that this solution can't be used by third parties. What I mean
is, it's okay for the *user* to write some code that sends their own email and
password to an API endpoint in order to create a token. But, if some third-party
were building an iPhone app for your site, that app should *not* use this method.
Why? Because it would require the user to enter their email & password directly into
the app, so that it could send the info to our API. Ideally, we *never* want users
to give their password to a third-party.

This leads us to the *third* way of creating & distributing tokens: OAuth2. If
you need third-parties to be able to securely create & get API tokens for your
users, then you probably need OAuth. The only negative is that OAuth is more complex.

Phew! So, the *whole* second part of API token authentication... well, really has
nothing to do with authentication at all! It's more about how these secrets keys
are created and handed out to who needs them. So, we are *not* going to talk about
that part of API authentication.

But we *are* going to build the first part: the *true* authentication part. Let's
get to work!
