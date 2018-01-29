# KnpMarkdownBundle & its Services

Scroll down to the "Why do Asteroids Taste like Bacon" article and click to see it.
Here's the goal: I want the article body to be processed through *markdown*. Of course,
Symfony doesn't come with a markdown-processing service... but there's probably a
bundle that *does*! Google for KnpMarkdownBundle and find its GitHub page.

## Installing a Bundle

Let's get this installed: copy the `composer require` line. Then, move over to your
terminal, paste and... go!

```terminal-silent
composer require knplabs/knp-markdown-bundle
```

Notice that this is a *bundle*: you can see it right in the name. That means it
likely contains two things: First, of course, some PHP classes. And second, some
configuration that will add one or more new *services* to our app!

And.... installed! It executed one recipe, which made just *one* change:

```terminal-silent
git status
```

Yep! It updated `bundles.php`, which *activates* the bundle:

[[[ code('f899273a9c') ]]]

## Finding the new Service

So... what's different now? Run:

```terminal
./bin/console debug:autowiring
```

and scroll to the top. Surprise! *We* have a new tool! Actually, there are *two*
interfaces you can use to get the *same* markdown service. How do I know these will
give us the *same* object? And which should we use? We'll talk about those two questions
in the next chapter.

But since it doesn't matter, let's use `MarkdownInterface`. Open `ArticleController`.
In `show()`, create a new variable - `$articleContent` - and set it to the multiline
HEREDOC syntax. I'm going to paste in some fake content. This is the same beefy
content that's in the template. In the controller, let's markdownify some stuff!
Add some emphasis to `jalapeno bacon` ands let's turn `beef ribs` into a link to
`https://baconipsum.com/`:

[[[ code('827f680350') ]]]

Pass this into the template as a new `articleContent` variable:

[[[ code('bd30ef152a') ]]]

And *now*, in the template, remove *all* the old stuff and just print `{{ articleContent }}`:

[[[ code('321daed679') ]]]

Let's try it! Go back to our site and refresh! No surprise: it's the *raw* content.
*Now* it's time to process this through Markdown!

## Using the Markdown Service

In `ArticleController`, tell Symfony to pass us the markdown service by adding
a type-hinted argument. Let's use `MarkdownInterface`: `MarkdownInterface $markdown`:

[[[ code('ebf721c928') ]]]

Now, below, `$articleContent = $markdown->` - we never looked at the documentation
to see *how* to use the markdown service... but thanks to PhpStorm, it's pretty
self-explanatory - `$markdown->transform($articleContent)`:

[[[ code('75a75d28e8') ]]]

## Un-escaping Raw HTML

And that's it! Refresh! It works! Um... *kind* of. It *is* transforming our markdown
into HTML... but if you look at the HTML source, it's all being *escaped*! Bah!

Actually, this is *awesome*! One of Twig's super-powers - in addition to having very
stylish hair - is to automatically escape any variable you render. That means
you're protected from XSS attacks without doing *anything*.

If you *do* know that it's safe to print raw HTML, just add `|raw`:

[[[ code('89347266e1') ]]]

Try it again! Beautiful!

So here is our first big lesson:

1. Everything in Symfony is done by a service
2. Bundles give us these services... and installing new bundles gives us *more* services.

And 3, Twig clearly gets its hair done by a professional.

Next, let's use a service that's *already* being added to our app by an existing
bundle: the *cache* service.
