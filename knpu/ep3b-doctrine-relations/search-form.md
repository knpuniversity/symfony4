# Request Object & Query OR Logic

Because astronauts *love* to debate news, our site will have a *lot* of comments
on production. So, let's add a search box above this table so we can find things
quickly. 

Open the template and, on top, I'm going to paste in a simple HTML form:

[[[ code('8e0ec9655a') ]]]

We're not going to use Symfony's form system because, first, we haven't learned about
it yet, and second, this is a *super* simple form: Symfony's form system wouldn't
help us much anyways.

Ok! Check this out: the form has one input field whose name is `q`, and a button
at the bottom. Notice that the form has *no* `action=`: this means that the form
will submit *right* back to this same URL. It *also* has no `method=`, which means
it will submit with a GET request instead of POST, which is *exactly* what you want
for a search or filter form.

Let's see what it looks like: find your browser and refresh. Nice! Search for
"ipsam" and hit enter. No, the search won't magically work yet. But, we *can*
see the `?q=` at the end of the URL.

## Fetching the Request Object

Back in the controller, hmm. The *first* question is: how can we read the `?q`
query parameter? Actually, let me ask some bigger questions! How could we read POST
data? Or, headers? Or the content of uploaded files?

Science! Well, actually, the request! *Any* time you need to read information
about the request - POST data, headers, cookies, etc - you need Symfony's `Request`
object. How can you get it? Well... you can probably guess: add another argument
with a `Request` type-hint:

[[[ code('7d24712556') ]]]

Important: get the one from `HttpFoundation` - there are several, which, yea,
is confusing:

[[[ code('de6a86a978') ]]]

So far, we know of *two* "magical" things you can do with controller arguments.
First, if you type-hint a service class or interface, Symfony will give you that
service. And second, if you type-hint an entity class, Symfony will query for that
entity by using the wildcard in the route.

Well, you *might* think that the `Request` falls into the *first* magic category.
I mean, that the `Request` is a service. Well, actually... the `Request` object
is *not* a service. And, the reasons why are technical, and honestly, not very
important. The ability to type-hint a controller argument with `Request` is the
*third* "magic" trick you can do with controller arguments. So, it's (1) type-hint
services, (2) type-hint entities or (3) type-hint the `Request` class. There *is*
other magic that's possible, but these are the 3 main cases. 

Oh, side-note: while the `Request` object is *not* in the service container, there
*is* a service called `RequestStack`. You can fetch it like any service and call
`getCurrentRequest()` to get the `Request`:

```php
public function index(RequestStack $requestStack)
{
    $request = $requestStack->getCurrentRequest();
}
```

*Anyways*, the request gives us access to *everything* about the... um, request!
Add `$q = $request->query->get('q')`:

[[[ code('7f14e1f9bb') ]]]

This is how you read *query* parameters, it's like a modern `$_GET`. There are
other properties for almost everything else: `$request->headers` for headers,
`$request->cookies`, `$request->files`, and a few more. Basically, any time
you want to use `$_GET`, `$_POST`, `$_SERVER` or any of those global variables,
use the `Request` instead.

## A Custom Query with OR Logic

Now that we have the search term, we need to use that to make a custom query. So,
sadly, we *cannot* use `findBy()` anymore: it's not smart enough to do queries that
use the `LIKE` keyword. No worries: inside `CommentRepository`, add a public function
called `findAllWithSearch()`. Give this a *nullable* string argument called `$term`:

[[[ code('320b18bdf8') ]]]

I'm making this nullable because, for convenience, I want to allow this method to
be called with a `null` term, and we'll be smart enough to just return everything.

Above the method, add some PHP doc: this will `@return` an array of `Comment`
objects:

[[[ code('3ebeafbb6f') ]]]

Ok: we already know how to write custom queries: `$this->createQueryBuilder()`
with an alias of `c`:

[[[ code('827e915d53') ]]]

Then, *if* a `$term` is passed, we need a WHERE clause. But, here's the tricky part:
I want to search for the term on a couple of fields: I want
`WHERE content LIKE $term OR authorName LIKE $term`.

How can we do this? Hmm, the `QueryBuilder` apparently has an `orWhere()` method.
Perfect, right? No! Surprise, I *never* use this method. Why? Imagine a complex query
with various levels of AND clauses mixed with OR clauses and parenthesis. With a
complex query like this, you would need to be *very* careful to use the parenthesis
in just the right places. One mistake could lead to an OR causing *many* more results
to be returned than you expect!

To *best* handle this in Doctrine, always use `andWhere()` and put all the OR logic
right inside: `c.content LIKE :term OR c.authorName LIKE :term`. On the next line,
set `term` to, this looks a little odd, `'%'.$term.'%'`:

[[[ code('0e474b18c4') ]]]

By putting this all inside `andWhere()` - instead of `orWhere()` - all of that
logic will be surrounded by a parenthesis. Later, if we add another `andWhere()`,
it'll logically group together properly.

Finally, in all cases, we want to return `$qb->orderBy('c.createdAt', 'DESC')`
and `->getQuery()->getResult()`:

[[[ code('baa6b76b38') ]]]

Remember, `getResult()` returns an array of results, and `getOneOrNullResult()`
returns just *one* row.

Phew! That looks great! Go back to the controller. Use that method:
`$comments = $repository->findAllWithSearch()` passing it `$q`:

[[[ code('339dd41551') ]]]

Moment of truth! First, remove the `?q=` from the URL. Ok, everything looks good.
Now search for something very specific, like, ahem, `reprehenderit`. And, yes!
A *much* smaller result. Try an author: `Ernie`: got it!

Woo! This is great! But, we can do more! Next, let's learn about a Twig global
variable that can help us fill in this input box when we search. Then, it's finally
time to add a *join* to our custom query.
