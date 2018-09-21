# CSRF Protection

Our login form is working perfectly. But... there's one *tiny* annoying detail that
we need to talk about: the fact that *every* form on your site that performs an
action - like saving something *or* logging you in - needs to be protected by a
CSRF token. When you use Symfony's form system, CSRF protection is built in. But
because we're *not* using it here, we need to add it manually. Fortunately, it's
no big deal!

## Adding the CSRF Input Field

Step one: we need to add an `<input type="hidden">` field to our form. For the
name... this could be anything, how about `_csrf_token`. For the value, use a special
`csrf_token()` Twig function and pass it the string `authenticate`:

[[[ code('40ebb0876a') ]]]

What's that? It's sort of a "name" that's used when creating this token, and it could be anything.
We'll use that *same* name in a minute when we check to make sure the submitted
token is valid.

## Verifying the CSRF Token

In fact, what a great idea! Let's do that now! Step 2 happens inside of `LoginFormAuthenticator`.
Start in `getCredentials()`: in addition to the `email` and `password`, let's *also*
return a `csrf_token` key set to `$request->request->get('_csrf_token')`:

[[[ code('140386506b') ]]]

Next, in `getUser()`, *this* is where we'll check the CSRF token. We could do it
down in `checkCredentials()`, but I'd rather make sure it's valid *before* we
query for the user.

So... how do we check if a CSRF token is valid? Well... like pretty much everything
in Symfony, it's done with a service. Without even reading the documentation, we
can probably find the service we need by running:

```terminal
php bin/console debug:autowiring
```

And searching for CSRF. Yea! There are a few: a CSRF token manager, a token
generator and some sort of token storage. The second two are a bit lower-level:
the `CsrfTokenManagerInterface` is what we want.

To get this, go back to your constructor and add a third argument: `CsrfTokenManagerInterface`.
I'll re-type the "e" and hit tab to auto-complete that so that PhpStorm politely
adds the `use` statement on top of the file:

[[[ code('16d8caa92b') ]]]

Call the argument `$csrfTokenManager` and hit `Alt`+`Enter` to initialize that field:

[[[ code('1de6260368') ]]]

Perfect! To see how this interface works, hold `Command` or `Ctrl` and click into it.
Ok: we have `getToken()`, `refreshToken()`, `removeToken()` and... yes:
`isTokenValid()`! Apparently we need to pass this a `CsrfToken` object, which *itself*
needs two arguments: id and value. The `id` is referring to that string - `authenticate` -
or whatever string you used when you originally generated the token:

[[[ code('20d1d4f952') ]]]

The `value` is the CSRF token *value* that the user submitted.

Let's close all of this. Go back to `LoginFormAuthenticator` and find `getUser()`.
First, add `$token = new CsrfToken()` and pass this `authenticate` and then the
submitted token: `$credentials['csrf_token']`:

[[[ code('ea808800e6') ]]]

Because that's the key we used in `getCredentials()`:

[[[ code('77ebe4895b') ]]]

Then, if not `$this->csrfTokenManager->isTokenValid($token)`, throw a special
new `InvalidCsrfTokenException()`:

[[[ code('97710097d0') ]]]

That's it! Let's first try logging in successfully. Refresh the login form to get
the new hidden input. Use `spacebar1@example.com`, any password and... success!

Now, go back. Let's be shifty and mess with stuff. Inspect element on the form,
find the token field, change it and queue your evil laugh. Mwahahaha. Log in!
Ha! Yes! Invalid CSRF token! We rock!

Next: let's add a really convenient feature for users: a remember me checkbox!
