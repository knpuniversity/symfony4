# ManyToMany Relationship

We need the ability to add *tags* to each `Article`. And that means, we need a new
relationship! Like always, we could add this by hand. But, the generator can help
us. At your terminal, run:

```terminal
php bin/console make:entity
```

But, hmm. Which entity should we update? We could add a new property to `Article`
called `tags` or... I guess we could also add a new property to `Tag` called `articles`.
That's really the *same* relationship, just viewed from two different sides.

And... yea! We could choose to update *either* class. The side you choose *will*
make a subtle difference, and we'll learn about it soon. Let's update `Article`.
For the property name, use `tags`. Remember, we need to *stop* thinking about the
database and *only* think about our objects. In PHP, I want an `Article` object
to have many Tag objects. So, the property should be called `tags`.

For type, use the fake `relation` type to activate the relationship wizard.
We want to relate this to `Tag` and... perfect! Here is our menu of relationship
options! I already hinted that this will be a `ManyToMany` relationship. But, let's
look at the description to see if it fits. Each article can have many tags. And, each
tag can relate to many articles. Yep, that's us! This is a ManyToMany relationship.

And just like last time, it asks us if we *also* want to map the *other* side of
the relationship. This is optional, and is *only* for convenience. *If* we map the
other side, we'll be able to say `$tag->getArticles()`. That may or may not be useful
for us, but let's say yes. Call the field `articles`, because it will hold an array
of `Article` objects.

And, that's it! Hit enter to finish.

## Looking at the Generating Entities

Exciting! Let's see what changes this made. Open `Article` first:

[[[ code('84eac55e9e') ]]]

Yes! Here is the new `tags` property: it's a ManyToMany that points to the `Tag`
entity. And, like we saw earlier with comments, whenever you have a relationship
that holds *many* objects, in your constructor, you need to initialize that property
to a new `ArrayCollection`:

[[[ code('2d1a9d76f6') ]]]

The generator did that for us.

At the bottom, instead of a getter & setter, we have a getter, adder & remover:

[[[ code('5ad3f2e73e') ]]]

There's no special reason for that: the adder & remover methods are just convenient.

Next, open `Tag`:

[[[ code('c0a72a5b20') ]]]

The code here is almost *identical*: a ManyToMany pointing back to `Article` and,
at the bottom, getter, adder & remover methods.

## Owning Versus Inverse Sides

Great! But, which side is the owning side and which is the inverse side of the
relationship? Open `Comment`:

[[[ code('463c5d148d') ]]]

Remember, with a ManyToOne / OneToMany relationship, the ManyToOne side is *always*
the owning side of the relation. That's easy to remember, because this is where
the column lives in the database: the `comment` table has an `article_id` column.

But, with a ManyToMany relationship, well, *both* sides are ManyToMany! In `Article`,
ManyToMany. In `Tag`, the same! So, which side is the *owning* side?

The answer lives in `Article`. See that `inversedBy="articles"` config?

[[[ code('6ffb65350e') ]]]

That points to the `articles` property in `Tag`. On the other side, we have
`mappedBy="tags"`, which points *back* to `Article`:

[[[ code('e965a5964f') ]]]

Here's the point: with a ManyToMany relationship, you *choose* the owning side by
where the `inversedBy` versus `mappedBy` config lives. The generator configured
things so that `Article` holds the owning side because that's the entity we
chose to update with `make:entity`.

Remember, all of this owning versus inverse stuff is important because, when Doctrine
saves an entity, it *only* looks at the *owning* side of the relationship to figure
out what to save to the database. So, if we add tags to an article, Doctrine will
save that correctly. But, if you added articles to a tag and save, Doctrine would
do nothing. Well, in practice, if you use `make:entity`, that's not true. Why?
Because the generated code *synchronizes* the owning side. If you call
`$tag->addArticle()`, inside, that calls `$article->addTag()`:

[[[ code('9f5bbcba0d') ]]]

## Generating the Migration

Enough of that! Let's generate the migration:

```terminal
php bin/console make:migration
```

Cool! Go open that file:

[[[ code('244fd471f8') ]]]

Woh! It creates a new *table*! Of course! That's how you model a ManyToMany
relationship in a relational database. It creates an `article_tag` table with
only two fields: `article_id` and `tag_id`.

This is very different than anything we've seen so far with Doctrine. This is the
*first* time - and really, the only time - that you will have a table in the database,
that has *no* direct entity class. This table is created magically by Doctrine to
help us relate tags and articles. *And*, as we'll see next, Doctrine will also
automatically insert and delete records from this table as we add and remove tags
from an article.

Now, run the migration:

```terminal
php bin/console doctrine:migrations:migrate
```

Let's go tag some articles!
