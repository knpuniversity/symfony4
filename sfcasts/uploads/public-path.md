# URL to Public Assets

The hardest part of handling uploads... may not even be the uploading part! For
me, it's rendering the URLs to the image tags, thumbnailing and checking security
before letting users download certain assets. Oh, and we *gotta* keep this all
organized: I do *not* want a bunch of upload directory names sprinkled over 50
places in my code. It's bad for sanity, I mean, maintenance, and will make it
hard to move your uploads to the cloud later... which we *are* going to do.

Look back at the homepage: all of these images work except for one. But, *this*
is actually the image that we uploaded! Inspect element on that and check its path:
`/images/astronaut-blah-blah.jpeg` name. Check out one of the working images.
That's right: until now, in the fixtures, we set the `$imageFilename` string to
one of a few filenames that are hardcoded and committed into the `public/images`
directory, like `asteroid.jpeg`.

These aren't really uploaded assets - we were just faking it! Check out the template:
`templates/article/homepage.html.twig`. There it is! We're using the `asset()`...
ah, wrong spot. Down here, we're saying `{{ asset(article.imagePath) }}`, which
calls `getImagePath()` inside `Article`. That just prefixes the filename with
`images/` and returns it! So if `imageFilename` is `asteroid.jpeg` in the database,
this returns `images/asteroid.jpeg`.

## Pointing the Path to uploads/

Ok, so now that the true uploaded assets are stored in a different directory, we
can just change this path! In `Article`, update the path: `uploads/article_image/`
and then `$this->getImageFilename()`.

Cool! Try it out! It works! We don't care about the broken images from the fixtures:
we'll fix that soon. But the *actual* upload image *does* render.

## Getting Organized

*Great* first step. But now, I want to get organized. One problem is that we have
the directory name - `article_image` - in `Article` and *also* in `UploaderHelper`
where we handle the upload. That's not too bad - but as we start adding more file
uploads to the system, we're going to have more duplication. I don't like having
these important strings in multiple places.

So, in `UploaderHelper`, why not create a constant for this? Call it `ARTICLE_IMAGE`
and set it to the directory name: `article_image`. Down below, use that:
`self::ARTICLE_IMAGE`. And in `Article`, do the same thing:
`UploaderHelper::ARTICLE_IMAGE`.

Small step, but when we refresh, it works fine.

## Centralizing the Public Path

Let's keep going! Back in `Article`, the path starts with `uploads`... because that's
part of the public path to the asset. That's not a huge problem, but I actually
don't want that `upload` string to live here. Why? Well, I kinda don't want my
entity to really care *where* or *how* we're storing our uploads. Like, if our
site grows and we move our uploads to the cloud, we would need to change this
`uploads` string to a full CDN URL in *all* entities with an upload field. And,
that URL might even need to be dynamic - we might use a different CDN locally
versus on production! We can organize things better right now.

Remove the `uploads/` part from the path. This much better: the `getImagePath()`
now returns the path to the image relative to *wherever* our app decides to store
uploads. Now, in `UploaderHelper`, add a new `public function getPublicPath()`.
This will take a string `$path` - that will be something like
`article_image/astronaut.jpeg`. And it will return a string, which will be the
*actual* public path to the file. Inside, `return 'uploads/'.$path;`.

That may look like a tiny improvement, but it's awesome! Now, from *anywhere* in
our app, we can call `getPublicPath()` to get the public path to any uploaded file.
If we move to the cloud, we only need to change this one spot. Awesome!

## uploaded_asset() Twig Extension

Except... how can we call this from Twig? Because, if we refresh right now... it
definitely does *not* work. No worries: let's create a Twig extension! Open
`src/Twig/AppExtension` - this is the Twig extension we created in our Symfony
series. Here's the plan: in the homepage template, instead of using the `asset()`
function, let's use a new function called `uploaded_asset()`. We'll pace it
`article.imagePath()` - and it'll ultimately call `getPublicPath()`.

In `AppExtension`, copy `getFilters()`, paste and rename it to  `getFunctions()`.
Return an array, and, inside, add a `new TwigFunction()` with `uploaded_asset`
and `[$this, 'getUploadedAssetPath']`. Copy that new method name, scroll down
and add it: `public function getUploadedAssetPath()` with a `string $path` argument.
And this will also return a string.

Inside: we need to get the `UploaderHelper` service so we can call `getPublicPath()`
on it. Normally, we do this by adding this as an argument to the constructor. But,
in a few places in Symfony, for performance purposes, we do something *slightly*
different: we use what's called a "service subscriber", so we can fetch the services
lazily. If this is a new concept for you, go check out our Symfony Fundamentals
course - it's a really cool feature.

The short explanation is that this class has a `getSusbcribedServices()` method
where we can choose which services we need. These are then included in this
`$container` object and we can fetch them out by saying `$this->container->get()`.

Add `UploaderHelper::class` to the array. Then, above, we can
`return $this->container->get(UploaderHelper::class)` then
`->getPublicPath($path)`.

Let's give it a try! Refresh! We got it! That took some setup, but I promise you'll
be *super* happy you did this.

Next: let's also update the image path in the show page, and learn a bit about
what the `asset()` function does internally and how we can do the same thing
automatically in `UploaderHelper`.
