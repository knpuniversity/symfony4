# All about the User class

Now matter *how* your users will login, the *first* step to creating an
authentication system is to create a `User` class. And we just did that with the
handy `make:user` command.

Go check out that class: `src/Entity/User.php`:

[[[ code('644d34bb30') ]]]

Two important things. First, because we chose "yes" to storing user info in the
database, the command created an *entity* class with the normal annotations and
`id` property. It *also* added an `email` property, a `roles` property - that
we'll talk about later - and the normal getter and setter methods. Yep, this `User`
class is just a normal, boring entity class.

*Now* look back at the top of the class. Ah, it implements a `UserInterface`:

[[[ code('a54e03d696') ]]]

This is the *second* important thing `make:user` did. Our `User` class can look
*however* we want. The *only* rule is that it must implement this interface... which
is actually pretty simple. It just means that you need a few extra methods. The
first is `getUsername()`... which is a *bad* name... because your users do *not*
need to have a username. This method should just return a visual identifier for the
user. In our case: `email`:

[[[ code('3b2ae27a1a') ]]]

And actually, this method is only used by Symfony to display who is currently
logged in on the web debug toolbar. It's not important.

Next is `getRoles()`:

[[[ code('7a99742e0b') ]]]

This is related to user permissions, and we'll talk about it later.

The last 3 are `getPassword()`, `getSalt()` and `eraseCredentials()`. And *all*
3 of these are *only* needed if your app is responsible for storing and checking
user passwords. Because our app will *not* check user passwords - well, not *yet* -
these can safely be blank:

[[[ code('ebe82af864') ]]]

So, for us: we basically have a normal entity class that also has a `getUsername()`
method and a `getRoles()` method. It's really, pretty boring.

The *other* file that was modified was `config/packages/security.yaml`. Go back to
your terminal and run:

```terminal
git diff
```

to see what changed. Ah, it updated this `providers` key:

[[[ code('47dc66a53b') ]]]

This is called a "user provider". Each `User` class - and you'll almost definitely
only need one `User` class - needs a corresponding "user provider". And actually,
it's not *that* important. I'll tell you what it does later.

But before we get there, forget about security and remember that our `User` class
is a Doctrine entity. Let's add another field to it, generate a migration & add
some dummy users to the database. Then, to authentication!
