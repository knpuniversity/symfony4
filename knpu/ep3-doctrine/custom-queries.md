# Custom Queries

How do you write custom queries in Doctrine? Well, you're already familiar with
writing SQL, and, yea, it *is* possible to write raw SQL queries with Doctrine.
But, most of the time, you won't do this. Instead, because Doctrine is a library
that works with *many* database engines, Doctrine has its *own* SQL-like language
called Doctrine query language, or DQL.

Fortunately, DQL looks almost *exactly* like SQL. Exception, instead of table and
column names in your query, you'll use class and property names. Again, Doctrine
*really* wants to you pretend like there is no database, tables or columns behind
the scenes.

## Introducing: The Query Builder

So to write a custom query, you can either create a DQL string directly, *or* you
can do what I usually do: use the query builder. The query builder is just an
object-oriented builder that helps you create DQL. Yea, it's just that simple.

And you can see a *pretty* good example right here: you can add where statements
order by, limits and pretty much anything else. One nice thing is that you can
do this all in any order - you could be the order by first, and the where statements
after. The query builder doesn't care!

Oh, and see this `andWhere()`? There *is* a normal `where()` method, but it's safe
to use `andWhere()` even if this is the first WHERE clause - the query builder is
smart enough to figure it out. I recommend `andWhere()`, because `where()` will
remove any previous where clauses you may have added... which... might be surprising!

DQL, and the query builder, also use prepared statements. If you're not familiar
with them, it's a really simple idea: whenever you want to put a dynamic value int
a query, instead of hacking it into the string with concatenation, put `:` and a
placeholder name. Then, later, give that placeholder a value with `->setParameter()`.
This prevents SQL injection attacks.

## Writing our Custom Query

In our case, we won't need any arguments, and I'm going to simplify a bit. Let's
say `andWhere('a.isPublished IS NOT NULL')`.

You can *totally* see how close this is to normal SQL. You can even put OR statements
inside the string, like `a.isPublished IS NULL OR a.isPublished > NOW()`, which
might be used to find all *unpublished* articles.

Oh, and what the heck does the `a` mean? Think of this as the table *alias* for
`Article` in the query - just like how you can say `SELECT a.* from article as a`.

It could be anything: if you used `article` instead, you'd just need to change all
the references from `a.` to `article.`.

Let's also add our `orderBy`, with `.publishedAt`, DESC.

Oh, and this is a good example of how we're referencing the *property* name on the
entity. The *column* name in the database is actually `published_at`, but we don't
use that here.

Finally, let's remove the max result.

Once you're done building your query, you always call `getQuery()` and then, to
get the array of results, `getResults()`, the array of `Article` objects.

Below this method, there's an example of finding just *one* object. It's almost
the same: build the query, call `getQuery()`, but then call `getOneOrNullResult()`.

To review: you *always* call `getQuery()`, then you'll either call `getResults()`
to return many rows of articles, or `getOneOrNullResult` to return a single `Article`
object.

Now that our new `findAllPublishedOrderedByNewest` method is done, let's go use
it in the controller: `$repository->`, and there it is!

Let's give it a try! Move over and, refresh! Perfect! The order is correct and the
unpublished articles are gone.

## Autowiring ArticleRepository

To make this even cooler, let me show you a trick. Instead of getting the entity
manager and then calling `getRepository()` to get the `ArticleRepository`, you can
take a shortcut, and just type `ArticleRepository $repository`.

This works for a *simple* reason: all of your repositories are automatically registered
as services in the container. So you can autowire like anything else. *This* is
how I actually code when I need a repository.

And when we refresh, no surprise, it *still* works!

Custom queries are a *big* topic, and we'll continue writing a few more here and
there. But if you have something particularly challenging, check out our
[Go Pro with Doctrine Queries](https://knpuniversity.com/screencast/doctrine-queries)
tutorial. That tutorial uses Symfony 3, but the query logic is *exactly* the same
as in Symfony 4.

Next, I want to show you two more tricks: one for re-using query logic between
multiple queries, and another super shortcut to fetch any entity with *zero* work.
