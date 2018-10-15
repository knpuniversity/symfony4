# Giving the Comments an isDeleted Flag

I want to show you a *really* cool, *really* powerful feature. But, to do that,
we need to give our app a bit more depth. We need to make it possible to mark
comments as *deleted*. Because, honestly, not *all* comments on the Internet are
as insightful and amazing as the ones that *you* all add to KnpUniversity. You all
are *seriously* the best! But, instead of *actually* deleting them, we want to keep
a record of deleted comments, just in case.

## Adding Comment.isDeleted Field

Here's the setup: go to your terminal and run:

```terminal
php bin/console make:entity
```

We're going to add a new field to the `Comment` entity called `isDeleted`. This
will be a `boolean` type and set it to not nullable in the database:

[[[ code('f284e42da2') ]]]

When that finishes, make the migration:

```terminal-silent
php bin/console make:migration
```

And, you know the drill: open that migration to make sure it doesn't contain
any surprises:

[[[ code('a2dbeb7069') ]]]

Oh, this is cool: when you use a `boolean` type in Doctrine, the value on your
entity will be true or false, but in the database, it stores as a tiny int with
a zero or one.

This looks good, so move back and.... migrate!

```terminal
php bin/console doctrine:migrations:migrate
```

## Updating the Fixtures

We're not going to create an admin interface to delete comments, at least, not yet.
Instead, let's update our fixtures so that it loads some "deleted" comments. But
first, inside `Comment`, find the new field and... default `isDeleted` to `false`:

[[[ code('e773474f75') ]]]

Any new comments will *not* be deleted.

Next, in `CommentFixture`, let's say `$comment->setIsDeleted()` with
`$this->faker->boolean(20)`:

[[[ code('cb5f89bf76') ]]]

So, out of the 100 comments, approximately 20 of them will be marked as deleted.

Then, to make this a *little* bit obvious on the front-end, for now, open
`show.html.twig` and, right after the date, add an if statement: if
`comment.isDeleted`, then, add a close, "X", icon and say "deleted":

[[[ code('991911f779') ]]]

Find your terminal and freshen up your fixtures:

```terminal
php bin/console doctrine:fixtures:load
```

When that finishes, move back, refresh... then scroll down. Let's see... yea!
Here's one: this article has one deleted comment.

## Hiding Deleted Comments

We printed this "deleted" note *mostly* for our own benefit while developing. Because,
what we *really* want to do is, of course, *not* show the deleted comments at all!

But... hmm. The problem is that, to *get* the comments, we're calling
`article.comments`:

[[[ code('7bddb29bcd') ]]]

Which means we're calling `Article::getComments()`:

[[[ code('4367309ce1') ]]]

This is our super-handy, super-lazy shortcut method that returns *all* of the comments.
Dang! Now we need a way to return only the *non-deleted* comments. Is that possible?

Yes! One option is super simple. Instead of using `article.comments`, we could go
into `ArticleController`, find the `show()` action, create a custom query for the
`Comment` objects we need, pass those into the template, then use that new variable.
When the shortcut methods don't work, always remember that you don't *need* to use
them.

But, there is *another* option, it's a bit lazier, *and* a bit more fun.

## Creating Article::getNonDeletedComments()

Open `Article` and find the `getComments()` method. Copy it, paste, and rename
to `getNonDeletedComments()`. But, for now, just return *all* of the comments:

[[[ code('44163dc8ca') ]]]

Then, in the show template, use this new field: in the loop, `article.nonDeletedComments`:

[[[ code('d6a6586015') ]]]

And, further up, when we count them, *also* use `article.nonDeletedComments`:

[[[ code('848c99a97c') ]]]

Let's refresh to make sure this works so far. No errors, but, of course, we are
*still* showing *all* of the comments.

## Filtering Deleted Comments in Article::getNonDeletedComments()

Back in `Article`, how can we change this method to filter out the deleted comments?
Well, there is a lazy way, which is sometimes good enough. And an awesome way! The
lazy way would be to, for example, create a new `$comments` array, loop over
`$this->getComments()`, check if the comment is deleted, and add it to the array
if it is not. Then, at the bottom, return a new `ArrayCollection` of those
comments:

```php
$comments = [];

foreach ($this->getComments() as $comment) {
    if (!$comment->getIsDeleted()) {
        $comments[] = $comment;
    }
}

return new ArrayCollection($comments);
```

Simple! But... this solution has a drawback... performance! Let's talk about that
next, *and*, the awesome fix.
