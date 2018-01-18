# Twig ❤️

Back to work! Open `ArticleController`. As soon as you want to render a template,
you need to extend a base class: `AbstractController`:

[[[ code('21312d03bb') ]]]

Obviously, your controller does not *need* to extend this. But they usually will...
because this class gives you shortcut methods! The one we want is `return $this->render()`.
Pass it a template filename: how about `article/show.html.twig` to be consistent
with the controller name. The second argument is an array of variables that you want
to pass *into* your template:

[[[ code('30d53c8e17') ]]]

Eventually, we're going to load articles from the database. But... hang
on! We're not quite ready yet. So let's fake it 'til we make it! Pass a
`title` variable set to a title-ized version of the slug:

[[[ code('920ec9980f') ]]]

Great! Let's go add that template! Inside `templates/`, create an `article` directory
then the file: `show.html.twig`.

Add an `h1`, then print that `title` variable: `{{ title }}`:

[[[ code('0b61ed35ed') ]]]

## Twig Basics

If you're new to Twig, welcome! You're going to *love* it! Twig only has *2* syntaxes.
The first is `{{ }}`. I call this the "say something" tag, because it *prints*.
And just like PHP, you can print anything: a variable, a string or a complex expression.

The second syntax is `{% %}`. I call this the "do something" tag. It's used whenever
you need to, um, *do* something, instead of printing, like an `if` statement or `for`
loop. We'll look at the *full* list of do something tags in a minute.

And... yea, that's it! Well, ok, I totally lied. There is a *third* syntax:
`{# #}`: comments! 

At the bottom of this page, I'll paste some extra hard-coded content to *spice*
things up!

[[[ code('fded556337') ]]]

Let's go try it! Find your browser and refresh! Boom! We have content!

But check it out: if you view the page source... it's *just* this content: we don't
have any layout or HTML structure yet. But, we will soon!

## Looping with for

Go back to your controller. Eventually, users will need to be able to comment on
the articles, so they can respectfully debate the article's conclusions based on
objective analysis and research. Ya know... no different than *any* other news
commenting section. Ahem.

I'll paste in 3 fake comments. Add a second variable called `comments` to pass
these into the template:

[[[ code('f8434f96ff') ]]]

This time, we can't just *print* that array: we need to loop over it. At the bottom,
and an `h2` that says "Comments" and then add a `ul`:

[[[ code('b81ee7fde9') ]]]

To loop, we need our first *do* something tag! Woo! Use `{% for comment in comments %}`.
Most "do" something tags also have a closing tag: `{% endfor %}`:

[[[ code('25bd457831') ]]]

Inside the loop, `comment` represents the individual comment. So, just print it:
`{{ comment }}`:

[[[ code('75dd75142f') ]]]

Try it! Brilliant! I mean, it's *really* ugly... oof. But we'll fix that later.

## The Amazing Twig Reference

Go to [twig.symfony.com][twig_docs] and click on the Documentation link. Scroll down
a little until you see a set of columns: the [Twig Reference][twig_ref].

This is *awesome*! See the tags on the left? That is the *entire* list of possible
"do something" tags. Yep, it will always be `{%` and then one of these: `for`, `if`,
`extends`, `tractorbeam`. And honestly, you're only going to use about 5 of these
most of the time.

Twig also has functions... which work like every other language - and a cool thing
called "tests". Those are a bit unique, but not too difficult, they allow you to
say things like `if foo is defined` or... `if space is empty`.

The most *useful* part of this reference is the filter section. Filters are like
functions but with a different, way more hipster syntax. Let's try our the `|length`
filter.

Go back to our template. I want to print out the total *number* of comments. Add
a set of parentheses and then say `{{ comments|length }}`:

[[[ code('235089f531') ]]]

*That* is a filter: the `comments` value passes from the left to right, just like
a Unix pipe. The `length` filter counts whatever was passed to it, and we print the
result. You can even use *multiple* filters!

***TIP
To unnecessarily confuse your teammates, try using the `upper` and `lower` filters
over and over again: `{{ name|upper|lower|upper|lower|upper }}`!
***

## Template Inheritance

Twig has *one* last *killer* feature: it's template inheritance system. Because
remember! We don't *yet* have a *real* HTML page: just the content from the template.

To fix this, at the top of the template, add `{% extends 'base.html.twig' %}`:

[[[ code('2d2ff7b0f0') ]]]

This refers to the `base.html.twig` file that was added by the recipe:

[[[ code('e8a33ec214') ]]]

It's simple now, but this is *our* layout file and we'll customize it over time.
By extending it, we should *at least* get this basic HTML structure.

But when we refresh... surprise! An error! And probably one that you'll see at some
point!

> A template that extends another one cannot include content outside Twig blocks

Huh. Look at the base template again: it's basically an HTML layout plus a bunch of
blocks... most of which are *empty*. When you extend a template, you're telling
Twig that you want to put your content *inside* of that template. The blocks, are
the "holes" *into* which our child template can put content. For example, there's
a block called `body`, and that's *really* where we want to put our content:

[[[ code('4cb4a54ec9') ]]]

To do that, we need to *override* that block. At the top of the content, add
`{% block body %}`, and at the bottom, `{% endblock %}`:

[[[ code('d73afdb44a') ]]]

Now our content should go *inside* of that block in `base.html.twig`. Try it!
Refresh! Yes! Well, it doesn't look any different, but we *do* have a proper HTML
body.

##  More about Blocks

You're *completely* free to customize this template as much as you want: rename
the blocks, add more blocks, and, hopefully, make the site look less ugly!

Oh, and *most* of the time, the blocks are empty. But you *can* give the block
some *default* content, like with `title`:

[[[ code('c7206760e1') ]]]

Yep, the browser tab's title *is* `Welcome`.

Let's override that! At the top... or really, *anywhere*, add `{% block title %}`.
Then say `Read `, print the `title` variable, and `{% endblock %}`:

[[[ code('e5912946a5') ]]]

Try that! Yes! The page title changes. And... voilà! That's Twig. You're going to
*love* it.

***SEEALSO
Check out another [screencast][twig_screencast] from us to learn more about Twig
***

Next let's check out one of Symfony's most *killer* features: the profiler.

[twig_docs]: https://twig.symfony.com/
[twig_ref]: https://twig.symfony.com/doc/2.x/#reference
[twig_screencast]: https://knpuniversity.com/screencast/twig
