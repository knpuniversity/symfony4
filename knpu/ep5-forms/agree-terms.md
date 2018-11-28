# Agree to Terms Database Field

If you compare our old registration form and our new one, we're missing one annoying,
piece: the "Agree to terms" checkbox, which, if you're like me, is just one of my
*favorite* things in the world - right behind a fine wine or day at the beach.

Legally speaking, this field *is* important. So let's code it up correctly.

## Adding the "Agreed Terms" Persisted Date Field

A few years ago, we might have added this as a simple unmapped checkbox field with
some validation to make sure it was checked. But *these* days, to be compliant,
we need to save the *date* the terms were agreed to.

Let's start by adding a new property for that! Find your terminal and run:

```terminal
php bin/console make:entity
```

Update the `User` class and add a new field called `agreedTermsAt`. This will be
a `datetime` field and it *cannot* be `nullable` in the database: we need this to
always be set. Hit enter to finish.

[[[ code('92629b2cad') ]]]

## Adding the Checkbox Field

Before we worry about the migration, let's think about the form. What we want is
very simple: a checkbox. Call it, how about, `agreeTerms`. Notice: this creates
a familiar problem: the form field is called `agreeTerms` but the *property* on
`User` is `agreedTermsAt`. We *are* going to need more setup to get this working.

[[[ code('9344a1e21f') ]]]

But first, Google for "Symfony form types" and click the "Form Type Reference"
page. Let's see if we can find a checkbox field - ah: `CheckboxType`. Interesting:
it says that this field type should be used for a field that has a boolean value.
If the box is checked, the form system will set the value to `true`. If the box
is unchecked, the value will be set to `false`. That makes sense! That's the
*whole* point of a checkbox!

Back on the form, set the type to `CheckboxType::class`.

[[[ code('d1151d98de') ]]]

Nice start! Before I forget, find your terminal and make the migration:

```terminal-silent
php bin/console make:migration
```

As usual, go to the migrations directory, open that file and... yep! It adds
the one field. Run it with:

```terminal
php bin/console doctrine:migrations:migrate
```

Oh no! Things are *not* happy. We have *existing* users in the database!
When we suddenly create a new field that is `NOT NULL`, MySQL has a hard time
figuring out what datetime value to use for the existing user rows!

## Migrating Existing User Data

Our migration needs to be smarter. First: when a migration fails, Doctrine does
*not* record it as having been executed. That makes sense. And because there is
only *one* statement in this migration, we know that it completely failed, and we
can try it again as soon as we fix it. In other words, the `agreed_terms_at` column
was *not* added.

If a migration has *multiple* statements, it's possible that the first few queries
*were* successful, and *then* one failed. When that happens, I usually delete the
migration file entirely, fully drop the database, then re-migrate to get back to
a "clean" migration state. But also, some database engines like PostgreSQL are
smart enough to rollback the first changes, if a later change fails. In other words,
those database engines avoid the problem of partially-executed-migrations.

Anyways, to fix the migration, change the `NOT NULL` part to `DEFAULT NULL`
temporarily. Then add another statement:
`$this->addSql('UPDATE user SET agreed_terms_at = NOW()');`.

[[[ code('722282134d') ]]]

Great! First, let's run *just* this migration

```terminal
php bin/console doctrine:migrations:migrate
``` 

This time... it works! To finish the change, make one more migration: 

```terminal-silent
php bin/console make:migration
```

[[[ code('ccf0898da2') ]]]

Go check it out! Perfect! This gives us the *last* piece we need: changing the
column back to `NOT NULL`, which will *work* because each existing user now has a
real value for this field. Oh, but, for *legal* purposes, on a real site - it may
not be proper to automatically set the `agreed_terms_at` for existing users. Yep,
you've gotta check with a lawyer on that kind of stuff.

But from a database migration standpoint, this should fix everything! Run the last
migration:

```terminal-silent
php bin/console doctrine:migrations:migrate
``` 

Excellent! Next: we have a `CheckboxType` field on the form... which is good at
setting true/false values. And, we have an `agreedTermsAt` DateTime field on the
`User` class. Somehow, those need to work together!
