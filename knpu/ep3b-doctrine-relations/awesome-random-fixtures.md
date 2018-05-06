# Awesome Random Fixtures

Having just *one* fixture class that loads articles *and* comments... and eventually
other stuff, is not *great* for organization. Let's give the comments their *own*
home. First, delete the comment code from here. Then, find your terminal and run

```terminal
php bin/console make:fixtures
```

Call it `CommentFixture`.

Flip back to your editor, and open that up! In the last tutorial, we made a cool
base class with some extra shortcuts. Extend `BaseFixture`.  Then, instead of `load`,
we now need `loadData()`, and it should be protected. Remove the extra `use` statement
on top.

Thanks to our custom base class, we can create a *bunch* of comments easily with
`$this->createMany()`, passing it `Comment::class`, create 100 comments, and then
a callback that will receive each 100 `Comment` objects.

Inside, let's use Faker - which we also setup in the last tutorial - to give us
awesome, fake data. Start with `$comment->setContent()`. I'll use multiple lines.
Now, if `$this->faker->boolean`, which will be a random true or false, then either generate
a random paragraph: `$this->faker->paragraph`, or generate a two reandom sentences,
and pass `true` to get this as text, not an array.

Cool! Next, for the author, we can use `$comment->setAuthor()` with
`$this->faker->name`, to get a random person's name. By the way, *all* of these
faker functions are covered really well in their docs. I promise I'm not making
them up.

Finally, add `$comment->setCreatedAt()` with `$this->faker->dateTimeBetween()`
from `-1 months` to `-1 seconds`. That'll give us *much* more interesting data.

## Using the Reference System

At this point, this *is* a valid `Comment` object. We just haven't related it to
an `Article` yes. We know *how* to do this, but... the problem is that all of
the articles are created in a totally different fixture class. So, how can we
get access to them?

Well, one solution would be to use the entity manager, fetch the `ArticleRepository`,
and run some queries to fetch out the articles.

But, there's an easier way. Look again at the `BaseFixture` class, specifically,
the `createMany()` method. It's fairly simple, but it *does* have one piece of
magic: it calls `$this->addReference()` with a key, which is the entity class name,
an underscore, then an integer that starts at zero and counts up for each loop.
For the second argument, it passes the object itself.

This reference system is a little "extra" built into DoctrineFixture. When you add
a "reference" from one fixture class, you can fetch it out in *another* class.
It's *super* handy when you need to relate entities to each other.

For example, inside `$comment->setArticle()`, use `$this->getReference()` and pass
it once of those keys: `Article::class`, then `_0`. PhpStorm is complaining about
a type-mismatch, but this will totally work.

Try it! Find your terminal and run:

```terminal
php bin/console doctrine:fixtures:load
```

No errors! That's a great sign! Check out the database:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM comment'
```

Yes! 100 comments, and each relates to the exact same article.

## Relating to Random Articles

So, success! Except that this isn't very interesting yet. *All* our comments are
related ot the *same* one article? Come on! That's lame!

Look at `ArticleFixtures`: we created 10 articles. So, the system has references
from 0 to 9. In `CommentFixture`, spice things up a bit: replace the 0 with
`$this->faker->numberBetween(0, 9)`. Try the fixtures again:

```terminal-silent
php bin/console doctrine:fixtures:load
```

No errors! Check the database:

```terminal-silent
php bin/console doctrine:query:sql 'SELECT * FROM comment'
```

That is *much* better! The comments are all related to random articles.

## Making this Random Reference System Reusable

I really liked this idea, where we can fetch random objects in our fixtures. So,
let's make it easier! In `BaseFixture`, add a new private property on top called
`$referencesIndex`. Set that to an empty array.

I'm adding this because, at the bottom of this class, I'm going to paste in a new,
method that I prepared. It's a little ugly, but this new `getRandomReference()` does
exactly what its name says: you pass it a class, like the `Article` class, and it
will find a random Article for you.

In `CommentFixture`, use it: `$comment->setArticle()` with
`$this->getRandomReference(Article::class)`.

To make sure my function works, try the fixtures one last time:

```terminal-silent
php bin/console doctrine:fixtures:load
```

And, query for the comments:

```terminal-silent
php bin/console doctrine:query:sql 'SELECT * FROM comment'
```

Brilliant!

## Fixture Ordering

There is *one* last minor issue with our fixtures. Check this out: I'll right click
on `CommentFixture` and rename the class to `A0CommentFixture`. Also let PhpStorm
rename the file. Some of you *might* already see the problem. Try the fixtures now:

```terminal-silent
php bin/console doctrine:fixtures:load
```

Bah! An explosion! Cannot find any references to App\Entity\Article.

The error comes from `BaseFixture` and it basically means that *no* articles have
been set into the reference system yet! You can see the problem in the file
tree. We have *not* been thinking *at all* about what *order* each fixture class
is executed. By default, it loads them alphabetically. But now, this is a problem!
The `A0CommentFixture` class is being loaded *before* `ArticleFixtures`... which
ruins everything!

You can even see this in the terminal: it loaded `A0CommentFixture` first.

## DependentFixtureInterface

The solution is pretty cool. As *soon* as you have a fixture class that is
*dependent* on one or more *other* fixture classes, you need to implement a new
interface called `DependentFixtureInterface`. This will require you to have one
method. Move to the bottom, then, go to the Code -> Generate menu, or Command+N
on a Mac, select "Implement Methods" and choose `getDependencies()`. I'll add
the `public` before the function. Just return an array with `ArticleFixture::class`.

That's it! Try the fixtures again:

```terminal-silent
php bin/console doctrine:fixtures:load
```

Bye bye error! It loaded `ArticleFixtures` *first* and then the comments below that.
The fixtures library looks at all of the dependencies and figures out an order
that makes sense.

With that fixed, let's rename the class *back* from this ridiculous name to
`CommentFixture`.

To celebrate, move over, refresh and... awesome! 10, random comments. We rock!

Next, let's learn about some tricks to control *how* Doctrine fetches the
comments for an article.
