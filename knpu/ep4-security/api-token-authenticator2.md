# API Token Authenticator Part 2!

When the request sends us a *valid* API token, our authenticator code is working
all the way to `checkCredentials()`. But before we finish this, I want to see what
happens if a client sends us a *bad* key. So let's see... the last number here is
six. Let's add a space: that will be enough to mess things up.

Hit send again. Woh! It redirects us to `/login`? I did not see that coming.

The *hardest* part of security is figuring out what's happening when something
unexpected occurs. So, let's figure out *exactly* what's going on.

When authentication fails, this `onAuthenticationFailure()` method is called. Our
job here is to return a `Response` that should be sent back to the client. Right
now, we're doing nothing. So, instead of sending an error back to the user, the
request *continues* like normal to the controller. But of course, the request is
still *anonymous*. When it hits our security checks in `AccountController`, Symfony
activates the "entry point", which lives in `LoginFormAuthenticator`. This redirects
the user to `/login`.

## onAuthenticationError()

But... that's not what we want at all! If the client sends a bad API token, we need
to tell them! Let's return a new `JsonResponse()` with a `message` key that describes
what went wrong. Earlier, I mentioned that whenever authentication - for any reason -
it's because, internally, some sort of an `AuthenticationException` is thrown. That's
important because this exception is passed to us as an argument. *And* it has a
method - `getMessageKey()` - that holds a message about what went wrong. Set the
status code to 401.

## Custom Error Messages with CustomUserMessageAuthenticationException

Let's try it again! Send the request. Yes! A 401 Unauthorized response. But, oh.
That `message` isn't right at all!

> Username could not be found?

Remember: this is because Symfony creates a different error message based on
*where* authentication fails inside your authenticator. If you failed to return
a `User`, you get this "Username could not be found" error.

For our login form, we render this *exact* field on the template. But we *also*
pass it through the translator. That allowed us to translate that into a better
message. We *could* do the same here: inject the translator service into
`ApiTokenAuthenticator` and translate the message key. But... hmmm, the message
*still* wouldn't be right.

No problem: there is a *second* way to control error messages in an authenticator,
and it's *super* flexible. At *any* point in your authenticator, you can throw a
new `CustomUserMessageAuthenticationException()` that will cause authentication to
fail *and* accepts *any* custom error message you want, like, "Invalid API Token".

That's it! This exception will be passed to `onAuthenticationFailure()` and its
`getMessageKey()` method will return this message.

Go back to Postman to try it: send! We got it! So much better!

## Checking Token Expiration

Oh, while we're talking about tokens *failing*, we should *definitely* check to
make sure the token hasn't expired. Inside `ApiToken`, we created a nice
`expiresAt` property. Go down to the bottom of the class and add a new helper
function: `isExpired()` that returns a `bool`. Return `$this->getExpiresAt()`
is less than or equal to `new \DateTime()`.

Nice! Back in `ApiTokenAuthenticator`, in `getUser()`, if `$token->isExpired()`,
then `throw new CustomUserMessageAuthenticationException()` with `Token Expired`.

Oh, but, why are we putting this code *here* and not in `checkCredentials()`?
Answer: no reason! These two methods are called one after the other and you can
*really* put any code inside *either* of these methods. Actually, I chose `getUser()`
just because we have access to the `$token` object there.

Head back to Postman. Let's remove that extra space so our API token is correct
again. Send! Things are successful. Now, go back to the `ApiToken` class and,
temporarily, `return true` from `isExpired()` so we can see that error.

Ok, send it again! Nice! Token Expired. Remove that dummy code.

## onAuthenticationSuccess()

At this point... we're basically done! In `checkCredentials()`, there is no password
to check. And so, it's perfectly ok for us to `return true`.

Finally, in `onAuthenticationSuccess()`, hmm. What *should* we do when authentication
is successful? With a login form, we want to redirect the user after success. But
with an API token system we, well, want to do... nothing! Yep! We want to allow
the request to continue so that it can hit the controller and return the JSON
response.

## start() & supportsRememberMe()

So what about `start()`? Because we chose `LoginFormAuthenticator` as the
`entry_point`, this will never be called. To prove it, I'll throw an exception
that says:

> Not used: entry_point from other authenticator is used.

And, *finally*, `supportsRememberMe()`. Return `false`. If you return `true` from
this method, it just means that the "remember me" system is activated and looking
for that `_remember_me` checkbox to be checked. That makes *no* sense in this
situation, so we can save some memory.

That's it! Find a stranger to high-five! Find Postman and... brace yourself...
send! Yes! It executes our controller and we are *definitely* authenticated because
we see the info for `spacebar9@example.com`.

People - we now have *two* valid ways to authenticate in our system! The *super*
cool thing is that, inside of our controller, we don't care *which* method is
used! We just say `$this->getUser()`... never caring whether or not the user was
authenticated via the login form or with an API token.

