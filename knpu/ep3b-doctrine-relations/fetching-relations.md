# Fetching Relations

Yes! Each `Article` is now related to *two* comments in the database. So, on the
article show page, it's time to get rid of this hardcoded stuff and, finally,
query for the *true* comments for this `Article`.

In `src/Controller`, open `ArticleController` and find the `show()` action:

[[[ code('13dd06a278') ]]]

This renders a single article. So, how can we find *all* of the comments related
to this article? Well, we *already* know *one* way to do this.

Remember: whenever you need to run a query, step one is to get that entity's
repository. And, surprise! When we generated the `Comment` class, the `make:entity`
command *also* gave us a new `CommentRepository`:

[[[ code('7c291cdfb3') ]]]

Thanks MakerBundle!

Get the repository by adding a `CommentRepository` argument. Then, let's see,
could we use one of the built-in methods? Try `$comments = $commentRepository->findBy()`,
and pass this `article` set to the entire `$article` object:

[[[ code('cbb0eb82dd') ]]]

Dump these comments and die:

[[[ code('203d5f4fa1') ]]]

Then, find your browser and, try it! Yes! It returns the *two* `Comment` objects
related to this Article!

So, the weird thing is that, once again, you need to stop thinking about the *columns*
in your tables, like `article_id`, and only think about the *properties* on your
entity classes. That's why we use `'article' => $article`. Of course, behind the
scenes, Doctrine will make a query where `article_id` = the id from this `Article`.
But, in PHP, we think *all* about objects.

## Fetching Comments Directly from Article

As nice as this was... there is a *much* simpler way! When we generated the
relationship, it asked us if we wanted to add an optional `comments` property
to the `Article` class, for convenience. We said yes! And thanks to that, we can
literally say `$comments = $article->getComments()`. Dump `$comments` again:

[[[ code('7fc7bb30b2') ]]]

Oh, and *now*, we don't need the `CommentRepository` anymore:

[[[ code('0502f859a3') ]]]

Cool.

## Lazy Loading

Head back to your browser and, refresh! It's the *exact* same as before. Wait, what?
What's this weird `PersistentCollection` thing?

Here's what's going on. When Symfony queries for the `Article`, it *only* fetches
the `Article` data: it does *not* automatically fetch the related Comments. And,
for performance, that's great! We may not even *need* the comment data! But, as
*soon* as we call `getComments()` and start using that, Doctrine makes a query in
the background to go get the comment data.

This is called "lazy loading": related data is not queried for until, and unless,
we use it. To make this magic possible, Doctrine uses this `PersistentCollection`
object. This is *not* something you need to think or worry about: this object looks
and acts like an array.

To prove it, let's foreach over `$comments as $comment` and dump each `$comment`
inside. Put a `die` at the end:

[[[ code('6630679d6f') ]]]

Try it again! Boom! Two `Comment` objects!

## Fetching the Comments in the Template

Back in the controller, we *no* longer need these hard-coded comments. In fact,
we don't even need to pass `comments` into the template at all! That's because
we can call the `getComments()` method directly from Twig!

Remove *all* of the comment logic:

[[[ code('ab9f81ad85') ]]]

And then, jump into `templates/article/show.html.twig`. Scroll down a little...
ah, yes! First, update the count: `article.comments|length`:

[[[ code('62292711bc') ]]]

Easy! Then, below, change the loop to use `for comment in article.comments`:

[[[ code('e8ea705a12') ]]]

And because each comment has a dynamic author, print that with `{{ comment.authorName }}`.
And the content is now `comment.content`:

[[[ code('180a30d970') ]]]

Oh, and, because each comment has a `createdAt`, let's print that too, with our
trusty `ago` filter:

[[[ code('f9945e776b') ]]]

Love it! Let's try it! Go back, refresh and... yes! Two comments, from about
17 minutes ago. And, check this out: on the web debug toolbar, you can see that
there are *two* database queries. The first query selects the `article` data only.
And the *second* selects all of the *comment* data where `article_id` matches
this article's id - 112. This second query doesn't actually happen *until* we reference
the comments from inside of Twig:

[[[ code('30ebc68634') ]]]

That laziness is a *key* feature of Doctrine relations.

Next, it's time to talk about the *subtle*, but super-important distinction between
the *owning* and *inverse* sides of a relation.
