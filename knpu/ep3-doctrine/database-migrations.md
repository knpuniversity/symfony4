# Database Migrations

The `Article` entity is ready, and Doctrine already knows to save its data to an
`article` table in the database. But... that table doesn't exist yet! So... how
can we create it?

## Generating a Migration

Ah, this is one of Doctrine's *superpowers*. Go back to your terminal. At the bottom
of the `make:entity` command, it has a suggestion: run the `make:migration` command.

I *love* this! Try it:

```terminal
php bin/console make:migration
```

The output says that it created a new `src/Migrations/Version*` class that we should
review. Ok, find your code, open the `Migrations` directory and, there it is! One
migration file:

[[[ code('d58ce77cda') ]]]

Inside, cool! It holds the MySQL code that we need!

> CREATE TABLE article...

This is *amazing*. No, seriously - it's *way* more awesome than you might think.
The `make:migration` command actually *looked* at our database, looked at all of 
our entity classes - which is just one entity right now - and generated the SQL
needed to *update* the database to match our entities. I'll show you an even better
example in a few minutes.

## Executing the Migration

This looks good to me, so close it and then go back to your terminal. To execute
the migration, run:

```terminal
php bin/console doctrine:migrations:migrate
```

This command was *also* suggested above. Answer yes to run the migrations and...
done! 

But now, run that same command again:

```terminal-silent
php bin/console doctrine:migrations:migrate
```

## How Migrations Work

It does nothing! Interesting. Run:

```terminal
php bin/console doctrine:migrations:status
```

Ok, this tells us a bit more about *how* the migration system works. Inside the
database, the migration system automatically creates a new table called
`migration_versions`. Then, the *first* time we ran `doctrine:migrations:migrate`,
it executed the migration, and inserted a new *row* in that table with that migration's
version number, which is the date in the class name. When we ran `doctrine:migrations:migrate`
a *second* time, it opened the migration class, then looked up that version in the
`migration_versions` table. Because it was already there, it knew that this migration
had already been executed and did *not* try to run it again.

This is brilliant! Whenever we need to make a database change, we follow this simple
two-step process: (1) Generate the migration with `make:migration` and (2) run
that migration with `doctrine:migrations:migrate`. We *will* commit the migrations
to our git repository. Then, on deploy, just make sure to run `doctrine:migrations:migrate`.
The production database will have its *own* `migration_versions` table, so this will
automatically run *all* migrations that have not been run yet on production. It's
perfect.

## Migration a Second Change

To see how nice this is, let's make one more change. Open the `Article` class.
See the `slug` field?

[[[ code('c72145f630') ]]]

This will eventually be used to identify the article in the URL. And so, this *must*
be *unique* across every article in the table.

To *guarantee* that this is unique in the database, add `unique=true`:

[[[ code('f4d39f1fa5') ]]]

This option does only *one* thing: it tells Doctrine that it should create a unique
*index* in the database for this column.

But of course, the database didn't just magically update to have this index. We
need a migration. No problem! Find your terminal and do step 1: run:

```terminal
php bin/console make:migration
```

Ha! I even misspelled the command: Symfony figured out what I meant. This created
a *second* migration class: the first creates the table and the second... awesome!
It creates the unique index:

[[[ code('222c8239bb') ]]]

This is the Doctrine magic I mentioned earlier: the `make:migration` command looked
at the entity, looked at the database, determined the *difference* between the two,
then generated the SQL necessary to *update* the database.

Now, for step (2), run:

```terminal
php bin/console doctrine:migrations:migrate
```

It sees the *two* migration classes, notices that the *first* has already been
executed, and only runs the *second*.

Ok! Our database is setup, our `Article` entity is ready, and we already have a
killer migration system. So let's talk about how to *save* articles to the table.
