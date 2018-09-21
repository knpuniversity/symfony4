# Authentication Errors

Go back to the login page. I wonder what happens if we *fail* the login... which,
is only possible right now if we use a non-existent email address. Oh!

> Cannot redirect to an empty URL

## Filling in getLogUrl()

Hmm: this is coming from `AbstractFormLoginAuthenticator` *our* authenticator's
base class. If you dug a bit, you'd find out that, on failure, that authenticator
class is calling `getLoginUrl()` and trying to redirect *there*. And, yea, that makes
sense: if we fail login, the user should be redirected *back* to the login page.
To make this actually work, all *we* need to do is fill in this method.

No problem: `return $this->router->generate('app_login')`:

[[[ code('e13511cfbc') ]]]

Ok, try it again: refresh and... perfect! Hey! You can even see an error message
on top:

> Username could not be found.

We get *that* exact error because of *where* the authenticator fails: we *failed*
to return a user from `getUser()`:

[[[ code('0a3772d49e') ]]]

In a little while, we'll learn how to customize this message because... probably saying
"Email" could not be found would make more sense.

The *other* common place where your authenticator can fail is in the `checkCredentials()`
method:

[[[ code('6f40ca2386') ]]]

Try returning `false` here for a second:

```php
// ...
    public function checkCredentials($credentials, UserInterface $user)
    {
        return false;
    }
// ...
```

Then, login with a *legitimate* user. Nice!

> Invalid credentials.

Anyways, go change that back to `true`:

[[[ code('6f40ca2386') ]]]

## How Authentication Errors are Stored

What I *really* want to find out is: where are these errors coming from? In
`SecurityController`, we're getting the error by calling some
`$authenticationUtils->getLastAuthenticationError()` method:

[[[ code('100ef6e740') ]]]

We're passing that into the template and rendering its `messageKey` property...
with some translation magic we'll talk about soon too:

[[[ code('aba6e4ca27') ]]]

The point is: we magically fetch the "error" from... somewhere and render it. Let's
demystify that. Go back to the top of your authenticator and hold command
or control to click into `AbstractFormLoginAuthenticator`.

In reality, when authentication fails, this `onAuthenticationFailure()` method is
called. It's a bit technical, but when authentication fails, internally, it's because
something threw an `AuthenticationException`, which is passed to this method. And,
ah: this method *stores* that exception onto a special key in the session! Then,
back in the controller, the `lastAuthenticationError()` method is just a *shortcut*
to read that key *off* of the session!

So, it's simple: our authenticator stores the error in the session and then we read
the error *from* the session in our controller and render it:

[[[ code('56a52016a1') ]]]

The *last* thing `onAuthenticationFailure()` does is call our `getLoginUrl()` method
and redirect there.

## Filling in the Last Email

Go back to the login form and fail authentication again with a fake email. We see
the error... but the email field is empty - that's not ideal. For convenience, it
*should* pre-fill with the email I just entered.

Look at the controller again. Hmm: we *are* calling a `getLastUsername()` method
and passing that into the template:

[[[ code('214125d04d') ]]]

Oh, but I forgot to render it! Add `value=` and print `last_username`:

[[[ code('6d64209c92') ]]]

But... we're not quite done. Unlike the error message, the last user name is *not*
automatically stored to the session. This is something that *we* need to do inside
of our `LoginFormAuthenticator`. But, it's super easy. Inside `getCredentials()`,
instead of returning, add `$credentials = `:

[[[ code('370ce878cb') ]]]

Now, set the email onto the session with `$request->getSession()->set()`.
Use a special key: `Security` - the one from the Security component - `::LAST_USERNAME`
and set this to `$credentials['email']`:

[[[ code('019ca097db') ]]]

Then, at the bottom, return `$credentials`:

[[[ code('35337fcd3f') ]]]

Try it! Go back, login with that same email address and... nice! Both the error
*and* the last email are read from the session and displayed.

Next: let's learn how to *customize* these error messages. And, we really need
a way to logout.
