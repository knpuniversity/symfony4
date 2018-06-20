# ManyToMany Joins & When to Avoid ManyToMany

We have the N+1 query problem once again. Click to view those queries. The new
queries are mixed in here, but you'll see 7 new queries that select from `tag`
with an INNER JOIN so that it can find all the tags for just *one* Article.
Each time we reference the tags for a new `Article`, it makes a new query for
*that* article's tags.

This is quite possibly *not* something you need to worry about, at least, not until
you can see a *real* performance issue on production. But, we *should* be able to
fix it. The *first* query on this page finds all of the published articles. Could
we add a join to that query to select the tag data all at once?

Totally! Open `ArticleController` and find the `homepage()` action. Right now, we're
using `$articles = $repository->findAllPublishedOrderedByNewest()`:

[[[ code('bb53c2a5aa') ]]]

Open `ArticleRepository` to check that out:

[[[ code('8bea03fdfc') ]]]

This custom query finds the `Article` objects, but does *not* do any special joins.
Let's add one. But.... wait. This is weird. If you think about the database, we're
going to need to join *twice*. We first need a LEFT JOIN from `article` to
`article_tag`. Then, we need a another JOIN from `article_tag` to `tag` so that
we can select the tag's data.

This is where Doctrine's `ManyToMany` relationship *really* shines. Don't think
*at all* about the join table. Instead, `->leftJoin()` on `a.tags` and use
`t` as the new alias:

[[[ code('7a03d6dc3b') ]]]

The `a.tags` refers to the `tags` property on `Article`. And because Doctrine knows
that this is a ManyToMany relationship, it knows how to join *all* the way over to
`tag`. To actually fetch the tag data, use `->addSelect('t')`:

[[[ code('6d68e93b7c') ]]]

That is it. Go back to our browser. The 15 queries are... back down to 8! Open the
profiler to check them out. Awesome! The query selects everything from `article`
*and* all the fields from `tag`. It can do that because it has *both* joins!
That's nuts!

## When a ManyToMany Relationship is Not What You Need

Ok guys, there is *one* last thing we need to talk about, and, it's a warning
about ManyToMany relations.

What if we wanted to start saving the *date* of when an `Article` was given a Tag.
Well, crap! We can't do that. We could record the date that a `Tag` was created or
the date an `Article` was created, but we *can't* record the date when an `Article`
was linked to a `Tag`. In fact, we can't save *any* extra data about this relationship.

Why? Because that data would need to live on this `article_tag` table. For example,
we might want a third column called `created_at`. The problem is, when you use
a `ManyToMany` relationship, you *cannot* add any more columns to the join table.
It's just not possible.

This means that if, in the future, you *do* need to save extra data about the
relationship, well, you're in trouble.

So, here's my advice: before you set up a ManyToMany relationship, you need to think
hard and ask yourself a question:

> Will I ever need to store additional metadata about this relationship?

If the answer is yes, if there's even *one* extra piece of data that you want to
store, then you should *not* use a ManyToMany relationship. In fact, you can't use
Doctrine at all, and you need to buy a new computer.

I'm *kidding*. *If* you need to store extra data on the `article_tag` table, then,
instead, create a new `ArticleTag` entity for that table! That `ArticleTag` entity
would have a ManyToOne relationship to `Article` and a ManyToOne relationship to
`Tag`. This would effectively give you the *exact* same structure in the database.
But *now*, thanks to the new `ArticleTag` entity, you're free to add whatever other
fields you want.

If you generated a ManyToMany relationship by mistake and want to switch, it's not
the end of the world. You *can* still create the new entity class and generate a
migration so that you don't lose your existing data. But, if you can configure things
in the beginning... well, even better.

Ok guys, you are now Doctrine pros! Your relationship skills strike fear at the
heart of your enemies, and your ability to JOIN across tables is legendary among
your co-workers. 

Yes, there is more to learn, like how to write even more complex queries, and there
are a lot of other, cool features - like Doctrine inheritance. But, all of the
*super* important stuff that you need to create a real site? Yea, you got it *down*.
So go out there, `SELECT * FROM world`, and build something amazing with Doctrine.

Alright guys, seeya next time.
