# All about Entity Repositories

With the article show page now dynamic, let's turn to the homepage... cause these
news stories are *totally* still hardcoded. Open `ArticleController` and find the
`homepage()` action:

[[[ code('35ee907ee8') ]]]

Perfect. *Just* like before, we need to query for the articles. This means that we need
an `EntityManagerInterface $em` argument:

[[[ code('de8e17ce74') ]]]

Next, we get the *repository* for the class: `$repository = $em->getRepository(Article::class)`.
And *then* we can say, `$articles = $repository->findAll()`:

[[[ code('c8aa30b3ab') ]]]

Nice! With this array of `Article` objects in hand, let's pass those into the template
as a new `articles` variable:

[[[ code('dd76d0935a') ]]]

Now, to the template! Open `homepage.html.twig` and scroll down *just* a little
bit. Yes: here is the article list:

[[[ code('60a123237d') ]]]

Well, there's a "main" article on top, but I'm going to ignore that for now. Down
below, add `for article in articles` with, at the bottom, `endfor`:

[[[ code('521941d025') ]]]

Then... just make things dynamic: `article.slug`, `article.title`, and for the
three hours ago, if `article.publishedAt` is not null, print `article.publishedAt|ago`.
If it's *not* published, do nothing. With this in place, delete the last two
hardcoded articles:

[[[ code('9530648a28') ]]]

## Controlling the ORDER BY

Let's give it a try: find your browser and, refresh! Nice! You can see a mixture
of published and unpublished articles. But... you can *also* see that the articles
just printed out in whatever order they were created, *regardless* of the publish
date. Space travellers demand fresh content! So let's print the *newest* articles
first.

Head back to `ArticleController`. Hmm... the `findAll()` methods gives us
*everything*... but it's pretty limited. In fact, it takes zero arguments: you
can't control it at *all*:

[[[ code('59c2bd7dcf') ]]]

But, some of the *other* methods *are* just a little bit more flexible. To control
the order, use `findBy()` instead, pass this an empty array, and then another array
with `publishedAt` set to `DESC`:

[[[ code('99806c72e9') ]]]

The first array is where you would normally pass some criteria for a WHERE clause.
If we pass nothing, we get everything!

Try it - refresh! Much better!

## Hello ArticleRepository

Except... hmm... it probably does *not* make sense to show the unpublished articles
on the homepage. And *this* is when things get a bit more interesting. Sure, you
can pass simple criteria to `findBy()`, like `slug` equal to some value. But, in
this case, we need a query that says `WHERE publishedAt IS NOT NULL`. That's just
*not* possible with `findBy()`!

And so... for the *first* time, we're going to write - drumroll - a custom query!

Let me show you something cool: when we originally generated our entity, the command
created the `Article` class, but it *also* created an `ArticleRepository` class
in the `Repository` directory. Try this: `dump($repository)` and, refresh. Guess what?
This is an instance of that `ArticleRepository`!

Yes, there is a *connection* between the `Article` and `ArticleRepository` classes.
In fact, that connection is explicitly configured right at the top of your `Article`
class:

[[[ code('286d3aa4f4') ]]]

This says: when we ask for the `Article` class's repository, Doctrine should
give us an instance of this `ArticleRepository` class:

[[[ code('5511b223ca') ]]]

Oh, and the built-in `find*()` methods actually come from one of the parent classes
of `ArticleRepository`.

So... why the heck are we talking about this? Because, if you want to create a
custom query, you can do that by creating a custom *method* inside of this class.
And, hey! It even has a couple of examples:

[[[ code('4ef485c877') ]]]

Uncomment the first example, and rename it to `findAllPublishedOrderedByNewest()`:

[[[ code('72b25060ad') ]]]

I *love* descriptive names... or maybe I love *long* names... not sure.

Anyways, it's time to talk about *how* you actually write custom queries in Doctrine.
Let's do that next!
