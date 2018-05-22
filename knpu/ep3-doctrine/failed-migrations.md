# When Migrations Fail

My *other* favorite Doctrine Extension behavior is timestampable. Go back to the
library's documentation and click to view the Timestampable docs.

Oh, it's so nice: with this behavior, we can add `$createdAt` and `$updatedAt` fields
to our entity, and they will be automatically set. Believe me, this will save your
*butt* sometime in the future when something happens on your site you can't *quite*
explain. A mystery!

## Adding the createdAt & updatedAt Fields

Ok, step 1: we need those 2 new fields. We could easily add them by hand, but let's
generate them instead. Run:

```terminal
php bin/console make:entity
```

Update the `Article` entity and add `createdAt`, as a `datetime`, and say "no"
to nullable: this should *always* be populated. Do the same thing for `updatedAt`:
it should *also* always be set: it will match `createdAt` when the entity is first
saved. Hit enter to finish adding fields:

[[[ code('3976a4d142') ]]]

Next, you guys know the drill, run:

```terminal
php bin/console make:migration
```

Awesome! Move over and open that file. Yep, this looks good: an `ALTER TABLE`
to add `created_at` and `updated_at`:

[[[ code('cc913394b9') ]]]

Go *back* to your terminal, and run it:

```terminal
php bin/console doctrine:migrations:migrate
```

## When a Migration Fails

And... great! Wait, woh! No! It exploded! Check it out:

> Incorrect datetime value: 0000-00-00

Hmm. The problem is that our database *already* has articles. So when MySQL tries
to create a new datetime column that is *not* nullable, it has a hard time figuring
out what value to put for those existing rows!

Yep, unfortunately, *sometimes*, migrations fail. And fixing them is a delicate
process. Let's think about this. What we *really* want to do is create those columns,
but *allow* them to be null... at first. Then, we can *update* both fields to today's
date. And, *then* we can use another `ALTER TABLE` query to finally make them not
null.

That's *totally* doable! And we just need to modify the migration by hand. Instead
of `NOT NULL`, use `DEFAULT NULL`. Do the same for `updated_at`:

[[[ code('29659bee22') ]]]

Below that, call `$this->addSql()` with:

```sql
UPDATE article SET created_at = NOW(), updated_at = NOW()
```

[[[ code('715c086261') ]]]

We *still* need another query to change things *back* to not null, but don't do
it yet: we can be lazy. Instead, find your terminal: let's try the migration again.
But, wait! You may or may *not* be able to re-run the migration immediately. In this
case, the original migration had only *one* query, and that one query failed. This
means that *no* part of the migration executed successfully.

But sometimes, a migration may contain *multiple* lines of SQL. And, if the second
or third line fails, then, well, we're in a *really* weird state! In that situation,
if we tried to *rerun* the migration, the first line would execute for the *second*
time, and it would probably fail.

Basically, when a migration fails, it's possible that your migration system is now
in an invalid state. *When* that happens, you should completely drop your database
and start over. You can do that with:

```terminal
php bin/console doctrine:database:drop --force
```

And then:

```
php bin/console doctrine:database:create
```

And *then* you can migrate. Anyways, we are *not* in an invalid state: so we can
just re-try the migration:

```terminal
php bin/console doctrine:migrations:migrate
```

And *this* time, it works! To finally make the fields *not* nullable, we can ask
Doctrine to generate a new migration:

```terminal
php bin/console make:migration
```

Go check it out!

[[[ code('c955fd3e64') ]]]

Ha! Nice! It simply changes the fields to be NOT NULL. Run it!

```terminal
php bin/console doctrine:migrations:migrate
```

And we are good! Now, back to Timestampable!
