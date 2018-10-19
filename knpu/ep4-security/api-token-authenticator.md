# API Token Authenticator

Time to put some code in our `ApiTokenAuthenticator`! Woo! I'm going to use Postman
to help make test API requests. The only thing *better* than using Postman is creating
functional tests in your own app. But that's the topic for another tutorial.

Let's make a GET request to `http://localhost:8000/api/account`. Next, how should
we send the API token? As a query parameter? As a header? Well, you can do whatever
you want - but using a header is pretty standard. Great! And um... what should we
call that header? Postman has a nice system to help configure common authentication
types. Choose something called "Bearer token". I'll show you what that means in a
minute.

But first, move over to your terminal: we need to find a valid API key! Run:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM api_token'
```

## Authorization: Bearer

Copy one of these long strings, move back to Postman and paste! To see what this
Auth stuff does, hit "Preview Request".

> Request headers were successfully updated.

Cool! Click back to "Headers". Ahh! This "Auth" section is just a shortcut to add
a request header called `Authorization`. Hey! Go away tooltip! Anyways, the `Authorization`
header is set to the word "Bearer", a space, and then our token.

Honestly, you can name this header *whatever* you want - like
`SEND-ME-YOUR-TOKEN`, `WHATS-THE-MAGIC-WORD` or `I-LIKE-DINOSAURS`. The name
`Authorization` is just a standard, yea, and I guess... it *does* sound a bit more
professional than my other ideas. There's also nothing significant about that
"Bearer" part. That's *another* standard that's commonly used when your token is
what's known as a "Bearer token": a fancy term that means whoever "bears" this
token - so, whoever "possesses" this token - can use it to authenticate, without
needing to provide *any* other types of authentication, like a master key or a password.
Most API tokens, also known as "access tokens" are "bearer" tokens. And this is a
standard way of attaching them to a request.

## supports()

Back to work! Open `ApiTokenAuthenticator`. Ok: this is our *second* authenticator,
so it's time to use our existing knowledge to kick some security butt! For `supports()`,
our authenticator should only become active if the request has an `Authorization`
header whose value starts with the word "Bearer". No problem: return
`$request->headers->has('Authorization')` to make sure that header is set and also check
that 0 is the position inside `$request->headers->get('Authorization')` where the
string `Bearer` and a space appears:

[[[ code('41c5b5564a') ]]]

I know: weird-looking code. But it does exactly what we need! If the `Authorization`
Bearer header isn't there, `supports()` will return false and no other methods will
be called.

## getCredentials()

Next: `getCredentials()`. Our job is to read the token string and return it.
Start with `$authorizationHeader = $request->headers->get('Authorization')`:

[[[ code('cd066f81a7') ]]]

But, instead of returning that *whole* value, skip the `Bearer` part. So, return
a sub-string of `$authorizationHeader` where we start at the 7th character:

[[[ code('a12ee88922') ]]]

Ok. Deep breath: let's see if this is working so far. In `getUser()`, `dump($credentials)`
and die:

[[[ code('c121cf39f3') ]]]

This *should* be the API token *string*. Oh, and notice that this is different
than `LoginFormAuthenticator`: we returned an *array* from `getCredentials()` there:

[[[ code('905aa7a8f6') ]]]

But that's the beauty of the authenticators: you can return *whatever* you want
from `getCredentials()`. The only thing we need is the token string... so, we just
return that.

Try it! Find Postman and... send! Nice! I mean, it looks terrible, but go to Preview.
Yes! *There* is our API token string.

## getUser()

Next up: `getUser()`. First, we need to query for the `ApiToken` entity. At the
top of this class, make an `__construct` function and give it an
`ApiTokenRepository $apiTokenRepo` argument. I'll hit `Alt`+`Enter` to initialize that:

[[[ code('e76ee07fab') ]]]

Then, back in `getUser()`, get that token: `$token = $this->apiTokenRepo->findOneBy()`
to query where the `token` property is set to the `$credentials` string:

[[[ code('3a6fa191ac') ]]]

If we do *not* find an `ApiToken`, return null. That will make authentication fail.
If we *do* find one, we need to return the `User`, not the token. So, return
`$token->getUser()`:

[[[ code('6342fc16f6') ]]]

Finally, *if* you return a `User` object from `getUser()`, Symfony calls
`checkCredentials()`. Let's `dd('checking credentials')` to see if we *continue*
to be lucky:

[[[ code('4fcc63f44d') ]]]

Move back over to Postman, Send and... yes! Checking credentials.

We're *almost* done! But before we handle success, I want to see what happens
with a *bad* API key. And learn how we can send back the *perfect* error response.
