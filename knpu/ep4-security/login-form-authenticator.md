# Login Form Authenticator

Now that we've added our authenticator under the `authenticators` key:

[[[ code('7e6514c73e') ]]]

Symfony calls its `supports()` method at the beginning of *every* request,
which is why we see this little die statement:

[[[ code('272fcb019d') ]]]

These authenticator classes are really cool because *each* method controls just
*one* small part of the authentication process.

## The `supports()` Method

The first method - `supports()` - is called on every request. Our job is simple:
to return `true` if this request contains authentication info that this authenticator
knows how to process. And if not, to return `false`. 

In this case, when we submit the login form, it POSTs to `/login`. So, our authenticator
should *only* try to authenticate the user in that exact situation. Return
`$request->attributes->get('_route') === 'app_login'`:

[[[ code('897a803ef0') ]]]

Let me... explain this. If you look in `SecurityController`, the *name* of our login
route is `app_login`:

[[[ code('53f1a8443f') ]]]

And, though you don't need to do it very often, if you want to find out the *name*
of the currently-matched route, you can do that by reading this special `_route`
key from the request attributes. In other words, this is checking to see if the
URL is `/login`. We also only want our authenticator to try to login the user if
this is a POST request. So, add `&& $request->isMethod('POST')`:

[[[ code('416321cbd8') ]]]

Here's how this works: if we return `false` from `supports()`, nothing else happens.
Symfony doesn't call *any* other methods on our authenticator, and the request
continues on like normal to our controller, like nothing happened. It's not an
authentication *failure* - it's just that nothing happens at all.

If we return `true` from `supports()`, well, that's when the fun starts. If we return
`true`, Symfony will immediately call `getCredentials()`:

[[[ code('27aafe8c12') ]]]

To see if things are working, let's just `dump($request->request->all())`,
then `die()`:

[[[ code('7cdd678987') ]]]

I know, that looks funny. *Unrelated* to security, if you want to read POST
data off of the request, you use the `$request->request` property.

Anyways, let's try it! Go back to your browser and hit enter on the URL so that it
makes a `GET` request to `/login`. Hello login page! Our `supports()` method just
returned `false`. And so, the request continued *anonymously*, like normal.

Log in with one of our dummy users: `spacebar1@example.com`. The password doesn't
matter. And... enter! Yes! *This* time, because this is a POST request to `/login`,
`supports()` returns `true`! So, Symfony calls `getCredentials()` and our dump fires!
As expected, we can see the `email` and `password` POST parameters, because the login
form uses these names:

[[[ code('87534a62fc') ]]]

## The Brand-New `dd()` Function

Oh, and I want to show you a *quick* new Easter egg in Symfony 4.1, *unrelated* to
security. Instead of `dump()` and `die`, use `dd()` and then remove the `die`:

[[[ code('9b4b41542b') ]]]

Refresh! Same result. This is just a nice, silly shortcut: `dd()` is `dump()`
and `die`. We'll use it... because... why not?

## The `getCredentials()` Method

Back to work! Our job in `getCredentials()` is simple: to read our authentication
credentials off of the request and return them. In this case, we'll return the
`email` and `password`. But, if this were an API token authenticator, we would
return that token. We'll see that later.

Return an array with an `email` key set to `$request->request->get('email')`
and `password` set to `$request->request->get('password')`:

[[[ code('2a0b4246d7') ]]]

I'm just inventing these `email` and `password` keys for the new array: we can
really return *whatever* we want from this method. Because, after we return from
`getCredentials()`, Symfony will immediately call `getUser()` and pass this array
*back* to us as the first `$credentials` argument:

[[[ code('8a837eec4d') ]]]

Let's see that in action: `dd($credentials)`:

[[[ code('fe26cecc83') ]]]

Move back to your browser and, refresh! Coincidentally, it dumps the *exact*
same thing as before. But, this time, it's coming from line 30 - our line in `getUser()`.

## The `getUser()` Method

Let's keep going! Our job in `getUser()` is to use these `$credentials` to return
a `User` object, or null if the user isn't found. Because we're storing our users
in the database, we need to *query* for the user via their email. And to do that,
we need the `UserRepository` that was generated with our entity.

At the top of the class, add `public function __construct()` with a
`UserRepository $userRepository` argument:

[[[ code('07c2686622') ]]]

I'll hit `Alt`+`Enter` and select "Initialize Fields" to add that property
and set it:

[[[ code('e6c779a2ad') ]]]

Back down in `getUser()`, just return `$this->userRepository->findOneBy()` to
query by email, set to `$credentials['email']`:

[[[ code('657262312f') ]]]

This will return our `User` object, or `null`. The *cool* thing is that if this returns
`null`, the whole authentication process will stop, and the user will see an error.
But if we return a `User` object, then Symfony immediately calls `checkCredentials()`,
and passes it the same `$credentials` and the `User` object *we* just returned:

[[[ code('11f8f71e62') ]]]

Inside, `dd($user)` so we can see if things are working:

[[[ code('2fb574641e') ]]]

Refresh and... got it! That's *our* `User` object!

## The `checkCredentials()` Method

Ok, final step: `checkCredentials()`. This is your opportunity to check to see if
the user's password is correct, or any other last, security checks. Right now...
well... we don't have a password, so, let's return `true`:

[[[ code('b78d0bd922') ]]]

And actually, in *many* systems, simply returning `true` is perfect! For example,
if you have an API token system, there's no password.

If you *did* return `false`, authentication would fail and the user would see
an "Invalid Credentials" message. We'll see that soon.

But, when you return *true*... authentication is successful! Woo! To figure out
what to *do*, now that the user is authenticated, Symfony calls `onAuthenticationSuccess()`:

[[[ code('4f55e6c1f1') ]]]

Put a `dd()` here that says "Success":

[[[ code('8a548bd0d6') ]]]

Move over and... refresh the POST! Yes! We hit it! At this point, we have *fully*
filled in *all* the authentication logic. We used `supports()` to tell Symfony
whether or not our authenticator should be used in this request, fetched credentials
off of the request, used those to find the user, and returned `true` in
`checkCredentials()` because we don't have a password.

Next, let's fill in these *last* two methods and *finally* see - for *real* - that
our user is logged in. We'll also learn a bit more about what happens when
authentication fails and how the error message is rendered.
