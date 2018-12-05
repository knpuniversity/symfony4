# Customizing the User Entity

The *really* neat thing about Symfony's security system is that it doesn't care
at *all* about what your `User` class looks like. As long as it implements `UserInterface`,
so, as long as it has these methods, you can do *anything* you want with it. Heck,
it doesn't even need to be an entity!

## Adding more Fields to User

For example, we already have an `email` field, but I also want to be able to store
the first name for each user. Cool: we can just add that field! Find your terminal
and run:

```terminal
php bin/console make:entity
```

Update the `User `class and add `firstName` as a `string`, length 255 - or shorter
if you want - and not nullable. Done!

Check out the `User` class! Yep, there's the new `firstName` property and... at
the bottom, the getter and setter methods:

[[[ code('c491f416f7') ]]]

Awesome!

## Setting Doctrine's server_version

I think we're ready to make the migration. But! A word of warning. Check out
the `roles` field on top:

[[[ code('0ba06151e3') ]]]

It's an array and its Doctrine type is `json`. This is *really* cool. Newer
databases - like PostgreSQL and MySQL 5.7 - have a native "JSON" column type
that allows you to store an *array* of data.

But, if you're using MySQL 5.6 or lower, this column type does *not* exist. And
actually, that's not a problem! In that case, Doctrine is smart enough to use a
normal text field, `json_encode()` your array when saving, and `json_decode()` it
automatically when we query. So, no matter *what* database you use, you *can* use
this `json` Doctrine column type.

But, here's the catch. Open `config/packages/doctrine.yaml`. One of the keys here
is `server_version`, which is set to 5.7 by default:

[[[ code('c077a177e8') ]]]

This tells Doctrine that when it interacts with the database, it should *expect*
that our database has all the features supported by MySQL 5.7, *including* that
native JSON column type. If your
computer, or more importantly, if your *production* database is using MySQL 5.6,
then you'll get a *huge* error when Doctrine tries to make queries using the native
MySQL `JSON` column type.

If you're in this situation, just set this back to 5.6:

[[[ code('ceda20a4d0') ]]]

Doctrine will then create a normal text column for the JSON field.

## Generating the Migration

Ok, *now* run:

```terminal
php bin/console make:migration
```

Perfect! Go check that file out in `src/Migrations`:

[[[ code('53931e7d13') ]]]

And... nice! `CREATE TABLE user`. Look at the `roles` field: a `LONGTEXT` column.
If you kept your `server_version` at 5.7, this would be a `json` column.

Let's run this:

```terminal
php bin/console doctrine:migrations:migrate
```

## Adding Fixtures

One last step: we need to add some dummy users into the database. Start with:

```terminal
php bin/console make:fixtures
```

Call it `UserFixture`. Go check that out: `src/DataFixtures/UserFixture.php`:

[[[ code('db6f3588c2') ]]]

If you watched our Doctrine tutorial, you might remember that we created a special
`BaseFixture` with some sweet shortcut methods. Before I started recording *this*
tutorial, based on some feedback from *you* nice people, I made a few improvements
to that class. Go team!

[[[ code('3cc4c0fcb4') ]]]

The way you use this class is still the same: extend `BaseFixture` and update
the `load()` method to be `protected function loadData()`. I'll remove the old `use`
statement.

[[[ code('e6fbb7afc9') ]]]

Inside, call `$this->createMany()`. The arguments to this method changed a bit since
the last tutorial:

[[[ code('821f7bd0a9') ]]]

Pass this 10 to create 10 users. Then, pass a "group name" - `main_users`. Right now,
this key is meaningless. But later, we'll use it in a different fixture class to relate
other objects to these users. Finally, pass a callback with an `$i` argument:

[[[ code('c93f4d38fa') ]]]

This will be called 10 times and our job inside is simple: create a `User`,
put some data on it and return!

Do it! `$user = new User()`:

[[[ code('7ec5e57811') ]]]

Then `$user->setEmail()` with `sprintf()` `spacebar%d@example.com`. For the `%d` wildcard,
pass `$i`:

[[[ code('02cdf8d9f0') ]]]

Which will be one, two, three, four, five, six, seven, eight, nine, ten for the 10 calls.

The only other field is first name. To set this, we an use Faker, which we already
setup inside `BaseFixture`: `$this->faker->firstName`:

[[[ code('0ffeb46131') ]]]

Finally, at the bottom, return `$user`:

[[[ code('008ba95d75') ]]]

And... we're done! This step had *nothing* to do with security: this is just boring
Doctrine & PHP code inside a fancy `createMany()` method to make life easier.

Load 'em up:

```terminal
php bin/console doctrine:fixtures:load
```

Let's see what these look like:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM user'
```

Nice! Our `User` class is done! *Now*, it's time to add a login form and a login
form *authenticator*: the *first* way that we'll allow our users to login.
