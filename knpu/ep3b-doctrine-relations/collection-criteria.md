# Collection Magic with Criteria

Of course, if we wanted to remove any of the deleted comments from this collection,
we *could* loop over *all* of the comments, check if each is deleted, and return
an array of *only* the ones left. Heck, the collection object even has a `filter()`
method to make this easier!

But... there's a problem. If we *did* this, Doctrine would query for *all* of the
comments, even though we don't *need* all of the comments. If your collection is
pretty small, no big deal: querying for a few extra comments is probably fine. But
 if you have a large collection, like 200 comments, and you want to return a small
sub-set, like only 10 of them, that would be super, *super* wasteful!

## Hello Criteria

To solve this, Doctrine has a *super* powerful and amazing feature... and yet,
somehow, almost nobody knows about it! Time to change that! Once you're an expert
on this feature, it'll be your job to tell the world!

The system is called "Criteria". Instead of looping over all the data, add
`$criteria = Criteria` - the one from Doctrine - `Criteria::create()`:

[[[ code('1874d10846') ]]]

Then, you can chain off of this. The `Criteria` object is similar to the `QueryBuilder`
we're used to, but with a slightly different, well, slightly more *confusing* syntax.
Add `andWhere()`, but instead of a string, use `Criteria::expr()`. Then, there
are a bunch of methods to help create the where clause, like `eq()` for equals,
`gt()` for greater than, `gte()` for greater than or equal, and so on. It's a little
object-oriented builder for the WHERE expression.

In this case, we need `eq()` so we can say that `isDeleted` equals `false`:

[[[ code('c9a279bb96') ]]]

Then, add `orderBy`, with `createdAt => 'DESC'` to keep the sorting we want:

[[[ code('763b1daccb') ]]]

*Creating* the Criteria object doesn't actually *do* anything yet - it's like creating
a query builder. But *now* we can say return `$this->comments->matching()` and pass
`$criteria`:

[[[ code('118a8454fc') ]]]

Because, remember, even though we *think* of the `$comments` property as an array,
it's not! This `Collection` return type is an interface from Doctrine, and our property
will always be some object that implements that. That's a long way of saying that,
while the `$comments` property will look and feel like an array, it is *actually*
an object that has some extra helper methods on it.

## The Super-Intelligent Criteria Queries

*Anyways*, ready to try this? Move over and refresh. Check it out: the 8 comments
went down to 7! And the deleted comment is *gone*. But you haven't seen the *best*
part yet! Click to open the profiler for Doctrine. Check out the last query: it's
*perfect*. It *no* longer queries for *all* of the comments for this article. Nope,
instead, Doctrine executed a super-smart query that finds all comments where the
article matches this article *and* where `isDeleted` is false, or zero. It *even*
did the same for the count query!

Doctrine, that's crazy cool! So, by using `Criteria`, we get *super* efficient
filtering. Of course, it's not *always* necessary. You *could* just loop over
all of the comments and filter manually. If you are removing only a *small* percentage
of the results, the performance difference is minor. The `Criteria` system *is*
better than manually filtering, but, remember! Do *not* prematurely optimize. Get
your app to production, then check for issues. But if you have a *big* collection
and need to return only a small number of results, you should use `Criteria`
immediately.

## Organizing the Criteria into the Repository

One thing I *don't* like about the `Criteria` system is that I do *not* like having
query logic inside my entity. And this is important! To keep my app sane, I want
to have 100% of my query logic *inside* my repository. No worries: we can move
it there!

In `ArticleRepository`, create a `public static function` called
`createNonDeletedCriteria()` that will return a `Criteria` object:

[[[ code('6fc72ef9a4') ]]]

In `Article`, copy the `Criteria` code, paste it here, and return:

[[[ code('4f2c62af65') ]]]

These are the *only* static methods that you should ever have in your repository.
It *needs* to be static simply so that we can use it from inside `Article`. That's
because entity classes don't have access to services.

Use it with `$criteria = ArticleRepository::createNonDeletedCriteria()`:

[[[ code('3fb8c9fc6f') ]]]

Side note: we *could* have also put this method into the `CommentRepository`.
When you start working with related entities, sometimes, it's not clear exactly
*which* repository class should hold some logic. No worries: do your best and
don't over-think it. You can always move code around later.

Ok, go back to your browser, close the profiler and, refresh. Awesome: it still
works great!

## Using the Criteria in a QueryBuilder

Oh, and bonus! in `ArticleRepository`, what if in the future, we need to create
a `QueryBuilder` and want to re-use the logic from the Criteria? Is that possible?
Totally! Just use `->addCriteria()` then, in this case, `self::createNonDeletedCriteria()`:

```php
class ArticleRepository extends ServiceEntityRepository
{
    public function findAllPublishedOrderedByNewest()
    {
        $this->createQueryBuilder('a')
            ->addCriteria(CommentRepository::createNonDeletedCriteria());

        return $this->addIsPublishedQueryBuilder()
            ->orderBy('a.publishedAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
```

These `Criteria` *are* reusable.

## Updating the Homepage

To finish this feature, go back to the homepage. These comment numbers are still
including deleted comments. No problem! Open `homepage.html.twig`, find where we're
printing that number, and use `article.nonDeletedComments`:

[[[ code('87cc156512') ]]]

Ok, go back. We have 10, 13 & 7. Refresh! Nice! Now it's 5, 9 and 5.

Next, let's take a quick detour and leverage some Twig techniques to reduce
duplication in our templates.
