# Thumbnailing with LiipImagineBundle

Go back to the homepage. We're rendering these images with a width and height
of 100. But the image behind this is *way* bigger! That's wasteful: we don't want
the user to wait to download these *gigantic* images, just to see the tiny thumbnail.

## Hello LiipImagineBundle

Google for LiipImagineBundle and find its GitHub page. They have a bunch of docs
right here... but *most* of the information actually lives over on Symfony.com.
Click "Download the Bundle" to get there... and then I'll go back to the homepage -
*lots* of good stuff here.

Start back on the Installation page. Copy the composer require line, find your
terminal, paste and... go go go!

```terminal-silent
composer require liip/imagine-bundle
```

While we're waiting, head back over to the docs. Thanks to Flex, we don't need
to enable the bundle or register the routes - that's automatic. Go back to the
homepage of the docs... and click the "Filter Sets" link.

This bundle is pretty sweet. You start by creating something called a "filter set"
and giving it a name - like `my_thumb` or whatever you want. Next, you tell the
bundle which *filters*, or *transformations*, to apply when you use the `my_thumb`
filter set. And there are a *ton* of them: you can change the size with the `thumbnail`
filter, add a background, add border color, replace the image entirely with a cat
gif - pretty much anything you can dream of. We'll just use the `thumbnail` transformation,
but seriously - check out the full list.

## Configuring the Filter Set

Let's go check on the install. Excellent! It's done. And the message is right on:
it says we need to get to work in the new config file: `liip_imagine.yaml`.
Go open that: `config/packages/liip_imagine.yaml`. Uncomment the root key to activate
the bundle, leave the `driver` alone - it defaults to `gd` - and uncomment
`filter_sets`. Let's create our first filter set called `squared_thumbnail_small`.
We'll use this on the homepage to reduce the images down to 100 by 100. To do that,
uncomment the `filters` key and I'll copy the `thumbnail` example from below, move
it up here, and uncomment it.

Set the size to 200 by 200 so it looks good on Retina displays. The `mode: outbound`
is *how* the thumbnail is applied - you can also use `inbound`. And... I think
we're ready to go! Copy the `squared_thumbnail_small` name and go into
`homepage.html.twig`. To use this, it's so nice: `|imagine_filter()` and then the
name.

## The Thumbnailing Process

Let's go try it! Watch the image src closely. Refresh! It includes the
`https://127.0.0.1` part, but that's not important. The path -
`/media/cache/resolve/squared_thumbnail_small/...` blah, blah blah - looks
like a path to a physical file, but it's not! This is actually a Symfony route and
it's handled by a Symfony controller!

Check it out: at your terminal, run:

```terminal
php bin/console debug:router
```

There it is! The first time we refresh, LiipImagineBundle generates this URL.
When our browser tries to download the image, it's handle by a controller from
the bundle. That controller opens the original image, applies all the filters -
just a thumbnail in our case - and returns the transformed image. That's a *slow*
operation: our browser has to wait for all of that to finish.

But, watch what happens when we refresh. Did you see it? The path changed! It
*was* `/media/cache/resolve` - but the resolve part is now gone! This time, the
image is *not* handled by a Symfony route. Look at your `public/` directory: there
is now a `media/` directory with
`cache/squared_thumbnail_small/uploads/article_image/astronaut-...jpeg`.

The full process looks like this. The first time we refreshed, LiipImagineBundle
noticed that no thumbnail file existed yet. So, it created the URL that pointed
to the Symfony route & controller. The page finished rendering, and our browser
make a second request to that URL to load the image. That request was handled by
the controller from the bundle which thumbnailed the image, *saved* it to the filesystem,
and returned it to the user. That's slow.

But when we reloaded the page the *second* time, LiipImagineBundle *noticed* that
the filename already existed and generated a URL directly to that *real* file.
The request for *that* image was *super* fast.

Oh, also check out the `.gitignore` file. Thanks to the Flex recipe, we're already
ignoring the `public/media` directory: we do not want to commit this stuff: it'll
just regenerate if it's missing.

So, yea - it all kinda works perfectly!

Next, let's add another filter set for the show page *and* add an image preview
to the article form.
