# Target Path: Redirecting an Anonymous User

After changing the `access_control` back to `ROLE_ADMIN`:

[[[ code('8476d77a2f') ]]]

If we try to access `/admin/comment` again, we see that same "Access Denied"
page: 403 forbidden.

## Customizing the Error Page

Like with *all* the big, beautiful error pages, these are only shown to us, the developers.
On production, by default, your users will see a boring, generic error page that
*truly* looks like it was designed by a developer.

But, you can - and *should* - customize this. We won't go through it now, but if
you Google for "Symfony error pages", you can find out how. The cool thing is that
you can have a different error page per *status* code. So, a custom 404 not found
page and a *different* custom 403 "Access Denied" page - with, ya know, like a mean
looking alien or something to tell you to *stop* trying to hack the site.

## Redirecting Anonymous Users: Entry Point

Anyways, I have a question for you. First, log out. Now that we are anonymous:
what do you think will happen if we try to go to `/admin/comment`? Will we see that
same Access Denied page? After all, we *are* anonymous... so we definitely do *not*
have `ROLE_ADMIN`.

Well... let's find out! No! We are redirected to the login page! That's... awesome!
If you think about it, that's the *exact* behavior we want: if we're not logged
in and we try to access a page that requires me to be logged in, we should *totally*
be sent to the login form so that we *can* login.

The logic behind this actually comes from our authenticator. Or, really, from the
parent `AbstractFormLoginAuthenticator`. It has a method - called `start()` - that
decides what to do when an anonymous user tries to access something. It's called
an entry point, and we'll learn more about this later when we talk about API authentication.

## Redirecting Back on Success

But for now, great! Our system already behaves like we want. But now... check this
out. Log back in with `spacebar1@example.com`, password `engage`. When I hit
enter, where do you think we'll be redirected to? The homepage? `/admin/comment`?
Let's find out.

We're sent to the homepage! Perfect, right? No, not perfect! I originally tried to
go to `/admin/comment`. So, after logging in, to have a great user experience, we
should be redirected back *there*.

The reason that we're sent to the homepage is because of *our* code in
`LoginFormAuthenticator`. `onAuthenticationSuccess()` *always* sends the user to the
homepage, no matter what:

[[[ code('62dbccf4e1') ]]]

Hmm: how could we update this method to send the user back to the *previous* page instead?

Symfony can help with this. Find your browser, log out, and then go back to
`/admin/comment`. *Whenever* you try to access a URL as an anonymous user, *before*
Symfony redirects to the login page, it saves this URL - `/admin/comment` - into
the session on a special key. So, if we can *read* that value from the session
inside `onAuthenticationSuccess()`, we can redirect the user back there!

To do this, at the top of your authenticator, use a *trait* `TargetPathTrait`:

[[[ code('2f4a29ab62') ]]]

Then, down in `onAuthenticationSuccess()`, add if `$targetPath = $this->getTargetPath()`.
*This* method comes from our handy trait! It needs the session - `$request->getSession()` -
and the "provider key", which is actually an argument to this method:

[[[ code('0bdf5b4a84') ]]]

The provider key is just the *name* of your firewall... but that's not too important here.

Oh, and, yea, the if statement might look funny to you: I'm assigning the `$targetPath`
variable and *then* checking to see if it's empty or not. If it's *not* empty, if
there *is* something stored in the session, return new `RedirectResponse($targetPath)`:

[[[ code('4b5b8a0b55') ]]]

That's it! If there is *no* target path in the session - which can happen if the
user went to the login page directly - fallback to the homepage:

[[[ code('9d348405f0') ]]]

Let's try it! Log back in... with password `engage`. Yea! Got it! I know, it feels
weird to celebrate when you see an access denied page. But we *expected* that part.
The important thing is that we *were* redirected back to the page we originally
tried to access. That's *excellent* UX.

Next - as nice as access controls are, we need more *granular* control.
Let's learn how to control user access from inside the controller.
