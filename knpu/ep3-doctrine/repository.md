# All about Entity Repositories

Now that the article show page is dynamic, let's turn to the homepage... cause news
stories are *totally* still hardcoded. Open `ArticleController` and find the
`homepage` action. Perfect. *Just* like before, we need to query for the articles/
This means that we need an `EntityManagerInterface $em` argument. Next, we always
get the repository for the class: `$repository = $em->getRepository(Article::class)`.
And *then* we can say, `$articles = $repository->findAll()`.

Nice! With an array of `Article` objects in hand, let's pass those into the template
as a new `articles` variable.

Now, to the template! Open `homepage.html.twig` and scroll down *just* a little
bit. Yes: here is the article list. Well, there's a "main" article, but I'm going
to ignore that for now. Down below, add `for article in articles` then, below,
`endfor`.

Then... just make things dynamic: `article.slug`, `article.title`, and for the
three hours ago, if `article.publishedAt` is not null, print `article.publishedAt|ago`.
If it's *not* published, do nothing. With this in place, delete the last two
hardcoded articles.

## Controlling the ORDER BY

Let's give it a try - find your browser and, refresh! Nice! You can see a mixture
of published and unpublished articles. But... you cal *also* see that the articles
just printed out in whatever order they were created, regardless of the publish
date. That's not... ideal: we need to show the newest articles first.

Ok, head back to `ArticleController`. Hmm... the `findAll()` methods gives us
*everything*... but it's pretty limited. In fact, it takes zero arguments: you
can't control it at *all*.

But, some of the *other* methods *are* just a little bit more flexible. To control
the order, use `findBy()` instead, pass this an empty array, and then another array
with `publishedAt` set to `DESC`.

The first array is where you would normally pass some criteria for a WHERE clause.
If we pass nothing, we get everything!

Try it - refresh! That looks better already.

## Hello ArticleRepository

Except... hmm... it probably does *not* make sense to show the published articles
on the homepage. And *this* is when things get a bit more interesting. Sure, you
can pass simple criteria to `findBy`, like `slug` equal to some value. But, in
this case, we need a query that says `WHERE publishedAt IS NOT NULL`. That's just
not possible with `findBy()`!

And so... for the *first* time, we're going to write... a custom query!

Let me show you something cool: when we originally generated our entity, the command
created the `Article` entity, but it *also* created an `ArticleRepository` class
in the `Repository` directory. Try this: `dump($repository)` and refresh. Guess
what? This is an instance of `ArticleRepository`!

Yes, there is a *connection* between the `Article` and `ArticleRepository` classes.
In fact, that connection is explicitly configured right at the top of our `Article`
class. This says: when we ask for the `Article` class's repository, Doctrine should
give us an instance of this `ArticleRepository` class. Oh, and the built-in `find*`
methods actually come from one of the parent classes of `ArticleRepository`.

So... why the heck are we talking about this? Because, if you want to create a
custom query, you can do that by creating a custom *method* inside of this class.
And, hey! It even has a couple of examples. Uncomment the first example, and rename
it to `findAllPublishedOrderedByNewest()`. I *love* descriptive names... or maybe
I love long names... not sure.

Anyways, it's time to talk about *how* you actually write custom queries in Doctrine.
Let's do that next!