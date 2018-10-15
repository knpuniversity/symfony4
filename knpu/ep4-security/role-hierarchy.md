# Role Hierarchy

So far, our site has two types of users. First, for some pages, like the account page,
we only care that you are logged in - a "normal" user. And second, there are a few
admin pages. Open up `ArticleAdminController` and `CommentAdminController`. Both of
these are protected by `ROLE_ADMIN`:

[[[ code('541300c1b9') ]]]

[[[ code('ec1ef2cad9') ]]]

A lot of sites are just this simple: you have normal users and admin users, who have
access to *all* of the admin sections. But, if you have a more complex setup - like
a bigger company where different groups of people need access to different things,
this isn't good enough. The question is: what's the best way to organize that with
roles?

## Role Naming

Well, there are only two possibilities. First, you could use roles that are named
by the *type* of user that will have them - like `ROLE_EDITOR`, `ROLE_HUMAN_RESOURCES`
or `ROLE_THE_PERSON_THAT_OWNS_THE_COMPANY`... or something like that. But, I don't
*love* this option. It's just not super clear what having `ROLE_EDITOR` will give
me access to.

Instead, I like to use role names that *specifically* describe *what* you're
protecting - like `ROLE_ADMIN_ARTICLE` for `ArticleAdminController`:

[[[ code('072fbf4aef') ]]]

And, for `CommentAdminController`: `ROLE_ADMIN_COMMENT`:

[[[ code('bb6e61091a') ]]]

Oh, and also open `base.html.twig`. There's one other spot here where we use
`ROLE_ADMIN`. There it is: to hide or show the "Create Post" link. *Now* that
should be `ROLE_ADMIN_ARTICLE`:

[[[ code('3e292b8be7') ]]]

## role_hierarchy

I love it! Except... for one problem. Go to `/admin/comment`. Access denied! Well,
I'm not even logged in as an admin user. But even if I *were*, I would still not
have access! Admin users do *not* have these two new roles!

And, yea, we *could* go back to `UserFixture`, add `ROLE_ADMIN_COMMENT` and
`ROLE_ADMIN_ARTICLE` and *then* reload the fixtures. But, this highlights an
annoying problem. *Each* time we add a new admin section to the site and introduce
a new role, we will need to go into the database, find *all* the users who need
access to that new section, and give *them* that new role. That's a bummer!

But... don't worry! Symfony has our backs with a sweet feature called
`role_hierarchy`. Open `config/packages/security.yaml`. Anywhere inside, I'll
do it above firewalls, add `role_hierarchy`. Below, put `ROLE_ADMIN` set to an
array with `ROLE_ADMIN_COMMENT` and `ROLE_ADMIN_ARTICLE`:

[[[ code('eefd63ac50') ]]]

It's *just* that simple. Now, *anybody* that has `ROLE_ADMIN` *also* has these
two roles, automatically. To prove it, go log out so that we can log in as one of
our admin users: `admin2@thespacebar.com`, password `engage`.

Go back to `/admin/comment` and... access granted!

This is even cooler than you might think! It allows us to organize our roles
into different groups of people in our company. For example, `ROLE_EDITOR` could
be given access to all the sections that "editors" need. Then, the *only* role
that you need to *assign* to an editor user is this *one* role: `ROLE_EDITOR`. And
if all editors need access to a new section in the future, just add that new role
to `role_hierarchy`.

We can use this new super-power to try out a *really* cool feature that allows
you to *impersonate* users... and become the international spy you always knew you
would.
