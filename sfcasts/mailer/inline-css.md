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

## Checking for the twig-pack

To get this all working, we need to check that something is installed. If you
started your project *after* October 2019, you can skip this because you *will*
already have it.

For older projects, first make sure you have version Twig 2.12 or higher - you
can find your version by running:

```terminal
composer show twig/twig
```

Mine is too old, so I'll update it by running:

```terminal
composer update twig/twig
```

Now run:

```terminal
composer require twig
```

That... might look confusing... don't we already have Twig installed? Before
October 2019, `composer require twig` installed the TwigBundle... only. But
if you ran this command *after* October 16th, 2019... to be exact, the `twig`
alias would download `symfony/twig-pack`. The *only* difference is that the
`twig-pack` will install the normal TwigBundle *and* a new `twig/extra-bundle`,
which is a library that will help us install optional Twig features as we go.
You'll see what I mean.

The *main* point is: make sure `twig/extra-bundle` is installed and the best way
to get it is from the pack. If you installed Twig after October 2019, you probably
already have it.

## The inline_css Filter

Ok, back to work! In `welcome.html.twig`, *all* the way on top, add
`{% apply inline_css %}`.

`inline_css` is actually a *filter*... and in Twig, you *normally* use a filter
with the `|` symbol - like `foo|inline_css`. But if you want to run a *lot* of
stuff through a filter, you can do it with this handy `apply` *tag*. At the bottom
of the template, say `{% endapply %}`.

And... that's it! This passes our *entire* template through this filter... which
is *super* smart. It reads the CSS from inside the `style` attribute and uses
that to add `style` attributes to every HTML element that it finds. Yea... it's
crazy!

Let's see this in action. Go back to `/register` and fill the form back in... I'll
use `thetruthisoutthere9@example.com`, any password, agree and... register!

## TwigExtraBundle Invites you to Install Packages

It works! I'm kidding! But it's the *next* best thing. The error tells us *exactly*
what's going on:

> The "inline_css" filter is part of the CssInlinerExtension - try running
> "composer require twig/cssinliner-extra"

Why, what a fabulous idea! This error comes from that new TwigExtraBundle, which
allows you to integrate some outside Twig extension libraries *simply* by installing
them. And... be even *shinier*, if you're using a feature that's requires some
library you don't have, it tells you!

Copy the `composer require` line, move over to your terminal, and run:

```terminal
composer require twig/cssinliner-extra
```

When that finishes... move over to the browser again, hit back and... let's change
the email to 9b to be unique. Type a password, hit enter and... go check out that
email! It still looks the same here... but look out the HTML source. The `style`
tag *is* still there but if you scroll... *wow*. The styles *have* been applied
to each element.

This is one of my absolute *favorite* features of mailer. It's a huge chore that...
just works.

Next, let's use this to clean things up even more. Instead of having all this CSS
right in the template, let's use a proper, standalone CSS file.
