# Fun with Twig Extensions!

Head back to the article show page because... there's a little, bitty problem that
I just introduced. Using the `markdown` filter from KnpMarkdownBundle works... but
the process is not being cached anymore. In the previous tutorial, we created a cool
`MarkdownHelper` that used the markdown object from KnpMarkdownBundle, but added
caching so that we don't need to re-parse the *same* markdown content over and
over again:

[[[ code('6a9df82ef5') ]]]

Basically, we want to be able to use a markdown filter in Twig, but we want
it to use *our* `MarkdownHelper` service, instead of the uncached service from the
bundle.

So... how can we do this? Let's create our *own* Twig filter, and make it do exactly
what we want. We'll call it, `cached_markdown`.

## Generating a Twig Extension

To create a custom function, filter or to extend Twig in any way, you need to create
a Twig *extension*. These are *super* fun. Find your terminal and run:

```terminal
php bin/console make:twig-extension
```

It suggests the name `AppExtension`, which I'm actually going to use. I'll call it
`AppExtension` because I typically create just *one* extension class that will hold
*all* of the custom Twig functions and filters that I need for my entire project.
I do this instead of having multiple Twig extensions... because it's easier.

Let's go check out our new `AppExtension` file!

[[[ code('0c2acd361c') ]]]

Hello Twig extension! It's a normal PHP class that extends a base class, then
specifies any custom functions or filters in these two methods:

[[[ code('8aac7d153d') ]]]

Twig Extensions can add other stuff too, like custom operators or tests.

We need a custom filter, so delete `getFunctions()` and then change the filter name
to `cached_markdown`. Over on the right, this is the *method* that will be called
when the user uses the filter. Let's call our method `processMarkdown`. Point to
that from the filter:

[[[ code('efd5f9d735') ]]]

To make sure things are working, for now, in `processMarkdown()`, just return
`strtoupper($value)`:

[[[ code('029b2b5941') ]]]

Sweet! In the Twig template, use it: `|cached_markdown`:

[[[ code('1985d2c934') ]]]

Oh, and two important things. One, when you use a filter, the value to the *left*
of the filter will become the first argument to your filter function. So, `$value`
will be the article content in this case:

[[[ code('9395a0c57d') ]]]

Second, check out this options array when we added the filter. This is optional.
But when you say `is_safe` set to `html`:

[[[ code('28d1553c10') ]]]

It tells Twig that the result of this filter should *not* be escaped through
`htmlentities()`. And... that's perfect! Markdown gives HTML code, and so we definitely
do *not* want that to be escaped. You won't need this option on most filters, but
we *do* want it here.

And... yea. We're done! Thanks to Symfony's autoconfiguration system, our Twig
extension should already be registered with the Twig. So, find your browser,
high-five your dog or cat, and refresh!

It works! I mean, it's *super* ugly and angry-looking... but it works!

## Processing through Markdown

To make the extension use the `MarkdownHelper`, we're going to use good old-fashioned
dependency injection. Add `public function __construct()` with a `MarkdownHelper`
argument from our project:

[[[ code('c62ef4dfa7') ]]]

Then, I'll press `Alt`+`Enter` and select "Initialize fields" so that PhpStorm creates
that `$helper` property and sets it:

[[[ code('614d1a5736') ]]]

Down below, celebrate! Just `return $this->helper->parse()` and pass it the `$value`:

[[[ code('975ff4a806') ]]]

That's it! Go back, refresh and... brilliant! We *once* again have markdown, but
now it's being cached.
