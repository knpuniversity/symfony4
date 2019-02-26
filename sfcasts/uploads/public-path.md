# URL to Public Assets

The hardest part of handling uploads... probably isn't the uploading part! For
me, it's rendering the URLs to the uploaded files, thumbnailing and creating endpoints
to download *private* files. Oh, and we *gotta* keep this organized: I do *not*
want a bunch of upload directory names sprinkled over 50 files in my code. It's bad
for sanity, I mean, *maintenance*, and will make it hard to move your uploads to
the cloud later... which we *are* going to do.

Look back at the homepage: all of these images work except for one. But, *this*
is actually the image that we uploaded! Inspect element on that and check its path:
`/images/astronaut-blah-blah.jpeg`. Check out one of the working images. Ah yes:
until now, in the fixtures, we set the `$imageFilename` string to one of the filenames
that are hardcoded and committed into the `public/images/` directory, like `asteroid.jpeg`.

These aren't *really* uploaded assets: we were just faking it! Check out the template:
`templates/article/homepage.html.twig`. There it is! We're using the `asset()`...
ah, wrong spot. Here we go: we're saying `{{ asset(article.imagePath) }}`, which
calls `getImagePath()` inside `Article`. That just prefixes the filename with
`images/` and returns it! So if `imageFilename` is `asteroid.jpeg` in the database,
this returns `images/asteroid.jpeg`.

## Pointing the Path to uploads/

Now that the *true* uploaded assets are stored in a different directory, we can
just update this path! In `Article`, change this to `uploads/article_image/`
and then `$this->getImageFilename()`.

Cool! Try it out! It works! We don't care about the broken images from the fixtures:
we'll fix them soon. But the *actual* uploaded image *does* render.

## Getting Organized

*Great* first step. Now, let's get organized! One problem is that we have
the directory name - `article_image` - in `Article` and *also* in `UploaderHelper`
where we move the file around. That's not too bad - but as we start adding more file
uploads to the system, we're going to have more duplication. I don't like having
these important strings in multiple places.

So, in `UploaderHelper`, why not create a constant for this? Call it `ARTICLE_IMAGE`
and set it to the directory name: `article_image`. Down below, use that:
`self::ARTICLE_IMAGE`. And in `Article`, do the same thing:
`UploaderHelper::ARTICLE_IMAGE`.

Small step, and when we refresh, it works fine.

## Centralizing the Public Path

Let's keep going! Back in `Article`, the path starts with `uploads`... because that's
part of the public path to the asset. That's not a huge problem, but I actually
*don't* want that `uploads` string to live here. Why? Well, I kinda don't want my
entity to really care *where* or *how* we're storing our uploads. Like, if our
site grows and we move our uploads to the cloud, we would need to change this
`uploads` string to a full CDN URL in *all* entities with an upload field. And,
that URL might even need to be dynamic - we might use a different CDN locally
versus on production! Nope, I don't want my entity to worry about any of these
details.

Remove the `uploads/` part from the path. Now `getImagePath()` returns the path to
the image relative to wherever our *app* decides to store uploads. In `UploaderHelper`,
add a new `public function getPublicPath()`. This will take a string `$path` - that
will be something like `article_image/astronaut.jpeg` - and it will return a string,
which will be the *actual* public path to the file. Inside, `return 'uploads/'.$path;`.

That may feel like a micro improvement, but it's awesome! Thanks to this, we can
call `getPublicPath()` from anywhere in our app to get the URL to an uploaded asset.
If we move to the cloud, we only need to change the URL here! Awesome!

## uploaded_asset() Twig Extension

Except... how can we call this from Twig? Because, if we refresh right now... it
definitely does *not* work. No worries: let's create a custom Twig function.
Open `src/Twig/AppExtension` - this is the Twig extension we created in our Symfony
series. Here's the plan: in the homepage template, instead of using the `asset()`
function, let's use a new function called `uploaded_asset()`. We'll pass it
`article.imagePath` - and it will ultimately call `getPublicPath()`.

In `AppExtension`, copy `getFilters()`, paste and rename it to  `getFunctions()`.
Return an array, and, inside, add a `new TwigFunction()` with `uploaded_asset`
and `[$this, 'getUploadedAssetPath']`. Copy that new method name, scroll down
and add it: `public function getUploadedAssetPath()` with a `string $path` argument.
It will also return a string.

## Using a Service Subscriber

Inside: we need to get the `UploaderHelper` service so we can call `getPublicPath()`
on it. Normally we do this by adding it as an argument to the constructor. But,
in a few places in Symfony, for performance purposes, we should do something *slightly*
different: we use what's called a "service subscriber", because it allows us to
fetch the services lazily. If this is a new concept for you, go check out our Symfony
Fundamentals course - it's a really cool feature.

The short explanation is that this class has a `getSubscribedServices()` method
where we can choose which services we need. These are then included in the
`$container` object and we can fetch them out by saying `$this->container->get()`.

Add `UploaderHelper::class` to the array. Then, above, we can
`return $this->container->get(UploaderHelper::class)->getPublicPath($path)`.

Let's give it a try! Refresh! We got it! That took some work, but I promise you'll
be *super* happy you did this.

Next: let's also update the image path in the show page, and learn a bit about
what the `asset()` function does internally and how we can do the same thing
automatically in `UploaderHelper`.
