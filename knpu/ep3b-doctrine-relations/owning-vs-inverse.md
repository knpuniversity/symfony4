# Owning Vs Inverse Relations

We need to talk about a very, very important and, honestly, super-confusing part
of Doctrine relations! Listen closely: this is the *ugliest* part of working
with Doctrine relations. So, let's get through it, put a big beautiful check mark
next to it, then move !

It's called the owning versus inverse sides of a relationship, and it's related
to the fact that you can always look at a single relationship from two different
directions. You can either look at the `Comment` and say that this has one `article`,
so, this is `ManyToOne` to `Article`, or you can go to `Article` and - for that
same one relationship, you can say that this `Article` has many comments.

So, what's the big deal then? We already know that you can *read* data from either
direction. You can say `$comment->getArticle()` or `$article->getComments()`. But,
can you also *set* data on both sides? Well... that's where things get interesting.

In `ArticleFixtures`, we've proven that you *can* use `$comment->setArticle()` to
set the relationship. When we have, everything persists to the database perfectly.
But now, comment those out. Instead, set the data from the *other* direction:
`$article->addComment($comment1)` and `$article->addComment($comment2)`.

We're adding the comments to the `comments` collection on `Article`. By the way,
don't worry that this code lives *after* the call to `persist()`. That's actually
fine: the code just needs to come before `flush()`.

Anyways, let's try it! Find your terminal, sip some coffee, and reload the fixtures:

```terminal
php bin/console doctrine:fixtures:load
```

Ok, no errors! Check the database:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM comment"
```

Yea! It *does* still save correctly! The comments saved correctly, *and* each has
its `article_id` set.

So, I guess we found our answer: you can get *or* set data from *either* side of
the relationship. Well actually... that's not true!

## Synchronizing the Owning Side

Hold Command or Ctrl and click the `addComment()` method to jump into it. Now,
look closely: this code was generated for us by the `make:entity` command. First,
it checks to make sure the comment isn't *already* in the `comments` collection,
that's just to make sure the same comment isn't added multiple times. Then, of
course, it adds the comment to the `$comments` property. But *then*, it does something
very important: it calls `$comment->setArticle($this)`.

Yep, the code *synchronizes* the data to the *other* side of the relationship.
It makes sure that if you add this `Comment` to this `Article`, then the `Article`
is also set on the `Comment`.

Let's try something: comment out the `setArticle()` call for a moment. Then, go
back to your terminal and reload the fixtures:

```terminal-silent
php bin/console doctrine:fixtures:load
```

Woh! Explosion! When Doctrine tries to save the comments, their `article_id` is
empty! Basically, the relationship is *not* being set correctly!

## Owning Versus Inverse

*This* is *exactly* what I wanted to talk about. Every relationship has two
sides. One side is known as the *owning* side of the relation and the other
is known as the *inverse* side of the relation. I'll scroll back to the top
of `Comment`. For a ManyToOne and OneToMany relation, the *owning* side is always
the ManyToOne side. And, it's easy to remember: the owning side is the side where
the actual *column* appears in the table. Because the `comment` table will have
the `article_id` column, the `Comment.article` property is the owning side. The
And so, `Article.comments` property is the *inverse* side.

The reason this is *so* important is that, when you relate two entities together
and save, Doctrine *only* looks at the *owning* side of the relationship to figure
out what to persist to the database. Right now, we're *only* setting the *inverse*
side! When Doctrine saves, it looks at the `article` property on Comment - the
owning side - sees that it is null, and tries to save without a related article.

So, the owning side is the *only* side where the data matters for setting purposes.
In fact, the *entire* purpose of the *inverse* side of the relationship is just
convenience. It only exists because it's useful for to be able to say
`$article->getComments()`. That was *particularly* handy in the template.

## The Inverse Side is Optional

Heck, the inverse side of a relationship is actually optional! The `make:entity`
command *asked* us if we wanted to generate the inverse side of the relationship.
We could delete *all* of the `comments` stuff from `Article`, and the relationship
would still exist in the database *exactly* like it does now. And, we could still
use it. We wouldn't have our fancy `$article->getComments()` shortcut anymore, but
everything else would be fine.

I'm explaining this so that *you* can hopefully avoid a huge WTF moment in the
future. If you ever try to relate two entities together and it is *not* saving,
it may be because of this problem.

I'm going to uncomment the `setArticle()` call. In practice, when you use the
`make:entity` generator, it takes care of this ugliness automatically, by generating
code that synchronizes the owning side of the relationship when you set the
*inverse* side. But, keep this concept in mind: it may eventually bite you!

Back in `ArticleFixtures`, refactor things back to the original, with
`$comment->setARticle()`. But, *we* know that, thanks to the code that was generated
by `make:entity`, we *could* also set the inverse side.

Next, let's setup our fixtures properly, and do some *cool* stuff to generate
comments and articles that are randomly related to each other.
