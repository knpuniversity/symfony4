# File Uploads & Data Fixtures

Open up `src/DataFixtures/ArticleFixtures.php`. Here's how this works: this function
creates 10 articles whenever we run `bin/console doctrine:fixtures:load`. It's a cool
helper we created in our Symfony series. But, the `setImageFilename()` stuff
is now a problem. We know that the image filename needs to be the name of a file
that lives inside of the `uploads/article_image` directory - something like
`astronaut-blah-blah.jpg`. Right now, the fixtures use faker to select a random
item in `$articleImages` - this private property. So, it's setting `imageFilename`
to either `asteroid.jpeg`, `mercury.jpeg` or `lightspeed.png`.

This worked before because those images are committed to our repository in the
`public/images` directory and we were pointing to *that* path in our template.
When we run `doctrine:fixtures:load`, it *does* create 10 Article objects and
it *does* set the image filename to one of these three filenames. But on the
homepage... it doesn't work! There is no `upload/article_image/lightspeed.png`
file. We need to re-think how this works.

## Faking the File Upload

How? By *faking* the file upload inside the fixtures. It's kinda...beautiful!
Our `UploaderHelper` service is already really good at moving things into the
right spot - why not reuse it here?

Inside `ArticleFixtures`, create a `public function __construct()`. Add an
`UploaderHelper $uploaderHelper` argument and I'll hit `ALT + Enter` and select
initialize fields to create that property and set it.

Next, lets "cut" the 3 files in the `public/images` directory: we're going to
move them to a different spot, because they no longer need to be publicly
accessible. You'll see what I mean. In the `src/DataFixtures` directory, create
a new folder here called `images/` and paste them! Yep! They are no longer in
the `public/images/` directory.

Because these test images *are* committed to git, I'm going to commit this move -
it'll help us in a minute when things... ah... sorta go wrong horribly wrong.
Yes! We are planning for disaster!

Here's the idea: we'll use the `UploaderHelper` down here, point it at one of these
3 files, and have it, sort of, "fake" upload it. Start with `$randomImage =`, copy
the faker code, and paste. This is now one of the three random image filenames.
Next, in `UploaderHelper`, what I'd *like* to do is call `uploadArticleImage()`
and basically say:

> Hey! *Pretend* like `asteroid.jpeg` is a file that was just uploaded. And... ya
> know... do all your normal stuff and move it into the `uploads/` directory.

This is easier than you think: in the fixtures class, set `$imageFilename` to
`$this->uploaderHelper->uploadArticleImage()`. What I *want* to do is now say
`new UploadedFile()` and point it at one of the images. The *problem* is that
you can't really create a fake `UploadedFile` object. Internally, it's *bound*
to the PHP uploading process - weird stuff will happen if you try to create
one *outside* of that context.

## Hello File Object

That's ok! It just means we need to dig deeper! Go back into `UploaderHelper`.
Hold Command or Ctrl and click to open the `UploadedFile` class. This lives in
the `Symfony\HttpFoundation\File` namespace and *extends* a class called
`File` that lives in the same directory.

The `File` class is awesome: it simply represents... *any* file on your filesystem,
regardless of whether it's an uploaded file or just a normal file. And, if you
look closely, the *vast* majority of the methods we've been using come from
*this* class - *not* from `UploadedFile`. And we *can* create a `File` object
outside of an upload context.

So back in `ArticleFixtures`, instead of creating a `new UploadedFile()`, say
`new File()` - the one from `HttpFoundation`. Pass this the path to the random
image: `__DIR__.'/images/'` and then `$randomImage`, which will be one of these
image filenames.

Now, take `$imageFilename` - that'll be whatever the final filename is on the system
after moving it, and set that onto the entity.

That's beautiful! In `UploaderHelper`, we need to make this work *not* with an
`UploadedFile` object, but with the parent `File`. Change the type-hint to
`File` - again, make sure you get the one from `HttpFoundation` or you will have
*no* fun. To keep things clear, I'll Refactor -> Rename this variable to `$file`.

Let's see: everything looks happy, ah - except for `getClientOriginalName()`: that
method does not exist in `File` - it only exists in `UploadedFile`. Ok, let's get
fancy then: if `$file` is an `instanceof UploadedFile`, we can say
`$originalFilename = $file->getClientOriginalName()`. Else, set `$originalFilename`
to `$file->getFilename()` - that's just the name of the file on the filesytem.

After this, delete the `pathinfo()` stuff - we can move that to the next line.
Inside `urlize()`, re-add the `pathinfo()` and pass the same second argument:
`PATHINFO_FILENAME`.

I think that's all we need! Let's completely clear out the `uploads/` directory.
Now, find your terminal and run:

```terminal
php bin/console doctrine:fixtures:load
```

## Copying the Files Before Moving

Woh! The file `src/DataFixtures/images/asteroid.jpeg` does not exist? Hmm. Check
this out: it *did* upload two files before going all "explody" on us. Oh, but
those original files are missing! Of course! We're using `$file->move()`. So
it *is* working, but instead of copying the files, it's moving them, and the
originals are disappearing.

Let's get those files back. Run:

```terminal
git status
```

And undelete them with:

```terminal
git checkout src/DataFixtures/images
```

Much better. Let's clean out the uploads directory again.

We *do* want to use `$file->move()` because we *do* want to move the uploaded file
in normal circumstances. So, to get around this, in the fixtures, let's copy the
original file to a temporary spot. Start with `$fs = new Filesystem()` - that's
a handy object for doing filesystem operations. Next,
`$targetPath = sys_get_temp_dir().'/'.$randomImage`. And then use `$fs->copy()`.
We want to copy the original file path into `$targetPath`. Inside `File`,
pass the temporary path.

Ok, let's try it again!

```terminal-silent
php bin/console doctrine:fixtures:load
```

No error, our original files still exist and... we have a directory full of, fake
uploaded files. *Now* try the homepage. Beautiful. What I *really* love about this
is that we're not doing anything fancy or tricky in our fixtures: we're literally
using our upload system.

## Cleanup into a Private Method

Though, I don't love having *all* of this logic right in the middle of this
already-long function: it's not super obvious what it does. Let's do some
cleanup: copy all of this. And at the bottom, create a new
`private function fakeUploadImage()` that will return a `string`. Paste all that
logic and return the `$this->uploaderHelper` line. It selects a random image,
uploads it and returns the path. Back up top, delete all this stuff and say
`$imageFilename = $this->fakeUploadImage()`.

Let's run those fixtures one more time!

```terminal-silent
php bin/console doctrine:load:fixtures
```

When it finishes... we have some new files... and the homepage is shiny! That's
a solid fixture system.

Next: we'll take our first step towards storing uploaded files in the cloud by
integrating the gorgeous Flysystem library.
