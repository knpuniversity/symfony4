# Timestampable & Failed Migrations

My *other* favorite Doctrine Extension behavior is timestampable. Go back to the
library's documentation and click to view the Timestampable docs.

Oh, it's so nice: with this behavior, we can add `createdAt` and `updatedAt` fields
that are automatically updated. Believe me, this will save your *butt* sometime
in the future, when something happens on your site you can't *quite* explain.

## Adding the createdAt & updatedAt Fields

Ok, step 1: we need those 2 new fields. We could easily add them by hand, but let's
generate them instead. Run:

```terminal
php bin/console make:entity
```

Update the `Article` entity and add `createdAt`, as a `datetime`, and say "no"
to nullable: this should *always* be populated. Do the same thing for `updatedAt`:
it should *also* always be set: it will match `createdAt` when the entity is first
saved. Hit enter to finish adding fields.

Next, you guys know the drill, run:

```terminal
php bin/console make:migration
```

Awesome! Move over and open that migration. Yep, this looks good: an `ALTER TABLE`
to add `created_at` and `updated_at`. So, go *back* to your terminal, and run
this:

```terminal
php bin/console doctrine:migrations:migrate
```

## When a Migration Fails

And... great! Wait, woh! No! It exploded! Check it out:

> Incorrect datetime value: 0000-00-00

Hmm. The problem is that our database *already* holds articles. So when MySQL tries
to create a new datetime column that is *not* nullable, it has a hard time figuring
out what value to put for those existing rows!

Yep, unfortunately, *sometimes*, migrations fail. And fixing them is a delicate
process. Let's think about this. What we *really* want to do is create those columns,
but allow them to be *null* at first. Then, we can *update*  both fields to today's
date. And, *then* we can use another `ALTER TABLE` query to finally make them not
null.

That's *totally* doable! And we just need to modify the migration by hand. Instead
of `NOT NULL`, use `DEFAULT NULL`. Do the same for `updated_at`.

Below that, call `$this->addSql()` with:

```sql
UPDATE article SET created_at = NOW(), updated_at = NOW()
```

We *still* need another query to change things *back* to not null, but don't do
it yet: we can be lazy. Instead, find your terminal: let's try the migration again.
But, wait! You may or may *not* be able to run the migration immediately. In this
case, the original migration only had *one* statement, and that one statement
failed. This means that *no* part of the migration executed successfully.

But sometimes, a migration may contain *multiple* lines of SQL. And, if the second
or third line fails, then, well, we're in a *really* weird state! In that situation,
if we tried to *rerun* the migration, the first line would execute for the *second*
time, and it would probably fail.

Basically, when a migration fails, it's possible for your migration system to be
in an invalid state. *When* that happens, you should completely drop your database
and start over. You can do that with:

```terminal
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
```

And *then* you can migrate. Anyways, we are *not* in an invalid state: so we can
just re-try the migrations:

```terminal
php bin/console doctrine:migrations:migrate
```

And *this* time, they work! To finally make the fields *not* nullable, we can ask
Doctrine to generate a new migration:

```terminal
php bin/console make:migration
```

Go check it out! Ha! Nice! It simply changes the fields to be NOT NULL. Run it!

```terminal
php bin/console doctrine:migrations:migrate
```

And we are good!

## Activating Timestampable

Ok, let's add timestampable! First, you need to activate it, which again, is described
*way* down on the bundle's docs. Open `config/packages/stof_doctrine_extensions.yaml`,
and add `timestampable: true`.

Second, your entity needs some annotations. For this, go back to the library's
docs. Easy enough: we just need `@Gedmo\Timestampable`.

Back in our project, open `Article` and scroll down to find the new fields. Above
`createdAt`, add `@Timestampable()` with `on="create"`. Copy that, paste above
`updatedAt`, and use `on="update"`.

We should be ready! Find your terminal, and reload the fixtures!

```terminal
php bin/console doctrine:fixtures:load
```

No errors... but to make sure it's working, run:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM article'*
```

Yes! They're set! And each time we update, the `updated_at` will change.

## The TimestampableEntity Trait

I *love* Timestampable. Heck, I put it *everywhere*. And, fortunately, there is
a *shortcut*! Yea, we did *way* more work than we needed to.

Let me show you: completely delete the `createdAt` and `updatedAt` fields that we
so-carefully added. And, remove the getter and setter methods at the bottom too.

But now, *all* the way on top, `use TimestampableEntity`.

Yea! Hold Command or Ctrl and click to see that. *Awesome*: this contains the
*exact* same code that we had before! If you want Timestampable, *just* use this
trait, generate a migration and... done!

Oh, about that, there *could* be some slight column differences between these columns
and the original ones we created. Let's check that. Run:

```terminal
php bin/console make:migration
```

> No database changes were detected

Cool! The fields in the trait are identical to what we had before. So, we can
already test things with:

```terminal
php bin/console doctrine:fixtures:load
```

Thank you `TimestampableEntity`!


Ok guys! I hope you are *loving* Doctrine! We just got a *lot* of functionality
fast. We have magic - like Timestampable & Sluggable - rich data fixtures, and a
rocking migration system.

One thing that we have *not* talked about yet is production config. And... that's
because it's already setup. The Doctrine recipe came with its own `prod` config
file, which makes sure that anything that *can* be cached, *is* cached. This means
you get nice performance, out-of-the-box.

The *big* topic that we have *not* talked about yet is Doctrine relations. And
that'll be the topic of our next tutorial! We'll setup cool relationship, join
tables together, high five, and *really* make a rich database.

All right guys, see you next time.
