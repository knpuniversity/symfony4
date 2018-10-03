# API Token Authenticator

Time to fill in our `ApiTokenAuthenticator` logic! Woo! I'm going to use Postman
to help make test API requests. The only thing *better* than using Postman is creating
functional tests in your own app. But that's beyond the scope of this tutorial.

Let's make a GET request to `http://localhost:8000/api/account`. Next, hmm, we need
to send the API token string on a request header. What should we call that header?
Postman has a nice system to help us configure common authentication types. Choose
something called "Bearer token". I'll show you what that means in a minute.

But first, find your terminal: we need to find a valid API key! Run:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM api_token'
```

## Authorization: Bearer

Copy one of these long strings, move back to Postman and paste! To see what this
Auth stuff does, hit "Preview Request".

> Request headers were successfully updated.

Cool! Click back to "Headers". Ahh! This "auth" section is just a shortcut to add
a request header called `Authorization` - um... go away tooltip! Anyways, the `Authorization`
header is set to the word "Bearer", a space, and then our token.

Honestly, we could choose to name this header *whatever* we want - like
`SEND-ME-YOUR-TOKEN` or `WHATS-THE-MAGIC-WORD`. The `Authorization` header is just
a standard and, yea, it sounds more professional than my other ideas. There's
also nothing significant about that "Bearer" part. That's *another* standard
that's commonly used when your token is what's known as a "Bearer token", which
is a fancy term that  basically means that whoever "bears" this token - which means
whoever "has" this token, can use it to authenticate, without needing to provide
*any* other types of authentication, like a master key or a password. It short, most
API tokens, also known as "access tokens" are "bearer" tokens. And this is a standard
way of attaching them to a request.

## supports()

Back to work! Open `ApiTokenAuthenticator`. Ok: this is our *second* authenticator,
so it's time to use our existing knowledge to kick some security butt! For `supports()`,
our authenticator should only become active if the request has an `Authorization`
header whose value starts with the word "Bearer". No problem: return
`$this->headers->has('Authorization')` to make sure the header is set and also check
that 0 is the position inside `$request->headers->get('Authorization')` where the
string `Bearer` and a space appears.

I know - it weird-looking code, but it does exactly what we need! If the `Authorization`
Bearer header isn't there, `supports()` will return false and no other methods will
be called.

## getCredentials()

Next: `getCredentials()`. Our job is to read the token string and return it.
Start with `$authorizationHeader = $request->headers->get('Authorization')`. But,
instead of returning that *whole* value, skip the `Bearer` part. So, return a sub-string
of `$authorizationHeader` where we start at the 7th character.

Ok. Deep breath: let's see if this is working so far. In `getUser()`, `dump($credentials)`
and die. This *should* be the API token *string*. Oh, and notice that this is different
than `LoginFormAuthenticator`: we returned an *array* from `getCredentials()` there.
But that's the beauty of the authenticators: you can return *whatever* you want
from `getCredentials()`. The only thing we need is the token string... so, we just
return that.

Try it! Find Postman and... send! Nice! I mean, it looks terrible, but go to Preview.
Yes! *There* is our API token string.

## getUser()

Next up: `getUser()`. First, we need to query for the `ApiToken` entity. At the
top of this class, make an `__construct` function and give it an
`ApiTokenRepository $apiTokenRepo` argument. I'll hit Alt+Enter to initialize that.

Then, back in `getUser()`, query for that: `$token = $thi->apiTokenRepo`
and use `findOneBy()` to find where `token` is set to `$credentials`, which is a
the string.

If we do *not* find an `ApiToken`, return null. That will make authentication fail.
If we *do* find one, we need to return the `User`, not the token. So, return
`$token->getUser()`.

Finally, *if* you return a `User` object from `getUser()`, Symfony calls
`checkCredentials()`. Let's `dd('checking credentials')` to see if we *continue*
to be lucky.

Move back over to Postman, Send and... yes! Checking credentials.

We're *almost* done! But before we handle success, I want to see what happens
with a *bad* API key. And how we can send back the *perfect* error response.
