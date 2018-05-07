# Collection Magic with Criteria

Sure, to remove any deleted comments, we *could* loop over *all* of the comments
and remove them manually. Heck, the collection object even has a `filter()` method
to make this easier!

But... there's a problem. If we *did* this, Doctrine would query for *all* of the
comments, even though we don't *need* all of the comments. If your collection is
pretty small, no big deal: querying for a few extra comments is probably fine. But,
if you have a large collection, like 200 comments, and you want to return a small
sub-set, like only 10 of them, that would be super, *super* wasteful!

## Hello Criteria

Because of this, there is a feature in Doctrine that is *super* powerful and amazing...
and yet, somehow, almost nobody knows about it! Time to change that! Once you'r
an expert, it'll be your job to tell all your friends!

The system is called `Criteria`. Instead of looping over all the data, add
`$criteria = Criteria` - the one from doctrine - `Criteria::create()`. Then, you
can chain off of this. The `Criteria` object is similar to the `QueryBuilder` we're
used to, but with a slightly different, well, slightly more *confusing* syntax.
Add `andWhere()`, but instead of a string, use `Criteria::expr()` and then there
are a bunch of methods to help create the where clause, like `eq()` for equals,
`gt()` for greater than, `gte` for greater than or equal, and so on. It's a little
object-oriented builder for the WHERE expression.

In this case, we need `eq()` so we can say that `isDeleted` equals `false`. Then,
add `orderBy`, with `createdAt => 'DESC` to keep the sorting we want.

*Creating* the Criteria object doesn't actually *do* anything yet - it's like creating
a query builder. But *now* we can say return `$this->comments->matching()` and pass
the `$criteria`.

Because, remember, even though we *think* of the `$comments` property as an array,
it's not! This `Collection` return type is an interface from Doctrine, and our property
will always be some object that implements it. That's a long way of saying that,
while the `$comments` property will look and feel like an array, it is *actually*
an object that has some extra helper methods on it.

## The Super-Intelligent Criteria Queries

*Anyways*, ready to try this? Move over and refresh. Check it out: the 8 comments
went down to 7! And the deleted comment is *gone*. But you haven't seen the *best*
part yet! Click to open the profiler for Doctrine. Check out the last query: it's
*perfect*. It does *not* query for *all* of the comments for this article. Nope,
it built a super-smart query that finds all comments where the article matches this
article *and* where `isDeleted` is false, or zero. It *even* did the same for the
count query!

Doctrine, that's crazy cool! So, by using `Criteria`, we get *super* efficient
filtering. Of course, its not *always* necessary. You can always just loop over
all of the comments and filter manually. If you are removing only a *small* percentage
of the results, the performance difference is minimal. The `Criteria` system *is*
better than that, but, remember! Do *not* prematurely optimize. Get your product
out the door first, then check for issues. But if you have a *big* collection and
are returning only a small number of results, you'll probably want to use the
`Criteria` immediately.

## Organizing the Criteria into the Repository

One thing I *don't* like about the `Criteria` system is that I do *not* like having
query logic inside my entity. And this is important! To keep my app sane, I want
to have 100% of my query logic *inside* my repository. But no worries, we can move
it there!

In `ArticleRepository`, create a `public static function` called
`createNonDeletedCriteria()` that this will return a `Criteria` object. In `Article`,
copy the `Criteria` code, paste it here, and return.

These are the *only* static methods that you should ever have in your repository.
They *need* to be static simply so that we can use them from inside `Article`. Because,
simple entity classes never have access to services.

Use it with `$criteria = ArticleRepository::createNonDeletedCriteria()`. Side note:
we *could* have also put this method into the `CommentRepository`. When you start
to work with related entities, sometimes, it's not clear exactly *which* repository
class should hold some logic. Don't sweat it too much: do your best and don't
over-think it. You can always move code around later.

Ok, go back to your browser, close the profiler and, refresh. Awesome: it still
works great!

## Using the Criteria in a QueryBuilder

Oh, and bonus! in `ArticleRepository`, suppose that in the future, we're creating
a `QueryBuilder` and want to re-use the logic from the Criteria. Is that possible?
Yep, just use `->addCriteria()` then, in this case, `self::createNonDeletedCriteria()`.
Hmm, I'm *really* starting to think I should have put that method into `CommentRepository`.

Anyways, the point is: `Criteria` *are* reusable.

## Updating the Homepage

To finish this feature, go back to the homepage. These comment numbers are still
including deleted comments. No problem! Open `homepage.html.twig`, find where we're
printing that number, and use `article.nonDeletedComments`.

Ok, go back. We have 10, 13 & 7. Refresh! Nice! Now it's 5, 9 and 5.

Next, let's take a quick detour and leverage some Twig techniques to reduce duplication
in our templates.
