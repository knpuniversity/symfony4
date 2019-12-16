# Absolute URLs to Routes & Assets

The HTML content of our email will use *this* template... which is still *totally*
static. For example, see this link going to `#homepage`? That's just a placeholder.
Normally in a template, we would use the `{{ path() }}` function to generate a URL
to the homepage route. The name of that route is... check out `ArticleController`...
there it is: the homepage route name is `app_homepage`. So we would normally
say `path('app_homepage')`.

## Using the url() Function

The *problem* is that this will generate a *relative* URL - it will literally
generate `href="/"`. But for an email, all paths must be *absolute*. To force
that, change `path()` to `url()`.

[[[ code('b19565bf93') ]]]

That's it! Symfony will detect the domain name - `localhost:8000` while we're
coding locally - and use that to prefix the URL.

Let's fix a few other URLs: for the link to create a new article, replace the
hardcoded string with `url()` and the name of *that* route, which if you looked
in the app, is `admin_article_new`. 

[[[ code('25fb95d6f8') ]]]

At the bottom, there's one more link to the homepage. Say  `{{ url('app_homepage') }}`.

[[[ code('ba1ae525c7') ]]]

## A Bit about Webpack Encore & Images

Links, done! But there's one other path we need to fix: the path to this image.
But... forget about emails for a minute. This project uses Webpack Encore to compile
its assets: I have an `assets/` directory at the root, an `images` directory inside
that, and an `email/logo.png` file that I want to reference. You don't need to run
Encore, but if you *did*, I've configured it to *copy* that file into a
`public/build/images/` directory. There it is:
`public/build/images/email/logo.66125a81.png`.

If you downloaded the starting code for the tutorial, you don't need to worry about
running Encore... only because we ran it *for* you and included the final, built
`public/build` directory. I mean, you *can* run Encore if you want - you just
don't need to because the built files are already there.

The point is, whether you're using Encore or not, the end goal is to generate
an absolute URL to a file that lives somewhere in your `public/` directory.
To do that in Twig, we use the `{{ asset() }}` function. Pass this
`build/images/email/logo.png`. Because we're using Encore, we don't need to include
the version hash that's part of the *real* file: the asset function will add that
automatically. Go team!

If you're not using Encore, it's the same process: just use `asset()` then include
the actual path to the physical file, *relative* to the `public/` directory.

## Absolute Image Paths

But... this leaves us with the *same* problem we had for the generated URLs!
By default, the `asset()` function generates *relative* URLs: they don't contain
the domain name. To fix that, wrap this in another function: `absolute_url()`.

[[[ code('e014661c5c') ]]]

And... done! Ready to try this? Move over to the site, go back, change the email
address again... we're going to do this a lot... type a new password, wave a magic
wand and... hit enter. Ok... no errors... a good sign!

Over in Mailtrap, it's already there! Oh, it looks *so* much better: we even have
a working image and, if we hover over a link, the URL *does* contain our domain:
`localhost:8000`. This is even more obvious in the HTML source: everything has
a full URL.

## Automatic "Text" Part

Woh, and... our email *also* has a text part! How did that happen? In the controller,
we *only* called `htmlTemplate()` - we *removed* our call to the `text()` method.
Well... thank you Mailer. If you set the HTML on an email but do *not* explicitly
set the text, Symfony automatically adds it for you by calling `strip_tags()`
on your HTML. That's *awesome*.

Well... awesome... but not *totally* perfect: it included all the styles on top!
Don't worry: we'll fix that soon... kinda on accident. But the bottom looks pretty
great... with *zero* effort.

Next, the URLs and image paths in our email *are* now dynamic... but nothing else
is! Any self-respecting email must have *real* data, like the name of the user...
or their favorite color. Let's make the email *truly* dynamic by passing in variables.
We'll also find out what *other* information is available for free from inside
an email template.
