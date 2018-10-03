# IS_AUTHENTICATED_ & Protecting All URLs

I mentioned earlier that there are *two* ways to check whether or not the user is 
simply logged in. The first is by checking `ROLE_USER`:

[[[ code('c1b793f4df') ]]]

I like this one because it's simple. It works because of how our `getRoles()`
method is written:

[[[ code('e1679b1a90') ]]]

The *only* reason I'm even going to *mention* the *second* way is because I want
you to know what it is if you see it, *and*, it leads us towards a few other
interesting things.

## `IS_AUTHENTICATED_FULLY`

Let's *play* a little bit in `security.yaml`. Under `access_control` add a new
entry with path `^/account`. Yes, this will be a *totally* redundant access control
because we're already requiring `ROLE_USER` from inside the controller:

[[[ code('4c1b66e541') ]]]

Just pretend that we don't have this controller code for a minute.

On your `access_control`, if you wanted to require the user to be logged in, you
could use `roles: ROLE_USER` *or* `IS_AUTHENTICATED_FULLY`:

[[[ code('6ebda9d296') ]]]

OoooOOOoo.

Well, it's not really that fancy: it's just a special string that *simply* checks
if the user is logged in or not. In our system, it's 100% identical to `ROLE_USER`.

Move over, go back to `/account` and... yep! Access is *still* granted.

## Web Debug Toolbar & Access Control Checks

Oh, and I want to show you something cool! Click the little security icon on the
web debug toolbar. This has some *pretty* sweet stuff in it. In addition to saying
who you're logged in as and your roles, it also has a table down here with some
lower-level info. But what I *really* want to show you is *all* the way at the bottom.
Yes! The access decision log. This records *every* time that we checked whether or
not the user had access to something on this page. The first check is for
`IS_AUTHENTICATED_FULLY` from `access_control`. Granted! Then, two `ROLE_USER`
checks and one `ROLE_ADMIN` check.

One of those `ROLE_USER` checks is from `AccountController`:

[[[ code('ca2f8cd42f') ]]]

And the other comes from `is_granted()` in the template. The `ROLE_ADMIN` check also
lives here:

[[[ code('0306aeb73e') ]]]

So, this is just a nice way to debug all the security checks happening on your page.

## Requiring Login on Every Page

Anyways, we now know `IS_AUTHENTICATED_FULLY` is a way to check if the user is logged
in. Though... because of the way our app is written, checking `ROLE_USER` does
the same thing and... it's shorter to write.

But! This *does* touch on another interesting topic. This is a news site, so most
of the pages will be accessible to anonymous users. We'll require login on just
the pages that need it. Not all sites are like this, however. On *some* sites,
you want to do the opposite: you want to require authentication for *every* page,
or at least, *almost* every page. In those cases, a better strategy is to require
login on *all* pages and then *allow* anonymous access on just a few pages.

We can do this by being clever with `access_control`. Try this: change the
`path` to just `^/`:

[[[ code('4aad70c362') ]]]

Because this is a regular expression, it will match *every* URL and so *every*
page now requires login.

If we refresh, we still have access. But now, log out!

## Allowing the Login Page: `IS_AUTHENTICATED_ANONYMOUSLY`

Whoa! The page is broken! Like, *crazy* broken! `localhost` redirected too many
times!? Yep, our security system is *too* awesome. Because we're now anonymous,
when we try to access any page, we're redirected to `/login`. But guess what?
`/login` requires authentication too! So what does Symfony do? It redirects
us to `/login`!

We made security *so* tight that anonymous users can't even get to the login page!
Here's the fix: add a new `access_control` - *above* the one for all URLs with
`path: ^/login`. You can add a `$` on the end to match only this URL exactly,
not also `/login/foo`. Your call. For `roles`, use a *second* special string:
`IS_AUTHENTICATED_ANONYMOUSLY`:

[[[ code('c905edde42') ]]]

This one is *weird*. Who has `IS_AUTHENTICATED_ANONYMOUSLY`? Everyone! If you're
anonymous, you have it. If you're logged in, you have it too! So, *why* would
we *ever* want to use a role that *everyone* has? Well, go refresh.

Because it fixes our problem! Remember: Symfony goes down each `access_control`
one-by-one. As *soon* as it finds *one* that matches, it uses that *one* and stops.
So when we go to `/login`, *only* the first access control is used and access is
granted. Every *other* page will still require login. Booya!

## `IS_AUTHENTICATED_REMEMBERED`

We've now learned *two* special "strings" that can be used in place of the normal
roles: `IS_AUTHENTICATED_FULLY` and `IS_AUTHENTICATED_ANONYMOUSLY`. But, there
is *one* more. Change "fully" to `IS_AUTHENTICATED_REMEMBERED`:

[[[ code('378f3f5385') ]]]

Go back to your site and log in. Because we *just* logged in, we have all three
special strings: `IS_AUTHENTICATED_FULLY`, `IS_AUTHENTICATED_REMEMBERED` and, of
course, `IS_AUTHENTICATED_ANONYMOUSLY`.

But now, imagine that you're using the "remember me" functionality. You close your
browser, re-open it, and are *still* authenticated, but only thanks to the remember
me cookie. *Now*, you would *still* have `IS_AUTHENTICATED_REMEMBERED`, but you
would *not* have `IS_AUTHENTICATED_FULLY`. Fully means that you have authenticated
during *this* session.

This allows you to do something really neat. If you use the remember me functionality
you should protect all pages that require login with `IS_AUTHENTICATED_REMEMBERED`.
This says that you don't care whether the user just logged in during this session or
if they are logged in via the remember me cookie. *Then* you can protect more sensitive
pages - like the change password page - with `IS_AUTHENTICATED_FULLY`:

[[[ code('68a735c2ee') ]]]

If a user tries to access that page, but is *only* authenticated with the remember
me cookie, Symfony will redirect them to the login page so that they can become "fully"
authenticated. Nice, right?

By the way, I'm showing you all of these examples for the `IS_AUTHENTICATED` strings
inside `access_control`. But, you absolutely can use these in your controller or inside
Twig.

Ok, because our site will be mostly public, I'll comment-out these examples:

[[[ code('8bf68542c6') ]]]

Next, let's learn how to find out *who* is logged in by fetching their `User` object.
