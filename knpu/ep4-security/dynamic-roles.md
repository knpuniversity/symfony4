# Dynamic Roles

I want to create something new: a *new* user account page. Find your terminal and
run:

```terminal
php bin/console make:controller
```

Create a new `AccountController`. Open that up. Perfect! A new `/account` page,
which we can see instantly if we go to that URL.

Change the route name to `app_account` to be consistent with our other code. And,
I'm not going to pass any variables to the template for now. Open that:
`templates/account/index.html.twig`. Let's customize this just a bit: Manage Account
and an h1: Manage your Account. That's pretty boring... but it should be enough for
us to get into trouble!

## Check if the User Is Logged In

Ok: I *only* want this page to be accessible by users who are logged in. Log out
and then go back to `/account`. Obviously, right now, *anybody* can access this.
Hmm: we *do* already know how to require the user to have a specific *role* to access
something - like in `CommentAdminController` where we require `ROLE_ADMIN`. But...
how can we make sure that the user is simply... logged in?

There are actually *two* different ways. I'll tell you about *one* of those ways
later. But right now, I want to tell you about the easier of the two ways: just
check for `ROLE_USER`.

Above the `AccountController` class - so that it applies to any future methods -
add `@IsGranted("ROLE_USER")`.

So... *why* is this a valid way to check that the user is simply logged in? Because,
remember! In `User`, our `getRoles()` method is written so that *every* user always
has at least this role. *If* you are logged in, you *definitely* have `ROLE_USER`.

Refresh the page now: it bumps us to the login page. Log in with password `engage`
and... nice! We're sent back over to `/account`. Smooth.

## Adding Admin Users

At this point, *even* though we're requiring `ROLE_ADMIN` in `CommentAdminController`,
we... well... don't actually have any admin users! Yep, *nobody* can access this
page because *nobody* has `ROLE_ADMIN`!

To make this page... um... actually usable, open `src/DataFixtures/UserFixture.php`.
In addition to these "normal" users, let's *also* create some admin users. Copy
the whole `createMany()` block and paste below. Give this set of users a different
"group name" - `admin_users`. Remember: this key is *not* important right now. But
we can use it later in *other* fixture classes if we wanted to "fetch" these admin
users and relate them to different objects. We'll see that later.

Let's create three admin users. For the email, how about `admin%d@thespacebar.com`.
The first name is fine and keep the password so that I don't get completely confused.
But *now* add `$user->setRoles()` with `ROLE_ADMIN`.

Notice that I do not *also* need to add `ROLE_USER`: the `getRoles()` method will
make sure that's returned *even* if it's not stored in the database.

Let's reload those fixtures!

```terminal
php bin/console doctrine:fixtures:load
```

When that finishes, move over and go back to `/login`. Log in as one of the new
users: `admin2@thespacebar.com`, password `engage`. Then, try `/admin/comment`.
Access granted! Woohoo! And we, of course, *also* have access to `/account` because
our user has both `ROLE_ADMIN` *and* `ROLE_USER`.

## Checking for Roles in Twig

Oh, and now that we know how to check if the user is logged in, let's fix our user
drop-down: we should *not* show the login link once we're logged in.

In PhpStorm, open `templates/base.html.twig` and scroll down a bit. Earlier, when
we added the login link, we commented out our big user drop-down. *Now*, we can
be a bit smarter. Copy that entire section: we *will* show it when the user is
logged in.

Oh, but how can we check if the user has a role from inside Twig? With: `is_granted`:
if `is_granted('ROLE_USER')`, `else` - I'll indent my logout link - and `endif`.
Inside the if, paste the drop-down code.

Ah! Let's go see it! Refresh! Our user drop-down is back! Oh, except all of these
links go... nowhere. We can fix that!

For profile, that route is `app_account`: `path('app_account')`. For logout, that's
`path('app_logout')`. And, for "Create Post", we haven't built that yet. But, there
*is* a controller called `ArticleAdminController` and we have at least *started*
this. Give this route a `name="admin_article_new"`. We'll link here, even though
it's not done.

Oh, but this link is only for admin users. So, surround this with
`is_granted("ROLE_ADMIN")`.

Nice! Let's make sure we didn't mess up - refresh! Woohoo! Because we *are* logged
in as an admin user, we see the user drop-down *and* the Create Post link.

Next: we need to talk about a few unique roles that start with `IS_AUTHENTICATED`
and how these can be used in `access_control` to easily require login for *every*
page on your site.
