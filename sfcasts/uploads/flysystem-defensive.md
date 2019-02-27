# Flysystem: Streaming & Defensive Coding

There are a few minor problems with our new `Flysystem` integration that I want
to clean up before they bit us!

## Streaming

The first issue is that using `file_get_contents()` eats memory: it reads the entire
contents of the file into PHP's memory. That might not be a huge deal for tiny files,
but it *could* be a big deal if you start uploading bigger stuff. And, it's just
not necessary.

For that reason, in general, when you use `Flysystem`, instead of using methods
like `->write()` or `->update()`, you should use `->writeStream()` or `->updateStream()`.

It works the same, except that we need to pass a *stream* instead of the contents.
Create the stream with `$stream = fopen($file->getPathname())` and, because we just
need to *read* the file, you can use the `r` flag. Now pass stream instead of the
contents.

Yea... that's it! Same thing, but no memory issues. After, we *do* need to add one
more detail: if `is_resource($stream)`, then `fclose($stream)`. The "if" is needed
because, when you use *some* Flysystem adapters, the adapter itself closes the
stream.

## Deleting the Old File

Ok, for problem number to, go back to `/admin/article`. Log back in with password
`engage`, edit an article, and go select an image - how about `astronaut.jpg`. Hit
update and... it works! So what's the problem? Well, we just *replaced* an existing
image with this new one. Does the old file still exist in our uploads directory?
Absolutely - and it probably shouldn't. When an article image is updated, let's
delete the old file.

In `UploaderHelper`, add a second argument - a *nullable* string argument called
`$existingFilename`. This is nullable because sometimes there may *not* be an existing
file we need to delete. At the bottom, it's beautifully simple: if an
`$existingFilename` was passed, then `$this->filesystem->delete()` and pass that
the full path, which will be `self::ARTICLE_IMAGE.'/'.$existingFilename`.

Done! You can see the astronaut file that we're using right now. Oh, but first,
head over to `ArticleAdminController`: we need to pass this new argument.
Let's see - this is the `edit()` action - so pass `$article->getImageFilename()`.
In `new()`, you can really just pass `null` - there will *not* be an article image.
But I'll pass `getImageFilename()` just for consistency.

Oh, and there's one other place we need update: `ArticleFixtures`. Down here, just
pass `null`: we are never updating some existing image.

Let's try it! Here is the current astronaut image. Now, move over, upload `rocket.jpg`
this time and update! Back in the directory... there's rocket and astronaut is gone!
Love it!

## Avoiding Errors

Now, in a *perfect* system, the existing file should *always* exist, right? I mean,
how else would it get there? But what if we're developing locally - and maybe we're
clearing out the uploads directory during out tests. In that case, it's possible
that the existing file is *not* there.

Try this: empty the `uploads/` directory. Now, upload the form. The image still
shows up because we're looking at the thumbnail file - but the original image is
totally gone. Now select `earth.jpeg`, update and... it fails! It fails on
`$this->filesystem->delete()`.

This *may* be the behavior you want: if something weird happens and the old file
is gone, *please* explode so I know. But, I'm going to propose something slightly
different. If the old file doesn't exist for some reason, I don't want the entire
process to fail - it really doesn't need to.

The error from Flysystem is a `FileNotFoundException` from  `League\Flysystem`.
In `UploaderHelper` wrap that line in a try-catch. Let's catch that
`FileNotFoundException` - the one from `League\Flysystem`

## Logging Problems

That'll fix that problem... but I don't *love* doing this. Honestly, I *hate*
silencing errors. One of the benefits of throwing an exception is that we can
configure Symfony to notify us via the logger. At SymfonyCasts, we send all errors
to a Slack channel so we know if something weird is going on... not that we *ever*
have bugs. Pfff.

Here's what I propose: a *soft* failure: we don't fail, but we *do* log that an
error happened. Back on the constructor, autowire a new argument:
`LoggerInterface $logger`. I'll hit `Alt + Enter` and select initialize fields to
create that property and set it. Now, down in the catch, say
`$this->logger->alert()` - alert is one of the highest log levels and I usually
send all logs that are this level or higher to a Slack channel. Inside, how about:
"Old uploaded file %s was missing when trying to delete" - and pass
`$existingFilename`.

Thanks to this, the user gets a smooth experience, but *we* get notified so we
can figure out how the heck the old file disappeared.

Move over and re-POST the form. *Now* it works. And to prove the log worked,
check out the terminal tab where we're running the Symfony web server: it's streaming
all of our logs here. Scroll up and... there it is!

> Old upload a file "rocket..." was missing when trying to delete

## Checking for Filesystem Failure

Ok, there's *one* more thing I want to tighten up. If one of the calls to the
`Filesystem` object fails... what do you think will happen? An exception? Hold
Command or Ctrl and click on `writeStream()`. Check out the docs: we *will* get
an exception if we pass an invalid stream or if the file already exists. But for
any other type of failure, maybe a network error... instead of an exception, the
method just returns false!

Actually, that's not *completely* true - it depends on your adapter. For example,
if you're using the S3 adapter and there's a network error, it *may* throw its
own type of exception. But, the point is this: if any of the Filesystem methods
fail, you might *not* get an exception: it might just return false.


For that reason, I like to code defensively. Assign this to a `$result` variable.
Then say: `if ($results === false)`, let's throw our own exception - I *do* want
to know that something failed:

> Could not write uploaded file "%s"

and pass `$newFilename`. Copy that and do the same for `delete`:

> Could not delete old uploaded file "%s"

with `$existingFilename`. I'm *throwing* this error instead of just logging something
because this would *truly* be an exceptional case - we shouldn't let things continue.

Let's make sure this all works - move over and select the `stars` file - or...
actually the "Earth from Moon" photo. Update and... got it!

Next: let's teach LiipImagineBundle to play nice with Flysytem. After all, if we
move Flysystem to S3, but LiipImagineBundle is still looking for the source files
locally... well... it's not going to work great.
