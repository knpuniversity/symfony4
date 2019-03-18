# Streaming the File Download

We have a method that will allow us to open a *stream* of the file's contents. But...
how can we send that to the user? We're used to returning a `Response` object or
a `JsonResponse` object where we already have the response as a string or array.
But if you want to *stream* something to the user without reading it all into
memory, you need a special class called `StreamedResponse`.

Add `$response = new StreamedResponse()`. This takes one argument - a *callback*.
At the bottom, return this.

Here's the idea: we can't just start streaming the response or echo'ing content
right now inside the controller: Symfony's just not ready for that yet, it has
more work to do, more headers to set, etc. That's why we *normally* create a Response
object and *later*, when it's ready, Symfony echo's the response's content for us.

With a `StreamedResponse`, when Symfony is ready to finally send the data, it
executes our callback and then we can do *whatever* we want. Heck, we can
`echo 'foo'` and that's what the user would see.

Add a `use` statement and bring `$reference` and `$uploaderHelper` into the
callback's scope so we can use them. To send a file stream to the user, it looks
a little strange. Start with `$outputStream` set to `fopen('php://output')`
and `wb`.

We *usually* use `fopen` to write to a file. But this special
`php://output` allows us to write to the "output" stream - a fancy way of saying
that anything we write to this stream will just get "echo'ed" out. Next, set `$fileStream` to `$uploaderHelper->readStream()` and pass this the path to the
file - something like `article_reference/symfony-best-practices-blah-blah.pdf`.

Oh, except, we don't have an easy way to do that yet! In our `Article` entity, we
added a nice `getImagePath()` method that read the constant from `UploaderHelper`
and added the filename. I like that.

Let's copy that and go do the exact same thing in `ArticleReference`. At the bottom,
paste and rename this to `getFilePath()`. Let's add a return type too - I probably
should have done that in `Article`. Then, re-type the `r` on `UploaderHelper`
to get the `use` statement, change the constant to `ARTICLE_REFERENCE` and update
the method call to `getFilename()`.

Great! Back in the controller, pass `$reference->getFilePath()` and then `false`
for the `$isPublic` argument.

*Finally*, now that we have a "write" stream and a "read" stream, we can use
a function called `stream_copy_to_stream()` to... do exactly that! Copy
`$fileStream` to `$outputStream`.

There ya go! The fanciest way of echo'ing content that you've probably ever seen,
but it *avoids* eating memory.

## Setting the Content-Type

Try it out! Refresh and... it works... sort of. We *are* sending the file
contents... but the browser is *clearly* not handling it well. The reasons is that
we haven't told the browser what *type* of file this is, so it's just treating it
like the world's ugliest web page.

And... hey! Remember when we stored the `$mimeType` of the file in the database?
Whelp, that's about to come in handy... big time! Add
`$response->headers->set()` with `Content-Type` set to `$reference->getMimeType()`.

Try it again. Hello PDF!

## Content-Disposition: Forcing Download

Another thing you might want to do is *force* the browser to download the file. It's
really up to you. By default, based on the `Content-Type`, the browser may try to
open the file - like it is here - or have the user download it. To force the browser
to *always* download the file, we can leverage a header called
`Content-Disposition`.

This header has a very specific format, so Symfony comes with a helper to create
it. Say `$disposition = HeaderUtils::makeDisposition()`. For the first argument,
we'll tell it whether we want the user to download the file, or open it in the
browser by passing `HeaderUtils::DISPOSITION_ATTACHMENT` or `DISPOSITION_INLINE`.
Next, pass it the *filename*.

This is *especially* cool because, without this, the browser would probably try
to call the file... just... "download" - because that's the last part of the URL.
Now it will use `$reference->getOriginalFilename()`.

Before we set this header, I just want you to see what it looks like. So,
`dd($disposition)`, move over, refresh and... there it is. It's just a string, like
any other header - but it has this specific format, which is why Symfony has a
helper method.

Set this on the actual response with
`$response->headers->set('Content-Disposition', $disposition)`.

Try it one more time. Yes! It downloads *and* uses the original filename.

Next: let's make this all *way* cooler by uploading instantly via AJAX.
