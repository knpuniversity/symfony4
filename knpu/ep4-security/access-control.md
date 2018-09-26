# access_control Authorization & Roles

*Everything* that we've done so far has been about authentication: how your user
logs in. But now, our space-traveling users *can* log in! We're loading users
from the database, checking their password and even protecting ourselves
from the Borg Collective... with CSRF tokens.

So let's start to look at the second part of security: **authorization**. Authorization
is all about deciding whether or not a user should have access to something. This
is where, for example, you can require a user to log in before they see some page -
or restrict some sections to admin users only.

There are two main ways to handle authorization: first, `access_control` and second,
denying access in your controller. We'll see both, but I want to talk about `access_control`
first, it's pretty cool.

## access_control in security.yaml

At the bottom of your `security.yaml` file, you'll find a key called, well,
`access_control`:

[[[ code('f07daa3686') ]]]

Uncomment the first access control:

[[[ code('dd4aac0ae7') ]]]

The `path` is a regular expression. So, this access control says that any URL that
starts with `/admin` should require a role called `ROLE_ADMIN`. We'll talk about
roles in a minute.

Go to your terminal and run

```terminal
php bin/console debug:router
```

Ah, yes, we *do* already have a few URLs that start with `/admin`, like
`/admin/comment`. Well... let's see what happens when we try to go there!

Access denied! Cool! We get kicked out!

## Roles!

Let's talk about how *roles* work in Symfony: it's simple and it's beautiful.
Down on the web debug toolbar, click on the user icon. Cool: we're logged in as
`spacebar1@example.com` and we have one role: `ROLE_USER`. Here's the idea: when
a user logs in, you give them whatever "roles" you want - like `ROLE_USER`. Then,
you run around your code and make different URLs require different roles. Because
our user does *not* have `ROLE_ADMIN`, we are denied access.

But... why does our user have `ROLE_USER`? I don't remember doing *anything* with
roles during the login code. Open the `User` class. When we ran the `make:user` command,
one of the methods that it generated was `getRoles()`:

[[[ code('466263d32f') ]]]

Look at it carefully: it reads a `roles` property, which is an array that's stored
in the database:

[[[ code('7b71bafabb') ]]]

Right now, this property is empty for *every* user in the database: we have *not*
set this to any value in the fixtures.

But, inside `getRoles()`, there's a little extra logic that guarantees that *every*
user *at least* has this one role: `ROLE_USER`:

[[[ code('5a564c7518') ]]]

This is nice because we *now* know that, *if* you are logged in, you definitely have
this *one* role. Also... you need to make sure that `getRoles()` always returns at least
*one* role... otherwise weird stuff happens: the user becomes an undead zombie that
is "sort of" logged in.

To prove that this roles system works like we expect, change `ROLE_ADMIN` to
`ROLE_USER` in the access control:

```yaml
security:
    # ...
    access_control:
        - { path: ^/admin, roles: ROLE_USER }
```

Then, click *back* to the admin page and... access granted!

Change that back to `ROLE_ADMIN`.

## Only One `access_control` Matches per Page

As you can see in the examples down here, you're allowed to have as *many* `access_control`
lines as you want: each has their own regular expression path. But, there is one
*super* important thing to understand. Access controls work like *routes*: Symfony
checks them one-by-one from top to bottom. And as soon as it finds *one* access
control that matches the URL, it uses that and stops. Yep, a maximum of *one* access
control is used on each page load.

Actually... this fact allows you to do some cool things if you want *most* of your
pages to require login. We'll talk about that later.

Now that we can deny access... something interesting happens if you try to access
a protected page as an *anonymous* user. Let's see that next.
