# Adding the ManyToOne Relation

Hmm. We want each Article to have many Comments... and we want each Comment to
*belong* to one Article. Forget about Doctrine for a minute: let's think about how
this should look in the database. Because each `Comment` should *belong* to one
article, this means that the `comment` table needs an `article_id` column.

So far, in order to add a new column to a table, we add a new property to the
corresponding entity. And, at first, adding a relationship column is no different:
we need a new property on `Comment`.

## Generating the Relationship

And, just like before, when you want to add a new field to your entity, the *easiest*
way is to use the generator. Run:

```terminal
php bin/console make:entity
```

Type `Comment` so we can add the new field to it. But then, wait! This is a *very*
important moment: it asks us for the new property's name. If you think that this
should be something like `articleId`... that makes sense. But, surprise! It's wrong!

Instead, use `article`. I'll explain *why* soon. For the field type, we can use
a "fake" option here called: `relation`: that will start a special wizard that
will guide us through the relation setup process.

The first question is:

> What class should this entity be related to?

Easy: `Article`. Now, it explains the *four* different types of relationships that
exist in Doctrine: ManyToOne, OneToMany, ManyToMany and OneToOne. If you're not
sure which relationship you need, you can read through the descriptions to find
the one that fits best.

Check out the `ManyToOne` description:

> Each comment relates to one Article

That sound perfect! And then:

> Each Article can have many Comment objects

Brilliant! *This* is the relationship we need. In fact, it's the "king" of
relationships: you'll probably create more ManyToOne relationships than any other.

Answer with: ManyToOne.

Now, it asks us if the `article` property on `Comment` is allowed to be null.
Basically, it's asking us if it should be legal for a Comment to be saved to the
database that is *not* related to an `Article`, so, with an `article_id` set to
`null`. A Comment *must* have an article, so let's say no.

## Generating the Other (Inverse) Side of the Relation

This next question is *really* important: do we want to add a new property to
`Article`? Here's the deal: you can look at every relationship from two different
sides. You could look at a `Comment` and ask for its one related `Article`. *Or*,
you could look at an `Article`, and ask for its many related *comments*.

No matter *what* we answer here, we *will* be able to get or set the `Article`
for a `Comment` object. But, if we *want*, the generator can *also* map the *other*
side of the relationship. This is *optional*, but it means that we will be able to
say `$article->getComments()` to get all of the Comments for an Article. There's
no real downside to doing this, except having extra code if you *don't* need this
convenience. But, this sounds pretty useful. In fact, we can use it to render the
comments on the article page!

If this is making your head spin, don't worry! We'll talk more about this later.
But most of the time, because it makes life easier, you *will* want to generate
both sides of a relationship. So let's say yes.

Then, for the *name* of this new property in `Article`, use the default: `comments`.

*Finally*, it asks you about something called `orphanRemoval`. Say no here. This
topic is a bit more advanced, and you probably don't need `orphanRemoval` unless
you're doing something complex with Symfony form collections. Oh, and we can easily
update our code later to add this.

And... it's done! Hit enter one more time to exit. We did it!

## Looking at the Entities

Because I committed all of my changes before recording, I'll run:

```terminal
git status
```

to see what this did. Cool! It updated *both* `Article` and `Comment`. Open
the `Comment` class first:

[[[ code('42c3eba0fb') ]]]

Awesome! It added a new property called `article`, but instead of the normal
`@ORM\Column`, it used `@ORM\ManyToOne`, with some options that point to the
`Article` class. Then, at the bottom, we have getter and setter methods like normal:

[[[ code('f47cb3db06') ]]]

Now, check out the other side of the relationship, in `Article` entity. This has
a new `comments` property:

[[[ code('d2407c1638') ]]]

And, near the bottom, *three* new methods: `getComments()`, `addComment()` and
`removeComment()`:

[[[ code('1e82ce471f') ]]]

You *could* also add a `setComments()` method: but `addComment()` and `removeComment()`
are usually more convenient:

## The ArrayCollection Object

Oh, and there's one *little*, annoying detail that I need to point out. Whenever you have
a relationship that holds a *collection* of items - like how an `Article` will
relate to a *collection* of comments, you need to add a `__construct()` method
and initialize that property to a `new ArrayCollection()`:

[[[ code('1153653f1e') ]]]

The generator took care of that for us. And, this looks scarier, or at least, more
important than it really is. *Even* though the comments are set to an `ArrayCollection`
object, I want you to think of that like a normal array. In fact, you can count,
loop over, and pretty much treat the `$comments` property *exactly* like a normal
array. The `ArrayCollection` is simply needed by Doctrine for internal reasons.

## ManyToOne Versus OneToMany

Now, remember, we generated a *ManyToOne* relationship. We can see it inside
`Comment`: the `article` property is a ManyToOne to `Article`. But, if you look
at `Article`, huh. *It* has a *OneToMany* relationship back to Comment:

[[[ code('d0b06c24fd') ]]]

This is a really important thing. In reality, ManyToOne and OneToMany do *not* represent
two different types of relationships! Nope, they describe the *same*, *one* relationship,
just viewed from different sides.

## Generating the Migration

Enough talking! Let's finally generate the migration. Find your terminal and run:

```terminal
php bin/console make:migration
```

Go back to your editor and open that new migration file. Woh! Awesome! The end-result
is *super* simple: it adds a new `article_id` column to the `comment` table along
with a foreign key constraint to the article's `id` column:

[[[ code('4b2c78bcb7') ]]]

So even though, in `Comment`, we called the property `article`:

[[[ code('d0b439cc88') ]]]

In the database, this creates an `article_id` column! Ultimately, the database looks
*exactly* like we expected in the beginning! But in PHP, guess what? When we *set*
this `article` property, we will set an entire `Article` *object* on it - *not*
the Article's ID. More about that next.

The migration looks prefect. So find your terminal, and run it!

```terminal
php bin/console doctrine:migrations:migrate
```

Ok, time to create a `Comment` object and learn how to relate it to an `Article`.
