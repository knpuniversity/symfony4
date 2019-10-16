# Automatic CSS Inlining

Our email looks good in Mailtrap, but will it look good in Gmail or Outlook? That's
one of the things that Mailtrap *can't* answer: it lets us see a *ton* of great
info about our email... but it is *not* showing an accurate representation of
*how* it would be displayed in the real world. If you need to be *super* strict
about making sure your email looks everywhere, check out services like Litmus.

But generally speaking, there are *two* big rules you should follow if you want
your emails to look good everywhere. First, use a *table-based* layout instead
of floating or Flex-box. We'll talk about how to do this... without hating it...
a bit later. The *second* rule is that you *can't* use CSS files or *even* add
a `<style>` tag. These will *not* work in gmail. If you want to style your elements...
which you totally *do*... then you literally need to add `style=""` to *every* HTML
element.

That's... insane! That is *no* way to live. So... we are *not* going to do that.
Well... what I mean is, we are not going to do that *manually*.

## The inline_css Filter

Start by installing a new library:

```terminal
composer require twig/cssinliner-extension
```

This Twig extension will give us a new Twig *filter* that will automatically add
all the style attributes *for* us. This idea is called CSS "inlining". It's also
*awesome*.

*All* the way at the top of our template, add `{% filter inline_css %}`. In Twig,
you *normally* use a filter with the `|` symbol - like `foo|inline_css`. But if
you want to run a *lot* of stuff through a filter, you can do it with this handy
`filter` *tag*. At the bottom of the template, say `{% endfilter %}`.

And... that's it! This passes our *entire* template through this filter... which
is *super* smart. It reads the CSS from inside the `style` attribute and uses
that to add `style` attributes to every HTML element that it finds. Yea... it's
crazy!

Let's see this in action. Hit back on your browser, change the email, type a
password, hit enter and... go check out that email! It still looks the same here.
Check out the HTML source. The `style` tag *is* still there but if you scroll...
*wow*. The styles *have* been applied to each element.

This is one of my absolute *favorite* features of mailer. It's a huge chore that...
just works.

## Inlining External CSS Files

And *now* we can go a step further. I don't *love* having all my email styles
inside a `style` attribute. It works... but will be a problem once our app needs
to send *two* emails: we don't want to duplicate this.

Nope, in the real world, we put CSS into a CSS *files*. Let's do that. Copy
*all* of the styles and delete them. Inside the  `assets/css` directory, let's
create a new `email.css` file. Paste!

So far, we've seen that the `inline_css` filter is smart enough to *notice*
any `style` tags in the template and use that CSS to style the HTML tags. But
you can *also* point the filter to an *external* CSS file.

Go back to `config/packages/twig.yaml`. To point to the CSS file, we need to add
another Twig path: let's set the `assets/css` directory to `styles`. So, `@styles`
will point to `assets/css/`.

Back in `welcome.html.twig`, we can pass an argument to `inline_css()`: some
*string* styles that it should use for styling. To get that, use the `source()`
function and then `@styles/` and then the name of our file `email.css`.

The `source()` function is a standard Twig function... that you don't see very
often. It tells Twig to go find the file - which could be a CSS file or another
Twig template - and return its *contents*. It's basically a `file_get_contents()`
for Twig... which is *perfect*, because `inline_css()` doesn't want a *file* path,
it wants the *string* styles it should use.

Lets try this! Hit back once again in your browser, bump the email, type a password,
submit and... it looks good! And *this* time in the HTML source, the `style` tag
is *not* there... but the inline *styles* still are. That's another benefit of
the CSS *file* - it got rid of the extra `style` attribute, which makes our email
a little bit smaller.

## Using Sass or Encore for Email CSS?

By the way, if you prefer to use Sass or LESS for your CSS and are using Webpack
Encore to compile all of that into your final CSS file, then you have a problem.
You *must* pass a *CSS* file to `inline_css` - you can't pass it a Sass file and
expect it to know how to process that. Instead, you need to point `inline_css`
at the final, *built* version of your CSS - the file that lives in `public/build/`.

Doing that is easy enough - you could add another Twig path - maybe called
`encore` - that refers to the `public/build` directory. Except... if you're
using versioned filenames... then how do you know exactly what the built filename
will be?

This is a *long* way of saying that pointing to a CSS file with `inline_css` is
easy... but pointing to a Sass file is... trickier. Later, we'll walk you through
how to do it.

But first! The two rules of making an email look good in every email client are,
one, use a table-based layout instead of floats or flex-box. And two, inline
your styles. We've done the first, *now* its time to do the second. Does this mean
we need to rewrite our HTML to use ugly, annoying tables? Actually... no!
