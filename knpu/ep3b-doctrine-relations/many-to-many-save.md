# Saving a ManyToMany Relation + Joins

We *now* know that a ManyToMany relationship works with the help of a join table.
The question now is: how can we insert new records *into* that join table? How can
I relate an `Article` to several tags?

The answer is the *exactly* the same as our ManyToOne relation. Start by opening
`BaseFixture`. At the bottom, I'm going to paste in a new `protected function`
called `getRandomReferences()`. We already have and `getRandomReference()` method
that returns just *one* object. This is the same, but you can pass it a class name
and how *many* of those objects you want back. The objects you get back may or
may not be a unique set - the method isn't perfect, but, it's good enough.

Next, in `ArticleFixtures`, *this* is where we'll set the relationship. And *that*
means, we need to make sure that `TagFixture` is loaded first so that can reference
those tags here. At the top, add `implements DependentFixtureInterface`. Then,
I'll go to the Code -> Generate menu - or Command + N on a Mac - select
"Implement Methods" and choose `getDependencies()`. We now depend on `TagFixture::class`.

Above, let's *first* get some tag objects: `$tags = $this->getRandomReferences()`
and pass it `Tag::class`, and then, let's fetch `$this->faker->numberBetween()`
zero and five. So, find 0 to 5 random tags.

And just to make sure you that this *does* give us `Tag `objects, `dump($tags)`
and die. Then find your terminal and run:

```terminal
php bin/console doctrine:fixtures:load
```

## Explaining Proxies

Perfect! These *are* `Tag` *objects*. Oh, by the way, sometimes you may notice
that your entity's class name is prefixed with this weird `Proxies` stuff. When
you see that, just ignore it. A "Proxy" is a special class that Doctrine generates
and sometimes wraps *around* your real object. Doctrine does that so that it can
do its relationship lazy-loading magic.

Actually, check this out: it *looks* like all the data on this `Tag` is null! But,
that's not true. As *soon* as you reference some data on that `Tag`, Doctrine will
query for the data and fill it in. That's lazy-loading in action.

Let me show you: add a `foreach` over `$tags as $tag`. To help PhpStorm, I'll add
some inline PHPDoc to tell it that `$tags` is an array of `Tag` objects.

Inside the loop, just say `$tag->getName()`. I know, that looks weird: we're calling
a method but not using it! But, calling this method should be enough to trigger
Doctrine to go query for this tag's real data. Below, `dump($tag)` and `die` after
the loop.

Load the fixtures again:

```terminal-silent
php bin/console doctrine:fixtures:load
```

Boom! We have data!

Anyways, this is the proxy system in action. You need to know what it is so because
you *will* see it from time-to-time. But mostly, it should be completely invisible:
don't think about it.

## Adding Tags to Article

Finally, how can we add each `Tag` to the `Article`? No surprise, it's
`$article->addTag()`. Try the fixtures again:

```terminal-silent
php bin/console doctrine:fixtures:load
```

Ok, no errors. To the database!

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM tag'
```

Yep, 10 tags with various, weird names. Now look for:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM article_tag'
```

Let's see what the join table looks like. Yea! 24 rows! *Each* time we add a
Tag to Article and save, Doctrine *inserts* a row in this table. If we were to
*remove* an existing Tag from an Article object with `$article->removeTag()` and
thne flush, Doctrine would actually *delete* that row. For the first, and only,
time, we have a table that we don't need to think about, at *all*: Doctrine inserts
and deletes data for us.

All *we* need to do is worry about relating `Article` objects to `Tag` objects.
Doctrine takes care of the saving.

## Rendering the Tags

And *now* we can turn back to building our site: open the `article/show.html.twig`
template. On this page, let's print the tags right under the article title. So,
scroll down a bit. Copy the span for the heart count and paste it below.

Because the `Article` object holds an array of tags, use `for tag in article.tags`.
Inside, let's create a cute little badge and print the tag name: `{{ tag.name }}`.

That's *super* easy. Try it: find the page and refresh. We got it! Well, this
`Article` only has one tag - boring. Find a different one: boom! Four different tags.

Let's repeat this on the homepage: we'll list the tags right under the title. Copy
the `for` loop, then open `homepage.html.twig`. Down below, add a `<br>`, then
paste! Wrap this in a `<small>` tag and change the class to `badge-light`.

So, this is just the same thing again: we have an `article` variable, so we can
loop over its tags.

But notice, *before* we refresh, there are 8 queries. But after, there are 15!
The page works, but we have another N+1 query problem. And, it's probably no big
deal, but let's learn how to JOIN in a ManyToMany query so we can fix it.