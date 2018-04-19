# Installing Doctrine

Friends! Welcome back to the *third* episode in our starting in Symfony 4 series!
We've done some really cool stuff already, but it's time to explore deeper in an
epic search for intelligent life... and also.. and database! Yea - what good is
our cool interstellar space news site... without being able to insert and query
for data?

But... actually... Symfony does *not* have a database layer. Nope, for this challenge,
we're going to rely one of Symfony's BFF's: an external library called
Doctrine. Doctrine has *great* integration with Symfony and is *crazy* powerful.
It *also* has a reputation for being a little bit hard to learn. But, a lot has
improved over the last few years.

## Code with Me!

If you *also* want to be best-friends-forever with Doctrine, you should *totally*
code along with me. Download the course code from this page. When you unzip it,
you'll find a `start/` directory that has the same code that you see here. Open
the `README.md` file for details on how to get the project setup... and of course,
a space poem.

The last step will be to open a terminal, move into the project and run:

```terminal
php bin/console server:run
```

to start the built in web server. Then, float over to your browser, and open
`http://localhost:8000` to discover... The Space Bar! Our inter-planetary,
and extraterrestrial news site that spreads light on dark matters everywhere.

In the first two episodes, we already added some pretty cool stuff! But, these
articles are still just hard-coded. Time to change that.

## Installing Doctrine

Because Doctrine is an external library, before we do *anything* else, we need to
install it! Thanks to Symfony flex, this is super easy. Open a new terminal tab
and just run:

```terminal
composer require doctrine
```

This will download a "pack" that contains a few libraries, including doctrine itself
and also a migrations library to help manage database changes on production. More
on that soon.

And... done! Hey! That's a nice message. Because we're going to be talking to a
database, obviously, we will need to configure our database details somewhere. The
message tells us that - no surprise - this is done in the `.env` file.

## Configuring the Database Connection

Move over to your code and open the `.env` file. Nice! The DoctrineBundle recipe
added a new `DATABASE_URL` environment variable:

[[[ code('3d6b07ba40') ]]]

Let's set this up: I use a `root` user with no password locally. Call the database
`the_spacebar`:

[[[ code('b119a2078e') ]]]

Of course, this sets a `DATABASE_URL` environment variable. And *it* is used in
a new `config/packages/doctrine.yaml` file that was installed by the recipe. If
you scroll down a bit... you can see the environment variable being used:

[[[ code('222aae44eb') ]]]

There are actually a lot of options in here, but you probably won't need to change
any of them. These give you nice defaults, like using UTF8 tables:

[[[ code('083c412860') ]]]

Or, consistently using underscores for table and column names:

[[[ code('b2dcb5e1fa') ]]]

If you want to use something other than MySQL, you can easily change that. Oh, and
you should set your `server_version` to the server version of MySQL that you're using
on production:

[[[ code('7f348c163d') ]]]

This helps Doctrine with a few subtle, version-specific changes.

## Creating the Database

And... yea! With one `composer require` command and one line of config, we're setup!
Doctrine can even create the database for you. Go back to your terminal and run

```terminal
php bin/console doctrine:database:create
```

Say hello to your new database! Well, it's not *that* interesting: it's completely
empty.

So let's add a table, by creating an entity class.
