# The Login Form

There are *two* steps to building a login form: the visual part - the HTML form
itself - *and* the logic when you *submit* that form: finding the user, checking
the password, and logging in. The interesting part is... if you think about it,
the *first* part - the HTML form - has absolutely *nothing* to do with security.
It's just... well... a boring, normal HTML form!

Let's get that built first. By the way, there are plans to add a `make` command
to generate a login form and the security logic automatically, so that *we* only
need to fill in a few details. That doesn't exist yet, so.. we'll do it manually.
But, that's a bit better for learning anyways.

## Creating the Login Controller & Template

To build the controller, let's at *least* use one shortcut. At your terminal,
run:

```terminal
php bin/console make:controller
```

to create a new class called `SecurityController`. Move over and open that:

[[[ code('3ccda4ac33') ]]]

Ok: update the URL to `/login`, change the route name to `app_login` and the method
to `login()`:

[[[ code('6900d7f826') ]]]

We don't need to pass any variables yet, and we'll call the template `login.html.twig`:

[[[ code('cf0843fad1') ]]]

Next, down in `templates/security`, rename `index.html.twig` to `login.html.twig`.
Let's try it! Move over, go to `/login` and... whoops!

> Variable `controller_name` does not exist.

Duh! I removed the variables that we *were* passing into the template:

[[[ code('3afa61735d') ]]]

Empty all of the existing code from the template. Then, change the title to `Login!`
and, for now, just add an `h1` with "Login to the SpaceBar!":

[[[ code('3ad70a876e') ]]]

## Filling in the Security Logic & Login Form

Try it again: perfect! Well, not *perfect* - it looks *terrible*... and there's no
login form yet. To fix *that* part, Google for "Symfony login form" to find a page
on the Symfony docs that talks all about this. We're coming here so that we can
steal some code!

Scroll down a bit until you see a `login()` method that has some logic in it. Copy
the body, move back to our controller, and paste!

[[[ code('8cdcde1570') ]]]

This needs an `AuthenticationUtils` class as an argument. Add it:
`AuthenticationUtils $authenticationUtils`:

[[[ code('e564f09eb9') ]]]

Then, these two new variables are passed into Twig. Copy them, and also paste it:

[[[ code('a5bd233ccb') ]]]

In a few minutes, we're going to talk about *where* these two variables are set.
They both deal with authentication.

But first, go back to the docs and find the login form. Copy this, move over and
paste it into our body:

[[[ code('ff6575a07b') ]]]

Notice: there is *nothing* special about this form: it has a username field,
a password field and a submit button. And, we're going to customize it, so don't
look too closely yet.

Move back to your browser to check things out. Bah!

> Unable to generate a URL for the named route "login"

This comes from `login.html.twig`. Of course! The template we copied is pointing
to a route called `login`, but *our* route is called `app_login`:

[[[ code('99f73dabc3') ]]]

Actually, just remove the `action=` entirely:

[[[ code('c8a11899b9') ]]]

If a form doesn't have an `action` attribute, it will submit right back to the *same*
URL - `/login` - which is what I want anyways.

Refresh again. Perfect! Well, it still looks *awful*. Oof. To fix that, I'm going
to replace the HTML form with some markup that looks nice in Bootstrap 4 - you
can copy this from the code block on this page:

[[[ code('8e3181519d') ]]]

## Including the login.css File

Before we look at this new code, try it! Refresh! Still ugly! Dang! Oh yea, that's
because we need to include a new CSS file for this markup.

If you downloaded the course code, you should have a `tutorial/` directory with
two CSS files inside. Copy `login.css`, find your `public/` directory and paste
the file into `public/css`:

[[[ code('b7d0be2c5b') ]]]

So far in this series, we are *not* using Webpack Encore, which is an *awesome*
tool for professionally combining and loading CSS and JS files. Instead, we're just
putting CSS files into the `public/` directory and pointing to them directly.
If you want to learn more about Encore, go check out our [Webpack Encore tutorial][encore_tutorial].

Anyways, we need to add a `link` tag for this new CSS file... but I *only* want to
include it on *this* page, *not* on *every* page - we just *don't* need the CSS on
every page. Look at `base.html.twig`:

[[[ code('ee8507a452') ]]]

We're including three CSS files in the base layout. Ah, and they *all* live inside
a block called `stylesheets`.

We basically want to add a *fourth* `link` tag right *below* these... but *only*
on the login page. To do that, in `login.html.twig`, add `block stylesheets` and
`endblock`:

[[[ code('9454fd3246') ]]]

This will *override* that block completely... which is actually *not* exactly what
we want. Nope, we want to *add* to that block. To do that print `parent()`:

[[[ code('edac60dc21') ]]]

This will print the content of the *parent* block - the 3 link tags - and then we
can add the new link tag below: `link`, with `href=` and `login.css`. PhpStorm helps
fill in the `asset()` function:

[[[ code('3bb1fb0f93') ]]]

*Now* it should look good. Try it. Boom! Oh, but we don't need that `h1` tag anymore.

## The Fields of the Login Form

So even though this looks much better, it's still just a very boring HTML form.
It has an email field and a password field... though, we won't add the
password-checking logic until later. It also has a "remember me" checkbox that
we'll learn how to activate.

The point is: you can make your login form look *however* you want. The only special
part is this `error` variable, which, when we're done, will be the authentication
error if the user just entered a bad email or password:

[[[ code('1abf926f0d') ]]]

I'll plan ahead and add a Bootstrap class for this:

[[[ code('553515e333') ]]]

## Adding a Link to the Login Page

Ok. Login form is done! But... we probably need a *link* to this page. In the upper
right corner, we have a cute user dropdown... which is *totally* hardcoded with
fake data. Go back to `base.html.twig` and scroll down to find this. There it
is! For now, let's comment-out that drop-down:

[[[ code('0d2b001fe6') ]]]

We'll re-add it later when we have *real* data. Then, copy a link from above,
paste ithere and change it to Login with a link to `app_login`:

[[[ code('a5b35fdba0') ]]]

Try it - refresh! We got it! HTML login form, check! We are now ready to fill
in the logic of what happens when we *submit* the form. We'll do that in something
called an "authenticator".


[encore_tutorial]: https://knpuniversity.com/screencast/webpack-encore
