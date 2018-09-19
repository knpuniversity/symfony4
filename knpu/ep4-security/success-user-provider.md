# Redirecting on Success & the User Provider

*If* our authenticator is able to return a `User` from `getUser()` *and* we return
true from `checkCredentials()`:

[[[ code('46c9064bcc') ]]]

Then, congrats! Our user is logged in! The *last* question Symfony asks us is: now what?
Now that the user is authenticated, what do you want to do?

For a form login system, the answer is: redirect to another page. For an API
token system, the answer is... um... nothing! Just allow the request to continue
like normal.

This is why, once authentication is successful, Symfony calls `onAuthenticationSuccess()`:

[[[ code('c5f6452b95') ]]]

We can either return a `Response` object here - which will be immediately sent back
to the user - *or* nothing... in which case, the request would continue to the
controller.

## Redirecting on Success

So, hmm, *we* want to *redirect* the user to another page. So... how do we
redirect in Symfony? If you're in a controller, there's a `redirectToRoute()`
shortcut method. Hold `Command` or `Ctrl` and click into that. I want to see what
this does.

Ok, it leverages two *other* methods: `redirect()` and `generateUrl()`. Look at
`redirect()`. Oh.... So, to redirect in Symfony, you return a `RedirectResponse`
object, which is a sub-class of the normal `Response`. It just sets the status
code to 301 or 302 and adds a `Location` header that points to where the user should
go. That makes sense: a redirect is just a special type of response!

The *other* method, `generateUrl()`, is a shortcut to use the "router" to convert
a route *name* into its URL. Go back to the controller and clear out our dummy
code.

Back in `LoginFormAuthenticator`, return a `new RedirectResponse()`. Hmm, let's
just send the user to the homepage. But, *of course*, we don't ever hardcode
URLs in Symfony. Instead, we need to *generate* a URL to the route named
`app_homepage`:

[[[ code('8ad4732bde') ]]]

We *know* how to generate URLs in Twig - the `path()` function. But, how can we
do it in PHP? The answer is... with Symfony's *router* service. To find out how
to get it, run:

```terminal
php bin/console debug:autowiring
```

Look for something related to routing... there it is! Actually, there are a few
different router-related interfaces... but they're all different ways to get the
*same* service. I usually use `RouterInterface`.

Back on top, add a *second* constructor argument: `RouterInterface $router`:

[[[ code('6df9288b08') ]]]

I'll hit `Alt`+`Enter` and select "Initialize Fields" to create that property and
set it:

[[[ code('00d41500b9') ]]]

Then, back down below, use `$this->router->generate()` to make a URL to `app_homepage`:

[[[ code('8082705cfd') ]]]

Ok! We still have one empty method:

[[[ code('2874c96a78') ]]]

But, forget that! We're ready! Go back to your browser, and hit enter to show
the login page again. Let's walk through the *entire* process. Use the same email,
*any* password and... enter! It worked! How do I know? Check out the web debug toolbar!
We are logged in as `spacebar1@example.com`!

## Authentication & the Session: User Provider

This is even *cooler* than it looks. Think about it: we made a POST request
to `/login` and became authenticated thanks to our authenticator. Then, we were
redirected to the homepage... where our authenticator did nothing, because its
`supports()` method returned `false`.

The *only* reason we're *still* logged in - even though our authenticator did nothing
on this request - is that user authentication info is stored to the session. At
the beginning of every request, that info is *loaded* from the session and we're
logged in. Cool!

Look back at your `security.yaml` file. Remember this user provider thing that was
setup for us?

[[[ code('c2c7286bb9') ]]]

This is a class that *helps* with the process of loading the user info from the session.

Honestly, it's a little bit confusing, but super important. Here's the deal: when
you refresh the page, the `User` object is loaded from the session. But, we need
to make sure that the object isn't out of date with the database. Think about it.
Imagine we login at work. Then, we login at home and update our first name in the
database. The next day, when we go back to work, we reload the page. Well... if we
did *nothing* else, the User object we reloaded from the session for *that* browser
would have our *old* first name. That would probably cause some weird issues.

So, that's the job of the user provider. When we refresh, the user provider takes
the `User` object from the session and uses its `id` to query for a *fresh* `User`
object. It all happens invisibly, which is *great*. But it *is* an important,
background detail.

Next, I want to see what happens when we *fail* authentication. What does the user
see? How are errors displayed? And how can we control them?
