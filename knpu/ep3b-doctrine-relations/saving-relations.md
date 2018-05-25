# Saving Relations

Our `Comment` entity has an `article` property and an `article_id` column in
the database:

[[[ code('c91ede73d9') ]]]

So, the question *now* is: how do we actually *populate* that column? How can we
relate a `Comment` to an `Article`?

The answer is both very easy, and also, quite possibly, at first, weird! Open up
the `ArticleFixtures` class. Let's hack in a new comment object near the bottom:
`$comment1 = new Comment()`:

[[[ code('43f18ead49') ]]]

Then, `$comment1->setAuthorName()`, and we'll go copy our *favorite*, always-excited
astronaut commenter: Mike Ferengi:

[[[ code('c820ae1b90') ]]]

Then, `$comment1->setContent()`, and use one of our hardcoded comments:

[[[ code('3ed410ce41') ]]]

Perfect! Because we're creating this manually, we need to persist it to Doctrine.
At the top, `use` the `$manager` variable:

[[[ code('85ebaa0a2b') ]]]

Then, `$manager->persist($comment1)`:

[[[ code('45c2e230d6') ]]]

If we stop here, this *is* a valid `Comment`... but it is NOT related to *any* article.
In fact, go to your terminal, and try the fixtures:

```terminal
php bin/console doctrine:fixtures:load
```

## JoinColumn & Required Foreign Key Columns

Boom! It fails with an integrity constraint violation:

> Column `article_id` cannot be null

It *is* trying to create the `Comment`, but, because we have not set the relation,
it doesn't have a value for `article_id`:

[[[ code('559ad9ef80') ]]]

Oh, and also, in `Comment`, see this `JoinColumn` with `nullable=false`? That's
the same as having `nullable=false` on a property: it makes the `article_id` column
*required* in the database. Oh, but, for whatever reason, a column *defaults* to
`nullable=false`, and JoinColumn defaults to the opposite: `nullable=true`.

## Setting the Article on the Comment

ANYways, how can we relate this `Comment` to the `Article`? By calling
`$comment1->setArticle($article)`:

[[[ code('dcc8790f6b') ]]]

And that's it! This is both the most wonderful and strangest thing about Doctrine
relations! We do *not* say `setArticle()` and pass it `$article->getId()`. Sure,
it will *ultimately* use the id in the database, but in PHP, we *only* think about
objects: relate the `Article` object to the `Comment` object.

Once again, Doctrine wants you to pretend like there is *no* database behind the
scenes. Instead, all *you* care about is that a `Comment` object is related to an
`Article` object. You expect Doctrine to figure out how to save that.

Copy that entire block, paste, and use it to create a second comment to make things
a bit more interesting: `$comment2`. Copy a different dummy comment and paste that
for the content:

[[[ code('2e13d2e2c5') ]]]

And *now*, let's see if it works! Reload the fixtures:

```terminal
php bin/console doctrine:fixtures:load
```

No errors! Great sign! Let's dig into the database:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM comment'
```

There it is! We have 20 comments: 2 for each article. And the `article_id` for each
row is set!

This is the *beauty* of Doctrine: *we* relate objects in PHP, never worrying about
the foreign key columns. But of course, when we save, it stores things exactly like
it should.

Next, let's learn how to *fetch* related data, to get all of the comments for a
specific `Article`.
