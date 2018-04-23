# Saving Entities

Put on your publishing hat, because it's time to write some thoughtful space articles
and insert some rows into our article table! And, good news! This is probably one
of the *easiest* things to do in Doctrine.

Let's create a new controller called `ArticleAdminController`. We'll use this as
a place to add new articles. Make it extend the normal `AbstractController`:

[[[ code('b123b1ce54') ]]]

And create a `public function new()`:

[[[ code('55e81a290d') ]]]

Above, add the `@Route()` - make sure to auto-complete the one from Symfony
`Components` so that PhpStorm adds the `use` statement. For the URL, how
about `/admin/article/new`:

[[[ code('c9d32dada6') ]]]

We're not *actually* going to build a real page with a form here right now.
Instead, I just want to write some code that saves a dummy article to the database.

But first, to make sure I haven't screwed anything up, return a new `Response`:
the one from `HttpFoundation` with a message:

> space rocks... include comets, asteroids & meteoroids

[[[ code('1ef7fa74d2') ]]]

Now, we *should* be able to find the browser and head to `/admin/article/new`. Great!

## Creating the Article Object

So, here's the big question: *how* do you save data to the database with Doctrine?
The answer... is beautiful: just create an `Article` object with the data you need,
then ask Doctrine to put it into the database.

Start with `$article = new Article()`:

[[[ code('ea6bc3d33d') ]]]

For this article's data, go back to the "Why Asteroids Taste like Bacon" article:
we'll use this as our dummy news story. Copy the article's title, then call
`$article->setTitle()` and paste:

[[[ code('0bdba95160') ]]]

This is one of the setter methods that was automatically generated into our entity:

[[[ code('dd7f7f0736') ]]]

Oh, and the generator *also* made all the setter methods return `$this`, which means
you can chain your calls, like: `->setSlug()`, then copy the last part of the
URL, and paste here. Oh, but we need to make sure this is unique... so just add
a little random number at the end:

[[[ code('4286708e3a') ]]]

Then, `->setContent()`. And to get this, go back to `ArticleController`, copy
*all* of that meaty markdown and paste here. Ah, make sure the content is completely
*not* indented so the multi-line text works:

[[[ code('5a24274be8') ]]]

Much better! The last field is `publishedAt`. To have more interesting data, let's
only publish *some* articles. So, if a random number between 1 to 10 is greater than
2, publish the article: `$article->setPublishedAt()` with `new \DateTime()` and
`sprintf('-%d days')` with a bit more randomness: 1 to 100 days old:

[[[ code('97ba922c01') ]]]

Perfect! Now... stop. I want you to notice that *all* we've done is create an
`Article` object and set data on it. This is normal, boring, PHP code: we're not
using Doctrine at *all* yet. That's really cool.

## Saving the Article

To *save* this, we just need to find Doctrine and say:

> Hey Doctrine! Say hi to Jon Wage for us! Also, can you please save this
> article to the database. You're the best!

How do we do this? In the last Symfony tutorial, we talked about how the *main* thing
that a bundle gives us is more *services*. DoctrineBundle gives us one, *very* important
service that's used for both saving to *and* fetching from the database. It's called
the DeathStar. No, no, it's the EntityManager. But, missed opportunity...

Find your terminal and run:

```terminal
php bin/console debug:autowiring
```

Scroll to the the top. There it is! `EntityManagerInterface`: that's the type-hint
we can use to fetch the service. Go back to the top of the `new()` method and add
an argument: `EntityManagerInterface $em`:

[[[ code('721727caa7') ]]]

Now that we have the all-important entity manager, saving is a two-step process...
and it *may* look a bit weird initially. First, `$em->persist($article)`, then
`$em->flush()`:

[[[ code('8564da411b') ]]]

It's *always* these two lines. Persist simply says that you would *like* to save
this article, but Doctrine does *not* make the INSERT query yet. That happens when
you call `$em->flush()`. Why two separate steps? Well, it gives you a bit more
flexibility: you could create ten Article objects, called `persist()` on each, then
`flush()` just *one* time at the end. This helps Doctrine optimize saving those
ten articles.

At the bottom, let's make our message a bit more helpful, though, I thought my message
about space rocks was *at least* educational. Set the article id to some number and
the slug to some string. Pass: `$article->getId()` and `$article->getSlug()`:

[[[ code('3a9fdb6583') ]]]

Oh, and this is important: *we* never set the id. But when we call `flush()`, Doctrine
will insert the new row, get the new id, and put that onto the `Article` *for*
us. By the time we print this message, the Article will have its new, fancy id.

Ok, are you ready? Let's try it: go back to `/admin/article/new` and... ha! Article
id 1, then 2, 3, 4, 5, 6! Our news site is alive!

If you want to be *more* sure, you can check this in your favorite database tool
like phpMyAdmin or whatever the cool kids are using these days. *Or*, you can use
a helpful console command:

```terminal
php bin/console doctrine:query:sql "SELECT * FROM article"
```

This is `article` with a *lowercase* "a", because, thanks to the default configuration,
Doctrine creates snake case table and column names.

And... yes! There are the new, 6 results.

We have successfully put stuff *into* the database! Now it's time to run some
queries to fetch it back out.
