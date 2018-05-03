# Saving Relations

Our `Comment` entity has an `article` property and an `article_id` column in
the database. So, the question is: how do we actually *populate* that column? How
can we relate a `Comment` to an `Article`?

The answer is both very easy, and also, maybe a little weird at first. Open up
the `ArticleFixtures` class. Let's hack in a new comment object near the bottom:
`$comment1 = new Comment()`. Then, `$comment1->setAuthorName()`, and we'll go copy
our favorite person: Mike Ferengi. And, `$comment1->setContent()`, and use one of
our hardcoded comments.

Perfect! Because we're creating this manually, we need to persist it to Doctrine.
At the top, `use` the `$manager` variable. Then, `$manager->persist($comment1)`.

If we stop here, this *is* a valid `Comment`... but it is NOT related to *any* article.
In fact, go to your terminal, and try the fixtures:

```terminal
php bin/console doctrine:fixtures:load
```

## JoinColumn & Required Foreign Key Columns

Boom! It fails with an integrity constraint violation: `article_id` cannot be null.
It *is* trying to create the `Comment`, but, because we haven't set the relation,
it doesn't have a value for `article_id`.

Oh, and also, in `Comment`, see this `JoinColumn` with `nullable=false`? That's
the same as having `nullable=false` on a column: it makes the `article_id` *required*
in the database. Oh, but, for whatever reason, columns *default* to `nullable=false`,
and JoinColumn's default to the opposite: `nullable=true`.

## Setting the Article on the Comment

ANYways, how can we relate this `Comment` to the `Article`? By calling
`$comment1->setArticle($article)`.

And that's it! This is both the most wonderful and weirdest thing about Doctrine
relations! We do *not* say `setArticle()` and pass it `$article->getId()`. Sure,
that it will *ultimately* use the id in the database, but in PHP, we *only* think
about objects: relate the `Article` object to the `Comment` object.

Once again, Doctrine wants you to pretend like there is *no* database behind-the-scenes.
Instead, all *you* care about is that a `Comment` object is related to an `Article`
object. You expect Doctrine to figure out the details about how to safe that.

Copy that entire block, paste, and let's create a second comment so things are a
bit more interesting. Copy a different dummy comment and past that for the content.
And *now*, let's see if it works! Reload the fixtures:

```terminal-silent
php bin/console doctrine:fixtures:load
```

No errors! That's always good. Let's look into the database:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM comment'
```

There it is! We have 20 comments: 2 for each article. And the `article_id` for each
row is set!

This is the *beauty* of Doctrine: *we* relate objects in PHP, never worrying about
the foreign key columns. But of course, when we save, it stores things exactly like
we expect.

Net, let's learn how to *fetch* related data, to get all of the comments for a
specific `Article`.
