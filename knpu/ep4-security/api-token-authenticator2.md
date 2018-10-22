# API Token Authenticator Part 2!

When the request sends us a *valid* API token, our authenticator code is working!
At least all the way to `checkCredentials()`. But before we finish that, I want to
see what happens if a client sends us a *bad* key. So let's see... the last number
in the token is six. Let's add a space: that will be enough to mess things up.

Hit send again. Woh! It redirects us to `/login`? I did *not* see that coming.

Sometimes the *hardest* part of security is figuring out what's happening when something
unexpected occurs. So, let's figure out *exactly* what's going on here.

When authentication fails, this `onAuthenticationFailure()` method is called:

[[[ code('81a4c7f4ab') ]]]

Our job is to return a `Response` that should be sent back to the client. Right now...
we're doing nothing.! So, instead of sending an error back to the user, the request
*continues* like normal to the controller. But, the request is still *anonymous*.
So when it hits our security check in `AccountController`, Symfony activates the
"entry point", which redirects the user to `/login`.

## onAuthenticationError()

But... that's not what we want at all! If an API client sends a bad API token, we need
to tell them! Bad API client! Let's return a new `JsonResponse()` with a `message`
key that describes what went wrong. Earlier, I mentioned that whenever authentication
fails - for any reason - it's because, internally, some sort of `AuthenticationException`
is thrown. That's important because this exception is passed to us as an argument:

[[[ code('7f6b973cf3') ]]]

*And* it has a method - `getMessageKey()` - that holds a message about what went
wrong. Set the status code to 401:

[[[ code('7d52a1c877') ]]]

## Custom Error Messages with CustomUserMessageAuthenticationException

Let's try it again! Send the request. Yes! A 401 Unauthorized response. But, oh.
That `message` isn't right at all!

> Username could not be found?

This is because Symfony creates a different error message based on *where*
authentication fails inside your authenticator. If you fail to return a `User`
from `getUser()`, you get this "Username could not be found" error.

For our login form, we render this *exact* `messageKey` field in the template. But
we *also* pass it through the translator:

[[[ code('a4e6a46571') ]]]

That allowed us to translate that into a better message:

[[[ code('a966bad279') ]]]

We *could* do the same here: inject the translator service into `ApiTokenAuthenticator`
and translate the message key. But... hmmm, the message *still* wouldn't be right - it would
use the "It doesn't look like that email exists!" message from the translation file.

No problem: there is a *second* way to control error messages in an authenticator,
and it's *super* flexible. At *any* point in your authenticator, you can throw a
new `CustomUserMessageAuthenticationException()` that will cause authentication to
fail *and* accepts *any* custom error message you want, like, "Invalid API Token":

[[[ code('40095603c3') ]]]

That's it! This exception will be passed to `onAuthenticationFailure()` and its
`getMessageKey()` method will return that message.

Go back to Postman to try it: send! We got it! So much better!

## Checking Token Expiration

Oh, while we're talking about tokens *failing*, we should *definitely* check to
make sure the token hasn't expired. Inside `ApiToken`, we created this nice
`expiresAt` property:

[[[ code('5a86dce5a2') ]]]

Go down to the bottom of the class and add a new helper function: `isExpired()`
that returns a `bool`. Return `$this->getExpiresAt()` is less than or equal to
`new \DateTime()`:

[[[ code('de4e05bfb0') ]]]

Nice! Back in `ApiTokenAuthenticator`, in `getUser()`, if `$token->isExpired()`,
then `throw new CustomUserMessageAuthenticationException()` with `Token Expired`:

[[[ code('1431938b6f') ]]]

We're killin' it! Oh, but, why are we putting this code *here* and not in `checkCredentials()`?
Answer: no reason! These two methods are called one after the other and you can
*really* put any code inside *either* of these methods. Actually, I chose `getUser()`
just because we have access to the `$token` object there.

Head back to Postman. Let's remove that extra space so our API token is valid once
again. Send! Success! Now, go back to the `ApiToken` class and, temporarily,
`return true` from `isExpired()` so we can see the error:

```
class ApiToken
{
    // ...

    public function isExpired(): bool
    {
        return true;
        return $this->getExpiresAt() <= new \DateTime();
    }
}
```

And... send it again! Got it! Token Expired. Remove that dummy code.

## onAuthenticationSuccess()

At this point... we're basically done! In `checkCredentials()`, there is no password
to check. And so, it's perfectly ok for us to `return true`:

[[[ code('e82e6f0f19') ]]]

Finally, in `onAuthenticationSuccess()`, hmm. What *should* we do when authentication
is successful? With a login form, we redirect the user after success. But with an
API token system we, well, want to do... nothing! Yep! We want to allow the request
to continue so that it can hit the controller and return the JSON response:

[[[ code('acf8db486a') ]]]

## start() & supportsRememberMe()

So what about `start()`? Because we chose `LoginFormAuthenticator` as the
`entry_point`, this will never be called. To prove it, I'll throw an exception
that says:

> Not used: entry_point from other authenticator is used:

[[[ code('9d4ddea277') ]]]

And, *finally*, `supportsRememberMe()`. Return `false`:

[[[ code('dfa5ce6270') ]]]

If you return `true` from this method, it just means that the "remember me" system
is activated and looking for that `_remember_me` checkbox to be checked. Because
that makes absolutely *no* sense for an API, just turn it off.

That's it! Find a stranger to high-five! Cheers your coffee with a co-worker! And
find Postman! Brace yourself... send! Yes! It executes our controller and we are
*definitely* authenticated because we see the info for `spacebar9@example.com`.

People - we now have *two* valid ways to authenticate in our system! The *super*
cool thing is that, inside of our controller, we don't care *which* method is
used! We just say `$this->getUser()`... never caring whether the user was
authenticated via the login form or with an API token.

Next: let's set up a registration form and learn how we can *manually* authenticate
the user after success.
