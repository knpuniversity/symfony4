# Author ManyToOne Relation to User

Check out the homepage: every `Article` has an author. But, open the
`Article` entity. Oh: the `author` property is just a *string*!

[[[ code('cecd9bc7f4') ]]]

When we originally created this field, we hadn't learned how to handle database
relationships yet.

But now that we are *way* more awesome than "past us", let's replace this `author`
string property with a proper relation to the `User` entity. So every `Article`
will be "authored" by a specific `User`.

Wait... why are we talking about database relationship in the security tutorial?
Am I wandering off-topic again? Well, only a *little*. Setting up database
relations is *always* good practice. But, I have a *real*, dubious, security-related
goal: this setup will lead us to some *really* interesting access control problems -
like denying access to edit an `Article` unless the logged in user is that
Article's *author*.

Let's smash this relationship stuff so we can get to that goodness! First,
remove the `author` property entirely. Find the getter and setter methods
and remove those too. Now, find your terminal and run:

```terminal
php bin/console make:migration
```

If our app were already deployed, we might need to be a little bit more careful
so that we don't *lose* all this original author data. But, for us, no worries:
that author data was garbage! Find the `Migrations/` directory, open up the new
migration file and yep! `ALTER TABLE Article DROP author`:

[[[ code('98dba46a39') ]]]

## Adding the Relation

Now, lets *re-add* author as a relation:

```terminal
php bin/console make:entity
```

Update the `Article` entity and add a new `author` property. This will be
a "relation" to the `User` entity. For the type, it's another `ManyToOne` relation:
each `Article` has one `User` and each `User` can have many articles. The
`author` property will be *required*, so make it *not* nullable. We'll say
"yes" to mapping the other side of the relationship and I'll say "no" to
`orphanRemoval`, though, that's not important. Cool! Hit enter to finish:

[[[ code('1e6f0fa2f4') ]]]

Now run:

```terminal
php bin/console make:migration
```

Like always, let's go check out the new migration:

[[[ code('84f7e3d33c') ]]]

Woh! I made a mistake! It *is* adding `author_id` but it is *also* dropping `author`.
But that column should already be gone by now! My bad! After generating the *first*
migration, I forgot to run it! This diff contains *too* many changes. Delete it.
Then, execute the first migration:

```terminal
php bin/console doctrine:migrations:migrate
```

Bye bye original `author` column. *Now* run:

```terminal
php bin/console make:migration
```

Go check it out:

[[[ code('ec81ef67bc') ]]]

*Much* better: it adds the `author_id` column and foreign key constraint.
Close that and, once again, run:

```terminal
php bin/console doctrine:migrations:migrate
```

## Failed Migration!

Woh! It explodes! Bad luck! This is one of those *tricky* migrations. We made
the new column required... but that field will be *empty* for all the existing
rows in the table. That's not a problem on its own... but it *does* cause a problem
when the migration tries to add the foreign key! The fix depends on your situation.
If our app were already deployed to production, we would need to follow a 3-step
process. First, make the property `nullable=true` at first and generate that migration.
Second, run a script or query that can somehow set the `article_id` for all the
existing articles. And finally, change the property to `nullable=false` and
generate one last migration.

But because our app has *not* been deployed yet... we can cheat. First, drop
*all* of the tables in the database with:

```terminal
php bin/console doctrine:schema:drop --full-database --force
```

Then, re-run all the migrations to make sure they're working:

```terminal
php bin/console doctrine:migrations:migrate
```

Awesome! Because the `article` table is empty, no errors.

## Adding Article Author Fixtures

Now that the database is ready, open `ArticleFixtures`. Ok: this simple
`setAuthor()` call will *not* work anymore:

[[[ code('78394fa4c0') ]]]

Nope, we need to relate this to one of the users from `UserFixture`. Remember we have
two groups: these `main_users` and these `admin_users`:

[[[ code('0109b2aa03') ]]]

Let's allow normal users to be the author of an `Article`. In other words, use
`$this->getRandomReference('main_users')` to get a *random* `User` object from
that group:

[[[ code('25fcacf621') ]]]

At the top of the class, I can remove this old static property.

Try it! Move over and run:

```terminal
php bin/console doctrine:fixtures:load
```

It works! But... only by chance. `UserFixture` was executed before
`ArticleFixtures`... and that's important! It would *not* work the other way around.
We just got lucky. To enforce this ordering, at the bottom of `ArticleFixtures`,
in `getDependencies()`, add `UserFixture::class`:

[[[ code('d42b7de214') ]]]

Now `UserFixture` will *definitely* run before `ArticleFixtures`.

If you try the fixtures again:

```terminal-silent
php bin/console doctrine:fixtures:load
```

Same result. But now, it's guaranteed!

Next - let's finish our refactoring and create a new "Article Edit" page!
