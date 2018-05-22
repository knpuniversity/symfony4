# Fixture References & Relating Objects

Having just *one* fixture class that loads articles *and* comments... and eventually
other stuff, is not super great for organization. Let's give the comments their *own*
home. First, delete the comment code from here. Then, find your terminal and run

```terminal
php bin/console make:fixtures
```

Call it `CommentFixture`.

Flip back to your editor and open that file! In the last tutorial, we made a cool
base class with some extra shortcuts. Extend `BaseFixture`. Then, instead of `load`,
we *now* need `loadData()`, and it should be protected. Remove the extra `use` statement
on top.

Thanks to our custom base class, we can create a *bunch* of comments easily with
`$this->createMany()`, passing it `Comment::class`, 100, and then a callback that
will receive each 100 `Comment` objects.

Inside, let's use Faker - which we also setup in the last tutorial - to give us
awesome, fake data. Start with `$comment->setContent()`. I'll use multiple lines.
Now, if `$this->faker->boolean`, which will be a random true or false, then either
generate a random paragraph: `$this->faker->paragraph`, or generate two random sentences.
Pass `true` to get this as text, not an array.

Cool! Next, for the author, we can use `$comment->setAuthor()` with
`$this->faker->name`, to get a random person's name. By the way, *all* of these
faker functions are covered really well in their docs. I'm seriously not just making
them up.

Finally, add `$comment->setCreatedAt()` with `$this->faker->dateTimeBetween()`
from `-1 months` to `-1 seconds`. That'll give us *much* more interesting data.

## Using the Reference System

At this point, this *is* a valid `Comment` object... we just haven't related it to
an `Article` yet. We know *how* to do this, but... the problem is that all of
the articles are created in a totally different fixture class. How can we get access
to them here?

Well, one solution would be to use the entity manager, get the `ArticleRepository`,
and run some queries to fetch out the articles.

But, that's kinda lame. So, there's an easier way. Look again at the `BaseFixture`
class, specifically, the `createMany()` method. It's fairly simple, but it *does*
have one piece of magic: it calls `$this->addReference()` with a key, which is the
entity class name, an underscore, then an integer that starts at zero and counts
up for each loop. For the second argument, it passes the object itself.

This reference system is a little "extra" built into Doctrine's fixtures library.
When you add a "reference" from one fixture class, you can fetch it out in *another*
class. It's *super* handy when you need to relate entities. And hey, that's *exactly*
what we're trying to do!

Inside `CommentFixture`, add `$comment->setArticle()`, with `$this->getReference()`
and pass it one of those keys: `Article::class`, then `_0`. PhpStorm is complaining
about a type-mismatch, but this will totally work.

Try it! Find your terminal and run:

```terminal
php bin/console doctrine:fixtures:load
```

No errors! That's a great sign! Check out the database:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM comment'
```

Yes! 100 comments, and each is related to the exact same article.

## Relating to Random Articles

So, success! Except that this isn't very interesting yet. *All* our comments are
related to the *same* one article? Come on!

Let's spice things up by relating each comment to a random article. *And*, learn
about when we need to implement a `DependentFixtureInterface`.
