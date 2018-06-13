# The 4 (2?) Possible Relation Types

Remember *way* back when we used the `make:entity` command to generate the relationship
between `Comment` and `Article`? When we did this, the command told us that there
are *four* different types of relationships: ManyToOne, OneToMany, ManyToMany
and OneToOne.

But, that's not really true... and the truth is a lot more interesting. For example,
we quickly learned that ManyToOne and OneToMany are really two different ways to
refer to the *same* relationship! `Comment` has a ManyToOne relationship to `Article`.
But that same database relationship can be described as a OneToMany from `Article`
to `Comment`.

## OneToOne: The Cousin of ManyToOne

This means that there are *truly* only *three* different types of relationships:
ManyToOne, ManyToMany and OneToOne. Um, ok, this is embarrassing. That's not true
either. Yea, A OneToOne relationship is more or less the same as a ManyToOne. OneToOne
is kind of weird. Here's an example: suppose you have a `User` entity and you decide
to create a `Profile` entity that contains *more* data about that one user. In this
example, each `User` has exactly one `Profile` and each `Profile` is linked to exactly
one `User`.

But, in the database, this looks exactly like a ManyToOne relationship! For example,
our ManyToOne relationship causes the `comment` table to have an `article_id`
foreign key column. If you had a OneToOne relationship between some `Profile` and
`User` entities, then the `profile` table would have a `user_id` foreign key to
the `user` table. The *only* difference is that doctrine would make that column
unique to prevent you from accidentally linking multiple profiles to the same
user.

The point is, OneToOne relationships are kind of ManyToOne relationships in disguise.
They also not very common, and I don't really like them.

## The 2 Types of Relationships

So, *really*, if you are trying to figure out *which* relationship type to use in
a situation... well... there are only *two* types: (1) ManyToOne/OneToMany
or (2) ManyToMany.

For ManyToMany, imagine you have a `Tag` entity and you want to be able to add tags
to articles. So, each article will have many tags. And, each Tag may be related
to many articles. *That* is a ManyToMany relationship. And *that* is *exactly*
what we're going to build.

## Building the Tag Entity

Let's create the new `Tag` entity class first. Find your terminal and run:

```terminal
php bin/console make:entity
```

Name it `Tag` and give it two properties: `name`, as a string and `slug` also as
a string, so that we can use the tag in a URL later.

Cool! Before generating the migration, open the new class. No surprises: `name`
and `slug`. At the top, use our favorite `TimestampableEntity` trait. And, just
like we did in `Article`, configure the slug to generate automatically. Copy
the slug annotation and paste that above the `slug` property. Oh, but we need a `use`
statement for the annotation. An easy way to add it is to temporarily type
`@Slug` on the next line and hit tab to auto-complete it. Then, delete it: that
was enough to make sure the `use` statement was added on top. Let's also make
the `slug` column unique.

Great! The entity is ready. Go back to your terminal and make that migration!

```terminal
php bin/console make:migration
```

Whoops! My bad! Maybe you saw my mistake. Change the Slug annotation from `title`
to `name`. Generate the migration again:

```terminal-silent
php bin/console make:migration
```

Got it! Open that class to make sure it looks right. Yep: `CREATE TABLE tag`.
Go run it:

```terminal
php bin/console doctrine:migrations:migrate
```

Now that the entity & database are setup, we need some dummy data! Run:

```terminal
php bin/console make:fixtures
```

Call it `TagFixture`. Then, like always, open that class so we can tweak it. First,
extend `BaseFixture`, rename `load` to `loadData` and make it protected. We also
don't need this `use` statement anymore. Call our trusty `$this->createMany()`
to create 10 tags. For the name, use `$tag->setName()` with `$this->faker->realText()`
and 20, to get about that many characters.

We *could* use `$this->faker->word` to get a random word, but that word would be
in Latin. The `realText()` method will give us a *few* words, actually, but they
will sound, at least "kinda" real.

And, that's all we need! To make sure it works, run:

```terminal
php bin/console doctrine:fixtures:load
```

We are ready! Article entity, check! Tag entity, check, check! It's time to create
the ManyToMany relationship.
