# Awesome Random Fixtures

Look at `ArticleFixtures`: we created 10 articles. So, the system has references
from 0 to 9:

[[[ code('9c6574b152') ]]]

In `CommentFixture`, spice things up: replace the 0 with `$this->faker->numberBetween(0, 9)`:

[[[ code('27e8e2f83a') ]]]

Try the fixtures again:

```terminal-silent
php bin/console doctrine:fixtures:load
```

No errors! And... check the database:

```terminal-silent
php bin/console doctrine:query:sql 'SELECT * FROM comment'
```

That is *much* better! Just like that, each comment is related to a random article!

## Making this Random Reference System Reusable

I really like this idea, where we can fetch random objects in our fixtures. So,
let's make it easier! In `BaseFixture`, add a new private property on top called
`$referencesIndex`. Set that to an empty array:

[[[ code('21b84581fd') ]]]

I'm adding this because, at the bottom of this class, I'm going to paste in a new,
method that I prepared. It's a little ugly, but this new `getRandomReference()` does
exactly what its name says: you pass it a class, like the `Article` class, and it
will find a random Article for you:

[[[ code('4640554e87') ]]]

That's super friendly!

In `CommentFixture`, use it: `$comment->setArticle()` with
`$this->getRandomReference(Article::class)`:

[[[ code('4af1a544ea') ]]]

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

There is *one* last minor problem with our fixtures... they only work due to pure
luck. Check this out: I'll right click on `CommentFixture` and rename the class
to `A0CommentFixture`:

[[[ code('1ee20bc966') ]]]

Also allow PhpStorm to rename the file. Some of you *might* already see the problem.
Try the fixtures now:

```terminal-silent
php bin/console doctrine:fixtures:load
```

Bah! Explosion!

> Cannot find any references to `App\Entity\Article`

The error comes from `BaseFixture` and it basically means that *no* articles have
been set into the reference system yet!

[[[ code('00ae1a5ad6') ]]]

You can see the problem in the file tree. We have *not* been thinking *at all* about
what *order* each fixture class is executed. By default, it loads them alphabetically.
But now, this is a problem! The `A0CommentFixture` class is being loaded *before*
`ArticleFixtures`... which totally ruins our cool system!

You can also see this in the terminal: it loaded `A0CommentFixture` first.

## DependentFixtureInterface

The solution is pretty cool. As *soon* as you have a fixture class that is
*dependent* on *another* fixture class, you need to implement an interface called
`DependentFixtureInterface`:

[[[ code('6147508418') ]]]

This will require you to have one method. Move to the bottom, then, go to the
"Code" -> "Generate" menu, or `Command` + `N` on a Mac, select "Implement Methods"
and choose `getDependencies()`. I'll add the `public` before the function:

[[[ code('76c463f682') ]]]

Just return an array with `ArticleFixtures::class`:

[[[ code('130e254ae3') ]]]

That's it! Load them again:

```terminal-silent
php bin/console doctrine:fixtures:load
```

Bye bye error! It loaded `ArticleFixtures` *first* and then the comments below that.
The fixtures library looks at all of the dependencies and figures out an order
that makes sense.

With that fixed, let's rename the class *back* from this ridiculous name to
`CommentFixture`:

[[[ code('c4ae6af9c0') ]]]

To celebrate, move over, refresh and... awesome! 8, random comments. We rock!

Next, let's learn about some tricks to control *how* Doctrine fetches the
comments for an article, like, their *order*.
