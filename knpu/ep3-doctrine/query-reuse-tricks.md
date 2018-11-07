# Query Logic Re-use & Shortcuts

One of my *favorite* things about the query builder is that, with a few tricks,
you can *reuse* query logic! Check this out: right now, we only have one custom method
in `ArticleRepository`:

[[[ code('a27541ba41') ]]]

But, as our app grows, we'll *certainly* need to add more. And there's a *pretty*
darn good chance that *another* custom query will *also* need to filter its results
to only show *published* articles. In a *perfect* world, we would *share* that logic,
between both custom methods. And... we can do that!

Step 1 is to isolate the query logic that we need to share into its own private
method. At the bottom, create a `private function addIsPublishedQueryBuilder()`
with a `QueryBuilder` type-hint - the one from `Doctrine\ORM` - and `$qb`:

[[[ code('2f6615b8e2') ]]]

Next, go up, copy that part of the query, and just return
`$qb->andWhere('a.publishedAt IS NOT NULL')`:

[[[ code('6c51624a78') ]]]

And since we're *returning* this - and each query builder method returns
itself - back up top, we can say `$qb = $this->createQueryBuilder('a')`, and below,
`return $this->addIsPublishedQueryBuilder()` passing it `$qb`:

[[[ code('9c7f0c1aee') ]]]

The rest of the query can chain off of this.

And... that's it! One important note is that you need to consistently use the same
alias, like `a`, across all of your methods.

## Fancier Re-Use

This is nice... but since I do this a lot, we can get a bit fancier. Create
another private method called `getOrCreateQueryBuilder()` with a `QueryBuilder`
argument like before, but make it optional:

[[[ code('21d9acb832') ]]]

Here's the idea: when someone calls this method, *if* the query builder is passed,
we'll just return it. Otherwise we will return a new one with
`$this->createQueryBuilder('a')`:

[[[ code('3cdd75dfbe') ]]]

If you're not used to this syntax, it means that if a `QueryBuilder` object is passed,
return that `QueryBuilder` object. If a `QueryBuilder` object is not passed, then create one.

This is *cool*, because *now* we can make the argument to `addIsPublishedQueryBuilder()`
*also* optional:

[[[ code('eefe8717a2') ]]]

Inside, use the new method: `return $this->getOrCreateQueryBuilder()` passing it `$qb`,
and then our `andWhere()`:

[[[ code('973d9eb4fa') ]]]

But the *real* beautiful thing is back up top. This *whole* method can now be one
big chained call: `return $this->addIsPublishedQueryBuilder()` - and pass nothing:

[[[ code('acae5accbe') ]]]

It will create the `QueryBuilder` for us.

So not only do we have really nice public functions for fetching data, we also have
some private functions to help us *build* our queries. Let's make sure it works.
Find your browser and, refresh! It still looks good!

## ParamConverter: Automatically Querying

Ok, enough custom queries for now. Instead, I want to show you a query shortcut!

Go to `ArticleController` and find the `show()` action. Sometimes you need to query
for an *array* of objects. So, we get the repository, call some method, and, done!

[[[ code('5a5cd7ef86') ]]]

Life is good. But it's *also* really common to query for just *one* object. And
in these situations, if the query you need is simple... you can make Symfony do
*all* of the work:

[[[ code('2935fc6be3') ]]]

Let me show you: remove the `$slug` argument and replace it with `Article $article`:

[[[ code('dea7b27bed') ]]]

Then, below, because I removed the `$slug` argument, use `$article->getSlug()`:

[[[ code('51e97abdda') ]]]

We can also remove *all* of the query, and even the 404 logic:

[[[ code('0d7728b1a7') ]]]

Before we talk about this, move over and click on one of the articles. Yea! Somehow,
this totally works! Back in our code, we can remove the unused `EntityManagerInterface`
argument:

[[[ code('fd29c41f38') ]]]

Here's the deal. We already know that if you type-hint, a *service*, Symfony will
pass you that service. In addition to that, if you *type-hint* an *entity* class,
Symfony will automatically query for that entity. How? It looks at all of the route's
placeholder values - which is just one in this case, `{slug}` - and creates a query
where the `slug` field matches that value:

***TIP
It requires `sensio/framework-extra-bundle` to be installed in order to 
automatically query for entity objects
***

[[[ code('523e425f15') ]]]

In other words, to use this trick, your routing wildcard *must* be named the same
as the property on your entity, which is usually how I do things anyways. It executes
the *exact* same query that we were doing before by hand! If there is *not* a slug
that matches this, it *also* automatically throws a 404, before the controller is
ever called.

In fact, try that - put in a bad slug. Yep, error! Something about the `Article`
object not found by the `@ParamConverter` annotation. So, that's not a great error
message - it makes more sense if you know that the name of this feature internally
is `ParamConverter`.

So... yea! If you organize your route wildcards to match the property on your entity,
which is a good idea anyways, then you can use this trick. If you need a more complex
query, no problem! You can't use this shortcut, but it's still simple enough: autowire
the `ArticleRepository`, and then call whatever method you need.
