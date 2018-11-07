# Agree Terms Database Field

If you compare our old registration form and our new one, we're missing one annoying,
small piece: the "Agree to terms" checkbox, which I know we all *love* almost as
much as a fine wine or a day on the beach.

Legally speaking, this field *is* important. So let's code this up correctly.

## Adding the "Agreed Terms" Persisted Date Field

A few years ago, we might have added this simply as an unmapped checkbox field that
had some validation to make sure it was checked. But *these* days, to be compliant,
we need to save the *date* the terms were agreed to.

So let's start by adding that new property! Find your terminal and run:

```terminal
php bin/console make:entity
```

Update the `User` class and add a new field called `agreedTermsAt`. This will be
a `datetime` field and it *cannot* be `nullable` in the database: we need this to
always be set. Hit enter to finish.

## Adding the Checkbox Field

Before we worry about the migration, let's think about the form. What we want is
very simple: a checkbox. Call it, how about, `agreeTerms`. Notice: this creates
a familiar problem: the form field is called `agreeTerms` but the *property* on
`User` is `agreedTermsAt.`. We *are* going to need more setup to get this working.

But first, Google for a "Symfony form types" and click the "Form Type Reference"
page. Let's see if we can find a checkbox field - ah: `CheckboxType`. Interesting:
it says that this field type should be used for a field that has a boolean value.
If the box is checked, the form system will set the value to `true`. If the box
is unchecked, the value will be set to `false`. That makes sense.! That's the
*whole* point of a checkbox!

Back on the form, set the type to `CheckboxType::class`.

Nice start! Before I forget, find your terminal and make the migration:

```terminal-silent
php bin/console make:migration
```

Now, go to the migrations directory, open that file and... yep! It just adds the
one field. Run it with:

```terminal
php bin/console doctrine:migrations:migrate
```

But, oh no! Things are *not* happy. We have *existing* users in the database! So
when we suddenly create a new field that is `NOT NULL`, MySQL has a hard time
figuring out what value to use for the existing user rows.

## Migrating Existing User Data

Our migration needs to be smarter. First: when a migration fails, Doctrine does
*not* record it as having been executed. That makes sense. And because there is
only *one* statement in this migration, we know that it completely failed and we
can try it again as soon as we fix it. In other words, the `agreed_terms_at` column
was *not* added.

If a migration has *multiple* statements, it's possible that the first few queries
were successful, and *then* one failed. When that happens, I usually delete the
migration file entirely, fully drop the database, then re-migrate to get back to
a "clean" migration state. But also, some database engines like PostgreSQL are
smart enough to rollback the first changes, if a later change fails.

Anyways, to fix the migration, change the `NOT NULL` part to `DEFAULT NULL`
temporarily. Then add another statement:
`$this->addSql('UPDATE user SET agreed_terms_at = NOW()');`.

Great. First, let's run just this migration

```terminal
php bin/console doctrine:migrations:migrate
``` 

This time, it works! To finish the change, make one more migration: 

```terminal-silent
php bin/console make:migration
```

Go check it out! Perfect! This gives us the *last* piece we need: changing the
column back to `NOT NULL`, which will *work* because existing users now have real
values for this field. Now, for *legal* purposes, on a real site - this might not
work because you need those users to actually agree to the terms. But from a
database migration standpoint, it's perfect. Run it:

```terminal-silent
php bin/console doctrine:migrations:migrate
``` 

Excellent! Next: we have a `CheckboxType` field on the form... which is good at
setting true/false values. And, we have an `agreedTermsAt` DateTime field on the
`User` class. Somehow, these need to work together!