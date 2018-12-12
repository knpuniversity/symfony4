# Querying for Data!

Hey! There are rows in our article table! So let's update the news page to *not*
show this hard-coded article, but instead to query the database and print *real*,
dynamic data.

Open `ArticleController` and find the `show()` method:

[[[ code('15a764e338') ]]]

This renders that page. As I mentioned earlier, DoctrineBundle gives us one
service - the EntityManager - that has the power to save *and* fetch data.
Let's get it here: add another argument: `EntityManagerInterface $em`:

[[[ code('d936debcf6') ]]]

When you want to *query* for data, the first step is always the same: we need to
get the *repository* for the entity: `$repository = $em->getRepository()` and then
pass the entity class name: `Article::class`:

[[[ code('48a0d2105d') ]]]

This repository object knows *everything* about how to query from the `article` table.
We can use it to say `$article = $repository->`. Oh, nice! It has some built-in
methods, like `find()` where you can pass the `$id` to fetch a single article. Or,
`findAll()` to fetch *all* articles. With the `findBy()` method, you can fetch
*all* articles where a field matches some value. And `findOneBy()` is the same, but
only returns *one* Article. Let's use that: `->findOneBy()` and pass it an array
with `'slug' => $slug`:

[[[ code('673150982e') ]]]

This will fetch *one* row where the `slug` field matches this value. These built-in
find methods are nice... but they can't do much more than this. But, don't worry!
We will *of course* learn how to write custom queries soon.

Above this line, just to help my editor, I'll tell it that this is an `Article`
object:

[[[ code('5514ee0239') ]]]

And... hold on, that's important! When you query for something, Doctrine
returns *objects*, not just an associative arrays with data. That's really the whole
point of Doctrine! You need to stop thinking about inserting and selecting rows in
a database. Instead, think about saving and fetching *objects*... almost as if
you didn't know that a database was behind-the-scenes.

## Handling 404's

At this point, it's *possible* that there is *no* article in the database with
this slug. In that case, `$article` will be `null`. How should we handle that? Well,
in the real world, this should trigger a 404 page. To do that, say if `!$article`,
then, `throw $this->createNotFoundException()`. Pass a descriptive message, like:
`No article for slug "%s"` and pass `$slug`:

[[[ code('763ddb416c') ]]]

I want to dig a *little* bit deeper to see how this work. Hold `Command` on a Mac -
or `Ctrl` otherwise - and click this method. Ah, it comes from a `trait` that's used
by the base `AbstractController`. Fascinating! It just throws an exception!

In Symfony, to trigger a 404, you just need to throw this very special exception
class. That's why, in the controller, we *throw* `$this->createNotFoundException()`.
The message can be as descriptive as possible because it will only be shown to
you: the developer.

After all of this, let's `dump()` the `$article` to see what it looks like and `die`:

[[[ code('55f31337fa') ]]]

Head back to your browser and first, refresh. Ok! *This* is the 404 page: there's
nothing in the database that matches this slug: all the *real* slugs have a random
number at the end. *We* see the helpful error message because *this* is what the
404 page looks like for *developers*. But of course, when you switch into the `prod`
environment, your users will see a different page that you can customize.

We're not going to talk about *how* to customize error pages... because it's super
friendly and easy. Just Google for "Symfony customize error pages" and... have fun!
You can create separate pages for 404 errors, 403 errors, 500 errors, or whatever
your heart desires.

To find a *real* slug, go back to `/admin/article/new`. Copy that slug, go back,
paste it and... it works! There is our *full*, *beautiful*, well-written, inspiring,
Article object... with fake content about meat. Having an *object* is *awesome*!
We are now... dangerous.

## Rendering the Article Data: Twig Magic

Back in the controller, remove the `dump()`:

[[[ code('6d9a351f51') ]]]

Keep the hardcoded comments for now. But, remove the `$articleContent`:

[[[ code('c1459471c7') ]]]

Let's also remove the markdown parsing code and the now-unused argument:

[[[ code('db11e84927') ]]]

We'll process the markdown in the template in a minute: Back down at `render()`,
instead of passing `title`, `articleContent` and `slug`, *just* pass `article`:

[[[ code('46d8b5917d') ]]]

Now, open that template! With the Symfony plugin, you can cheat and hold `Command`
or `Ctrl` and click to open it. Or, it's just in `templates/article`.

Updating the template is a *dream*. Instead of `title`, print `article.title`:

[[[ code('29995a567c') ]]]

Oh, and in *many* cases... but not always... you'll get auto-completion based on the
methods on your entity class!

But look closely: it's auto-completing `getTitle()`. But when I hit tab, it just
prints `article.title`. Behind the scenes, there is some serious Twig magic happening.
When you say `article.title`, Twig first looks to see if the class has a `title`
*property*:

[[[ code('78d547876d') ]]]

It does! But since that property is *private*, it can't use it. No worries! It then
looks for a `getTitle()` method. And because *that* exists:

[[[ code('8748f2438d') ]]]

It calls it and prints that value.

This is *really* cool because our template code can be simple: Twig figures out
what to do. If you were printing a boolean field, something like `article.published`,
Twig would also look for `isPublished()` a `hasPublished()` methods. *And*, if `article`
were an array, the dot syntax would just fetch the keys off of that array. Twig:
you're the bomb.

Let's update a few more places: `article.title`, then, `article.slug`, and
finally, for the content, `article.content`, but then `|markdown`:

[[[ code('29d27ecf56') ]]]

The KnpMarkdownBundle gives us a `markdown` filter, so that we can just process it right
here in the template.

Ready to try it? Move over, deep breath, refresh. Yes! It works! Hello dynamic title!
Hello dynamic bacon content!

## See your Queries in the Profiler

Oh, and I have a *wonderful* surprise! The web debug toolbar now has a database icon
that tells us how many database queries this page executed and how long they took.
But wait, there's more! Click the icon to go into the profiler. Yes! This actually
*lists* every query. You can run "EXPLAIN" on each one or view a runnable query.
I use this to help debug when a particularly complex query isn't returning the
results I expect.

So, um, yea. This is awesome. Next, let's take a quick detour and have some fun
by creating a custom Twig filter with a Twig extension. We need to do this, because
our markdown processing is *no* longer being cached. Boo.
