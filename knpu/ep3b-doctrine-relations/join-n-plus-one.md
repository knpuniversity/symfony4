# Query Joins & Solving the N+1 Problem

Problem number 1 is simple: when we search, the term does *not* show up inside
the search box. Lame! Go back to the template. Ok, just add a `value=""` to the
search field. But, how can we get that `q` query parameter? Well, one option would
be to pass the `$q` variable into the template and use it. That's totally simple
and totally allowed.

But! Of course, there's a shortcut! In the template, use `{{ app.request.query.get('q') }}`.

Before we talk about this black magic, go back and refresh. Got it! Woo!

But, back in Twig, I *hope* you're wondering: where the heck did that `app` variable
come from? When you use Twig with Symfony, you get exactly *one* global variable
automatically called `app`. In fact, find your terminal, and re-run the trusty:

```terminal
php bin/console debug:twig
```

Yep! Under "Globals", there is one, called `app`. And its an *object* called, huh,
`AppVariable`. Man, that's a *great* name!

Back in your browser, type Shift+Shift and search for this: `AppVariable`. Cool!
Ignore the setter methods on top - those are just for setup. The `AppVariable`
has a couple of handy methods: `getToken()` and `getUser()` both relate to security.
Then, hey! There's our favorite `getRequest()` method, then `getSession()`,
`getEnvironment()`, `getDebug()` and something called "flashes", which help render
temporary messages, usually used with forms.

It's not a *huge* class, but it's *exactly* what we needed: call `getRequest()`,
then `.query.get()`, which ultimately does the same thing we have in our controller:
go out to the `query` property and call `get()`.

Time for a totally *new* challenge. In addition to searching a comment's content
and author name, I *also* want to search the comment's, article's title. So, if
I search for "Bacon", that should return a few results.

## The Twig For-Else Feature

Oh, by the way, here's a fun Twig feature. When we get *zero* results, we should
probably print a nice message. On a Twig `for` loop, you can add an `else` at the
end. Add a `<td colspan="4">`, a centering class, and: No comments found.

Go back and try it! It works! Pff, except for my not-awesome styling skills. Use
`text-center`. That's better.

## Adding a Join

Anyways, back to the challenge: how can we *also* search the article's title?
In SQL, if we need to reference another table inside the WHERE clause, then we
need to *join* to that table first. 

In this case, we want to join from `comment` to `article`: an inner join is perfect.
How can you do this with the QueryBuilder? Oh, it's awesome:
`->innerJoin('c.article', 'a')`.

That's *it*. When we say `c.article`, we're actually referencing the `article`
property on `Comment`. Thanks to that, we can be lazy! We don't need to tell
Doctrine *how* to join - we don't need an `ON article.id = comment.article_id`.
Doctrine can figure that out on its own. The second argument - `a` - will be
the "alias" for `Article` for the rest of the query.

Before we do *anything* else, go refresh the page. Nothing changes yet, but go
open the profiler and click to look at the query. Yes, it's perfect! It still
*only* selects from `comment`, but it *does* have the `INNER JOIN` to article!

We can now *easily* reference the article somewhere else in the query. Inside
the `andWhere()`, add `OR a.title LIKE :term`.

That's all you need. Move back and refresh again. It works *instantly*. Check out
the query again: this time we have the `INNER JOIN` *and* the extra logic inside
the `WHERE` clause. Building queries with the query builder is not *so* different
than writing them by hand.

## Solving the N+1 (Extra Queries) Problem

You'll *also* notice that we still have a lot of queries: 7 to be exact. And that's
because we are *still* suffering from the N+1 problem: as we loop over each Comment
row, each time we reference a new article's data, a query is made for that article.

But wait... does that make sense anymore? I mean, if we're *already* making a
JOIN to the `article` table, isn't this unnecessary? Doesn't Doctrine *already*
have *all* the data it needs from the first query, thanks to the join?

The answer is no, or, at least not yet. Remember: while the query *does* join
to `article`, it *only* selects data from `comment`. We are *not* fetching *any*
`article` data. That's why the extra 6 queries are still needed.

But at this point, the solution is *dead* simple. Go back to `CommentRepository`
and put `->addSelect('a')`.

When you create a QueryBuilder from inside a repository, that QueryBuilder automatically
knows to select from its own table, so, from `c`. With this line, we're telling
the QueryBuilder to select all of the `comment` columns *and* all of the
`article` columns.

Try it: head back and refresh. It *still* works! But, yes! We're down to just *one*
query. Go check it out: yep! It selects everything from `comment` *and* `article`.

So, the moral of the story is this: *if* your page has a lot of queries because
Doctrine is making a lot of queries across a relationship, just *join* over that
relationship and use `addSelect()` to fetch all the data you need at once.

But... there *is* one confusing thing about this. We're now selecting all of the
`comment` data *and* all of the `article` data. But... you'll notice, the page
still works! What I mean is, *even* though we're suddenly selecting more data, our
`findAllWithSearch()` method is *still* returning *exactly* what it did before:
it returns a array of `Comment` objects. It does *not*, for example, now return
`Comment` *and* `Article` objects.

Instead, Doctrine takes that extra `article` data and stores it in the background
for later. But, it does *not* affect the return value. That's *way* different than
using raw SQL. There, if you select more data, your database returns a result
with more columns. With Doctrine, that's a bit different.

Next, let's polish this admin table by adding pagination.
