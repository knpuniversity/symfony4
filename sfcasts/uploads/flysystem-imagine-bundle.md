# Flysystem <3 LiipImagineBundle

Flysystem is working awesome! But... there's a problem hiding... a huge problem!
In *theory*, thanks to Flysystem, we could into the `oneup_flysystem.yaml` file
right now, change the adapter to S3 and everything would work. In *theory*.

## How LiipImagineBundle Finds Images

The problem is LiipImagineBundle. Open up `templates/article/homepage.html.twig`:
we call `uploaded_asset()`, pass that `article.imagePath` and *that* value is passed
into `imagine_filter`. So basically, a string like
`uploads/article_image/something.jpg` is passed to the filter.

The problem? By default, LiipImagineBundle *reads* the source image file directly
from the filesystem. So if we refactored to use S3... well... imagine would be
looking in the wrong place!

You can actually see by running:

```terminal
php bin/console debug:config liip_imagine
```

This is the current config for this bundle, which includes all of its default values.
Near the bottom, see that "loaders" section? The "loader" I the piece that's
responsible for *reading* the source image - and it defaults to use the filesystem,
and knows to look in the `public/` directory! So when we pass it
`upload/article_image/` some filename, it finds it perfectly.

Very simply: we need this to use Flysystem.

## Flysystem Loader

Let's go to back to the LiipImagineBundle documentation: find their GitHub page
and then click down here on the "Download the Bundle" as an easy way to get into
their full docs. Now, go back to the main page and... down here near the bottom,
it talks about different "data loaders". The default is "File System", we want
Flysystem.

Let's see... yea, we've already installed the bundle. Let's copy this loaders
section - we already have our Flysystem config all setup. Then, open our
`liip_imagine.yaml` file and, really, anywhere, paste!

This creates a loader called `profile_photos` - that name can be anything. Let's
use `flysystem_loader`. The critical part is the key `flysystem`: that says to
use the "Flysystem" loader that comes with the bundle. The only thing *it* needs
to know, is the service id of the filesystem that we want to use.

For that, go back to `config/services.yaml` and copy the *long* service id from
the `bind` section. Back in `liip_imagine.yaml`, paste!

We now have a "loader" called `flysystem_loader`, and a "loader's" job is to...
um, "load" the source file. You can *technically* have multiple loaders, though
I've never had to do that. Because we will *always* load our files via Flysystem,
below, add `data_loader` set to the loader's name: `flysystem_loader`. I'll add
a comment:

> default loader to use for all filter sets

Because, you can actually specify what loader you want to use on each filterset.
Again, I've never had to do that. Nope, for us: always use flysystem to find the
source files.

Cool! Let's try it! Let's go into the `public/` directory... let me find it... and
delete all the existing thumbnails - let's delete `media/cache/` entirely. By doing
this, the bundle will use the data loader to get the contents of each image so that
it can recreate the thumbnails.

## Correcting the Path to LiipImagineBundle

Testing time! Let's go back to, how about, the homepage. And... it doesn't work.
Drat! Inspect element. Hmm, it *does* start with the `media/cache/resolve` part.
Then, the path is at the end - `uploads/article_image/lightspeed...png`. That's
the path that we're passing to the filter.

Go back to the homepage template. The problem *now* - and it's *really* cool - is
that we told LiipImagineBundle to use Flysystem to load files... but the *root*
of our filesystem object is the `public/uploads` directory. In other words, if
you to read a file from our filesystem, the path needs to be *relative* to this
directory. In other words, it should *not* contain the `uploads/` part

The fix? Remove the `uploaded_asset()` function: we an just pass `article.imagePath`,
which will be `article_image/` the filename.

I love this! Need to thumbnail something? Just pass it the Flysystem path: you
don't need the word `uploads` or anything like that. The `uploaded_asset()` function
*will* still be useful if you want the public path to an asset *without* thumbnailing,
but if you're using `imagine_filter`, passing the short, relative path is all
you need.

Try it! Refresh! It still doesn't work? Oh yea! A few minutes ago, we deleted all
of the original images from the fixtures. But we *did* re-upload a few of them.
So if you scroll down... here we go - here's the Earth image we uploaded. So, it
*is* now working perfectly.

Let's reload our fixtures to make sure:

```terminal-silent
php bin/console doctrine:fixtures:load
```

Now the homepage... yes - everything is there. Let's make the same change in the
other two places we're using thumbnails. Click into the show page. This lives in
`templates/article/show.html.twig`: remove `uploaded_asset` there. Refresh... good!
For the other one, go back to the admin article section - log back in with password
"engage", because we reloaded the database. When we're editing an image, yep,
also broken.

Find this in `templates/article_admin/_form.html.twig`: take off `uploaded_asset()`.

Got it!

## The Resolver: Saving the Images to Flsystem

So, the "data loader" is responsible for reading the *original* image. But, there's
*another* important concept from LiipImagineBundle called "resolvers". Click down
on the "Flysystem Resolver" in their docs. The resolver responsible for *saving*
the thumbnail image back to the filesystem after all of the transformations. By
default, no surprise, LiipImagineBundle writes things directly to the filesystem.
So even if we moved Flysystem to s3, LiipImagineBundle would *still* be writing
the thumbnail files write back to our server - into the `public/media` directory.

Let's change that! In the docs, copy the `resolvers` section. Back in our
`liip_imagine.yaml` file, paste that. It's pretty much the same as before: we'll
call it `flysystem_resolver` and tell it to *save* the images using the same
filesystem service. Remove `visibility` - that sets the Flysystem visibility, which
is a concept we'll talk about soon. True is the default value anyways.

`cache_prefix` is the subdirectory within, the filesystem where the files will
be stored and the `root_url` is the URL that all the paths will be prefixed with
when the image paths are rendered. Right now, it needs to be `/uploads`.

For example, if LiipImagineBundle stores a file called `media/cache/foo.jpg` into
Flsystem, we know that the public path to this will be `uploads/media/cache/foo.jpg`.
We'll talk more about this setting later when we move to s3.

Ok, delete the `media/` directory entirely. Oh, and I almost forgot the last step:
add `cache` set to `flysystem_resolver` - let's put an "r" on that.

This tells the bundle to *always* use this resolver. I'm not sure why it's called
"cache" - the bundle seems to use "resolver" and "cache" to describe this one concept.

Ok! Moment of truth! Refresh. Ha! It works! Go check out where the thumbnails are
stored: there is *no* `media/` director anymore! The Flysystem filesystem points
to the `public/uploads` directory, so the `media/cache` directory lives *there*.
And thanks to the `/uploads` `root_url`, when it renders the path, it knows to
start with `/uploads` and then the path in Flysystem.

I love this! It's a bit tricky to get these two libraries to play together perfectly.
But now we are *much* more prepared to switch between local uploads and S3.

Next: we can generate public URLs to thumbnailed files or the original files. But,
what if you need to force all the URLs to include the domain name? This is something
you don't think about until you need to generate a PDF or send an email... then,
it can be a nightmare. Let's add this to our asset system in a way that we love.
