# Adding ManyToOne Relation

Hmm. We want each Article to have many Comments... and we want each Comment to
*belong* ton one Article. Forget about Doctrine for a minute: let's think about
this should look in the database. Because each `Comment` should *belong* to one
article, this means that the `comment` table needs an `article_id` column.

So far, in Doctrine, when we need a new column in the table, we add a new property
in the entity. And, at first, this is no different: we need to add a new property
to `Article`.

## Generating the Relationship

To do that, let's once-again use:

```terminal
php bin/console make:entity
```

Type `Comment` so we can add the new field to it. But then, wait! This is a *very*
important moment: it's asking us for the new property's name. If you're thinking
that this should be something like `articleId`... that makes sense. But, surprise!
It's wrong!

Instead, use `article`. I'll explain *why* soon. For the field type, we can use
a "fake" option here called: `relation`, which will start a special wizard that
will guide us through the relation setup process.

The first question is:

> What class should this entity be related to?

Easy: `Article`. Now, it explains the *four* different types of relationships that
exist in Doctrine: ManyToOne, OneToMany, ManyToMany and OneToOne. If you're not
sure which relationship you need, you can read through the descriptions to find
the one that fits best.

Look at the `ManyToOne` description:

> Each comment relates to one Article

That sound perfect! And then:

> Each Article can have many Comment objects

Brilliant! *This* is the relationships we'll need. In fact, it's kind of the "king"
of relationships: you'll liekly create ManyToOne relationships more than any other.

Answer with: ManyToOne.

Next, it asks us if the `article` property on `Comment` is allowed to be null.
Basically, it's asking us if it should be legal for a Comment to be saved to the
database *without* an `Article`. A Comment *must* have an article, so let's say
no.

## Generating the Other (Inverse) Side of the Relation

This next question is *really* important: do we want to add a new property to
`Article`? Here's the deal: you can look at every relationship from two different
sides. You can look at a `Comment` and ask for its related `Article`. *Or*, you
could look at an `Article`, and ask for its related *comments*.

We will *for sure* be able to get or set the `Article` on a `Comment` object. But,
if we *want* to, the generator can *also* map the *other* side of the relationship.
This is *optional*, but it means that we will be able to say
`$article->getComments()` to return all of the related Comments for an Article.
That sounds pretty handy. In fact, we can use it to render the comments on the
article page.

If this is making your head spin, don't worry! We'll talk more about this later.
But most of the time, because it makes life easier, you *will* want to generate
both sides of a relationship. So let's say yes.

Then, for the *name* of this new property in `Article`, use the default: `comments`.

*Finally*, it asks you about something called `orphanRemoval`. Say no here. This
topic is a bit more advanced, and you probably don't need `orphanRemoval` unless
you're doing something complex with Symfony form collection. Oh, and we can easily
update our code to re-add this if we want it.

And.. it's done! Hit enter one more time to exit.

## Looking at the Entities

Because I committed all my changes before recording, I'll run:

```terminal
git status
```

to see what this did. Cool! It updated *both* `Article` and `Comment`. Go open
the `Comment` class first.

Awesome! It added a new property called `article`, but instead of the normal
`@ORM\Column`, it uses `@ORM\ManyToOne`, with some options that point to the
`Article` class. Then, at the bottom, we have getter and setter methods like normal.

Now, check out the `Article` entity. This has a new `comments` property. And, near
the bottom, a `getComments()` method. Oh, and, to hopefully make life easier, instead
of a `setComments()` method, it generated `addComment()` and `removeComment()`.

## The ArrayCollection Object

Oh, and there's on *little*, annoying detail I need to point. Whenever you have
a relationship that holds an *collection* of items - like how an `Article` will
relate to a collection of comments, you need to add a `__construct()` method
and initialize that property to a `new ArrayCollection()`.

The generator took care of that for us - I just wanted to point it out. And, this
looks scarier, or at least, more important than it is. *Even* though the comments
are set to an `ArrayCollection`, I want you to think of that just like a normal
array. In fact, you can count, loop over, and pretty much treat the `$comments`
property like any normal array. The `ArrayCollection` is just needed by Doctrine
for some internal reason. But, it *does* have a few helper methods we'll learn
about later.

## ManyToOne Versus OneToMany

Ok, remember, we generated a ManyToOne relationship. We can see that inside
`Comment`: the `article` property is a ManyToOne to `Article`. But, if you look
at `Article`, huh. *It* has a OneToMany relationship back to Comment. This is a
really important thing to notice. When we used `make:entity`, it asked us which
of these *four* relationship types we wanted. Well, it turns out that ManyToOne
and OneToMany describe the *same*, *one* relationship! Just viewed from different
sides.

## Generating the Migration

Enough talking! Let's finally generate the migration for this change. Find your
terminal and run:

```terminal
php bin/console make:migration
```

Go back to your code and open that new migration file. Woh! Awesome! The end-result
is *super* simple: it adds a new `article_id` column to the `comment`  table, with
a foreign key constraint to the article's `id` column. 

So even though, in the `Comment` entity, we called the property `article`, in the
database, this creates an `article_id` column! Ultimately, the database looks
*exactly* like we expected in the beginning!

But, in PHP, instead of thinking about `articleId`, we called the property `article`.
And, guess what? When we *set* this property, we will set an entire `Article`
*object* on it - *not* the Article's id. We'll talk more about that next.

The migration looks prefect. So find your terminal, and run it!

```terminal-silent
php bin/console doctrine:migrations:migrate
```

Next, let's create a `Comment` object and learn how to relate it to an `Article`.
