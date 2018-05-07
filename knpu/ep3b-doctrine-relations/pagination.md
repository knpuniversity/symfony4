# Pagination

On production - because The SpaceBar is going to be a *huge* hit with *a lot*
of insightful comments - this list will eventually become, *way* long. Not only
will it be hard to use, it'll become a performance issue. If you ever need to query
and render for more than 100 Doctrine entities, you're going to have slow loading
times. If you try to print 1000, your page just won't load.

But no problem! The Internet has a tried-and-true solution for this: pagination.
Doctrine itself doesn't come with any pagination features. But, it doesn't need
to: there are a few great libraries for this.

Search for KnpPaginatorBundle. As usual, my disclaimer is that I did *nothing*
to help build this bundle, I just think it's great. Find the `composer require`
line, copy that, find your terminal and paste:

```terminal
composer require knplabs/knp-paginator-bundle
```

While that's installing, go back to its documentation. As I *love* to tell you,
over and over again, the *main* reason to install a bundle is because that bundle
will give you new services. And that's 100% true for this bundle.

Before, we talk more about that, notice that this has some details about enabling
your bundle. That happens automatically in Symfony 4 thanks to Flex.

## Paginator Usage and the Autowiring Alias

Anyways, look down at the Usage example. Hmm, from a controller, it says to use
`$this->get('knp_paginator')` to get some paginator service. Then, you pass that
a query, the current page number, read from a `?page=` query parameter, and the
number of items you want per page. Then the paginator handles the rest! If you
want 10 results per page and you're on page 3, the paginator will fetch the
exact results you need by adding a LIMIT and OFFSET to your query.



The *one* tricky thing is that the documentation is a little bit out of date. The
`$this->get()` method - which is the same as saying `$this->container->get()` - is
the historic way to fetch a service out of the container by using its *id*. Depending
on your setup, that may or may not even be possible in Symfony 4. And, in general,
it's *no* longer considered a best-practice to do this. Instead, you should use
dependency injection, which almost always means, autowiring.

But, hmm, it doesn't say anything about autowiring here. That's a problem: the
bundle needs to tell us what class or interface we can use to autowire the
paginator service. No problem! Let's figure it out on our own. 

Go back to your terminal Excellent! The install finished. Now run:

```terminal
php bin/console debug:autowiring
```

Search for pager. Boom! Apparently there is a `PaginatorInterface` we can use to
get that *exact* service. We are in business!

## Using the Paginator

Back in `CommentAdminController`, add that as the 3rd argument: `PaginatorInterface`.
Make sure to use auto-complete to get the `use` statement. Call the arg `$paginator`.

Next, go back to the docs and copy the `$pagination =` section, go back, and
paste.

Ok, so what should we use for `$query`? So, when you use a paginator, an important
thing happens: we are no longer responsible for actually *making* the query. Nope,
we're now responsible for *building* a query and then passing it to the paginator.
This `$query` variable should be a QueryBuilder.

Ok! Back in `CommentRepository`, let's refactor this method to return that instead.
Remove the `@returns` and, instead, use a `QueryBuilder` return type. Next, at the
bottom, remove the `getQuery()` and `getResults()` lines. Finally, rename the method
to `getWithSearchQueryBuilder()`,

Perfect! Back in the controller, add
`$queryBuilder = $repository->getWithSearchQueryBuilder($q)`. Pass this variable
below.

*Finally*, instead of passing `comments` into the template, pass this `pagination`
variable instead. Open `index.html.twig` so we can make changes there. First, at
the top, let's print the *total* number of comment because, we may only show 10
results on the page, but there are actually 100 in the table. To do that, go back
to the docs. Ah, this is perfect. Use: `pagination.getTotalItemCount()`.

Then, down in the loop, change this to `for comment in pagination`.

Yes, `pagination` is an *object*. But, you *can* loop over it to get the comments
for the current page only.

Oh, and at the bottom, we need some navigation to help the user go to the other
pages. That's really easy: on the docs, copy the `knp_pagination_render()` line
and, paste!

Phew! Let's go check it out! Yes! 100 total results, but only 10 on this page. We
can click to page 2, then 3 and so-on. Heck, the search even works! Try something
really common, like `est`. The URL has the `?q=` query parameter. And, if you change
pages, it stays: this is page 2 of that search. Dang, that's awesome.

## Using the Bootstrap Pager Navigation Theme

Of course, there's one *super* minor problem... um... dang, that navigation looks
*horrible*. But, that's easy to fix. The bundle comes with a bunch of different
*themes* for the navigation. Scroll back up to the configuration example. Obviously,
you don't *need* to configure *anything* on this bundle. But, there are a lot of
options. The most important one is this: `template.pagination`. This determines
which template is used to build the navigation links. *And*, it ships with one
for Bootstrap 4, which is what we're using. Booya!

So, first question: where should this configuration live? Sometimes, a recipe will
create a file for us, like the `stof_doctrine_extensions`. But in this case that
didn't happen. And that's ok! Not every bundle *needs* to give you a config file.
Just create it by hand: `knpu_paginator.yaml`.

As usual, the filename matches the root config key. But, we know from pervious
tutorials that the filename is actually meaningless. Next, copy the config down
toe the `pagination` line, move over, paste, then remove all the stuff we don't
need. Finally, copy the bootstrap v4 template name and, paste.

We're ready! Move back and refresh. Boom! It still works! It's beautiful! We
rock! Our pagination is awesome! I'm super happy about this.

With this perfect, let's turn to our last big topic: generating a *totally* new,
ManyToMany relationship.
