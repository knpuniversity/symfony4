# OrderBy & fetch EXTRA_LAZY

It's *great* that each article now has *real*, dynamic comments at the bottom.
But... something isn't right: the comments are in a, kind of, random order. Actually,
they're just printing out in whatever order they were added to the database. But,
that's silly! We need to print the *newest* comments on top, the oldest at the bottom.

How can we do this? Check out the template. Hmm, *all* we're doing is calling
`article.comments`:

[[[ code('0d56452a58') ]]]

Which is the `getComments()` method on `Article`:

[[[ code('2789b8f3c9') ]]]

The *great* thing about these relationship shortcut methods is that.... they're easy!
The *downside* is that you don't have a lot of control over what's returned, like,
the *order* of this article's comments.

Well... that's not entirely true. We *can* control the order, and I'll show you
how. Actually, we can control a *lot* of things - but more on that later.

## @ORM\OrderBy()

Scroll all the way to the top and find the `comments` property:

[[[ code('d385850f36') ]]]

Add a *new* annotation: `@ORM\OrderBy()` with `{"createdAt" = "DESC"}`:

[[[ code('0a270f634e') ]]]

That's it! Move over and, refresh! Brilliant! The *newest* comments are on top.
This actually changed *how* Doctrine queries for the related comments.

Oh, and I want to mention *two* quick things about the syntax for annotations.
First... well... the syntax can sometimes be confusing - where to put curly braces,
equal sign etc. Don't sweat it: I still sometimes need to look up the correct syntax
in different situations.

Second, for better or worse, annotations *only* support double quotes. Yep, you
simply *cannot* use single quotes. It just won't work.

## Fetch EXTRA_LAZY

I want to show you one other trick. Go back to the homepage. It would be really
nice to list the number of comments for each article. No problem! Open
`homepage.html.twig`. Then, inside the articles loop, right after the title, add
a `<small>` tag, a set of parentheses, and use `{{ article.comments|length }}`
and then the word "comments":

[[[ code('deb9f1ccdd') ]]]

I love it! Refresh the homepage. It works effortlessly! But... check out the
queries down here on the web debug toolbar. If you click into it, there are suddenly
*6* queries! The first query is what we expect: it finds all published articles.

The second query selects all of the `comments` for an article whose id is 176. The
next is an *identical* query for article 177. As we loop over the articles, each
time we call `getComments()`, at that moment, Doctrine fetches *all* of the comments
for that specific `Article`. Then, it counts them.

This is a classic, *potential* performance issue with ORM's like Doctrine. It's
called the N+1 problem. And, we'll talk about it later. But, it's basically that
the cool lazy-loading of relationships can lead to an extra query per row. And this
*may* cause performance issues.

But, forget about that for now, because, there's a *simpler* performance problem.
We're querying for *all* of the comments for each article... simply to count them!
That's insane!

This is the default behavior of Doctrine: as soon as you call `getComments()` and
use that data, it makes a query at that moment to get all of the comment data, even
if you eventually only need to *count* that data.

But, we can control this. In `Article,` at the end of the `OneToMany` annotation,
add `fetch="EXTRA_LAZY"`:

[[[ code('bcb96e2dee') ]]]

Now, go back to the page and refresh. We *still* have six queries, but go look at
them. Awesome! Instead of selecting *all* of the comment data, they are super-fast
COUNT queries!

Here's how this works: if you set `fetch="EXTRA_LAZY"`, and you simply *count*
the result of `$article->getComments()`, then instead of querying for all of the
comments, Doctrine does a quick COUNT query.

Awesome, right! You might think that it's *so* awesome that this should *always*
be the way it works! But, there is *one* situation where this is *not* ideal. And
actually, we have it! Go to the article show page.

Here, we count the comments first, and *then* we loop over them:

[[[ code('a78ed91ad5') ]]]

Look at the profiler now. Thanks to `EXTRA_LAZY`, we have an extra query!
It counts the comments... but then, right after, it queries for all of them anyways.
*Before* we were using `EXTRA_LAZY`, this count query didn't exist.

So, sorry people, like life, everything is a trade-off. But, it's still probably
a net-win for us. But *as always*, don't prematurely optimize. Deploy first, identify
performance issues, and then solve them.
