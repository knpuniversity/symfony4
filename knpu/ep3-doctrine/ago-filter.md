# ago Filter with KnpTimeBundle

Ok, I just *need* to show you something fun - it deals with Twig filters. See this
4 hours ago? That's still hard coded! Find the show template and scroll up a bit
to find it:

[[[ code('36087076a8') ]]]

There!

## Printing a DateTime Object in Twig

The `Article` entity has a `$publishedAt` property, so let's get our act together
and starting using that to print out the *real* date. Oh, but remember: the
`$publishedAt` field might be `null` if the article has *not* been published yet.
So let's use the fancy ternary syntax to say: `{{ article.publishedAt }}`, then,
if it *is* published, print `article.publishedAt`. But, `publishedAt` is a `DateTime`
*object*... and you can't just run around printing `DateTime` objects, and expect PHP
to *not* get angry.

To fix that, pipe this through a `date` filter, and then say `Y-m-d`:

[[[ code('2785f6176a') ]]]

Most filters do not have any arguments - most are like `cached_markdown`. But filters
*are* allowed to have arguments. If the article is *not* published, just say that:
unpublished:

[[[ code('3255edbee2') ]]]

Love it! When we go back and refresh, published on March 20th. 

## Installing KnpTimeBundle

Cool... but it looked better when it said something like "five minutes ago" or
"two weeks ago" - that was *way* more hipster. The date... it's ugly!

Fortunately, there's a really simple bundle that can convert your dates into this
cute "ago" format. Search for KnpTimeBundle. Despite seeing my little face there,
I did *not* create this bundle, so I take no credit for it. I just think it's great.

Scroll down to the "composer require" line, copy that, find your terminal and, paste!

```terminal-silent
composer require knplabs/knp-time-bundle
```

This installs the bundle and... interesting! It also installs `symfony/translation`.
Behind the scenes, KnpTimeBundle uses the translator to translate the "ago" wording
into other languages.

But what's *really* cool is that `symfony/translation` has a Flex *recipe*. Before
I recorded this chapter, I committed our changes so far. So now I can run:

```terminal
git status
```

to see what that sneaky translation recipe did. Interesting: we have a new
`config/packages/translation.yaml` file and a new `translations/` directory where
any translation files should live... *if* we need any.

At a high level, the recipe system, like always, is making sure that everything
is setup for us, automatically.

## Using the ago Filter

Ok, let's use that filter! Back in the template, replace the `date` filter with
`|ago`:

[[[ code('a55d8afc9e') ]]]

That's it. Find the page, refresh and... perfect! 27 days ago. So much nicer!

Next, I want to talk a little bit more about the `AppExtension` Twig extension because,
for a very subtle but important reason, it has a performance problem.
