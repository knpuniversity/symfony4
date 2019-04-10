# Private Downloads & Signed URLs

I have *one* more performance enhancement I want to do. If you click download,
it works great! But if these files were bigger, you'd start to notice that the
downloads would be kinda slow! Open up `ArticleReferenceAdminController` and search
for download. Remember: we're reading a stream from S3 and sending that directly
to the user. That's cool... but it also means that there's a middleman in the process:
our server! That slows things down. Couldn't we somehow give the user direct access
to the file on S3?

Go back to our bucket, head to its root directory, then click into `article_reference`.
If you click any of these files, each *does* have a URL. But if you try to go to
it, it's not public. That's *great* because these files are *meant* to be private...
but it sorta ruins our idea of pointing users directly to this URL.

Well, good news! We *can* have our cake and eat it too... as we say... for some
reason in English. Um, we *can* have the best of both worlds with... signed URLs.

## Hello Signed URLs

Signed URLs are *not* something that we can create with Flysystem - it's specific
to S3. So, instead of using our Filesystem object, we'll deal with S3 directly,
which turns out to be pretty awesome!

Google for "S3 PHP client signed url" to find their docs about this. Signed URLs
let *us* say:

> Hey S3! I want to create a public URL to download this file... but I only want
> the link to be valid for, like, 20 minutes.

Cool, right! Because the link is temporary, it's ok to let users use it.

We'll do this by interacting with the `S3Client` object directly... which is super
*awesome* because, a few minutes ago, we registered an `S3Client` service so we
could use it with Flysystem. Half our job is already done!

The other thing we'll need is the bucket name.

## Creating the Signed URL

Head back to `downloadArticleReference()`. Remove the `UploaderHelper` argument -
we won't need that anymore - and add `S3Client $s3client`. Also add `string $s3BucketName`.

[[[ code('cd38c547ff') ]]]

That won't autowire, so copy the argument name, open up `services.yaml` and add a
bind for this `$s3BucketName:`. For the value, copy the environment variable bucket
syntax from before and... paste.

[[[ code('f6729fbf58') ]]]

Cool! Back in the controller, copy the `$disposition` line - we're going to put
this back in a minute. Then, delete *everything* after the security check, paste
the `$disposition` line, but comment it out for now.

Ok, let's go steal some code from the docs! We already have the `S3Client` object,
so just grab the rest. Paste that then... let's see... replace `my-bucket` with
the `$s3BucketName` variable. For `Key`, that's the *file* path:
`$reference->getFilePath()`. And, for `$request = $s3Client->createPresignedRequest()`,
you can use whatever lifetime you want. These files are pretty small, so we don't
need too much time - but let's make the URLs live for 30 minutes.

[[[ code('0c13e7cd39') ]]]

Now that we have this "request" thing... how can we get the URL? Back on their docs,
scroll down... here it is: `$request->getUri()`.

When the user hits our endpoint, what *we* want to do is *redirect* them to the
URL. Do that with `return new RedirectResponse()`, `(string)` - they mentioned
that in the docs, it turns the URI into a string - then `$request->getUri()`.

[[[ code('e6b41ac2e7') ]]]

Let's try it! Refresh! And... download! Ha! It works! We're loading this directly
from S3. This long URL contains a signature that proves to S3 that the request
was pre-authenticated and should last for 30 minutes.

## Forcing S3 Response Headers

But we *did* lose one thing: our `Content-Disposition` header! This gave us two
nice things: it forced the user to download the file instead of loading it "inline",
*and* it controlled the download filename.

Hmm, this is tricky. Now that the user is no longer downloading the file directly
from us, we don't really have a way to set custom *headers* on the response. Well,
actually, that's a big ol' lie! There are *two* ways to do that. First, you can set
custom headers on each object in S3. *Or* you can *hint* to S3 that you want *it*
to set custom headers on your behalf when the user goes to the signed URL.

How? Add another option to `getCommand()`: `ResponseContentType` set to
`$reference->getMimeType()`. That'll hint to S3 that we want it to set a `Content-Type`
header on the download response. 

[[[ code('a2045b8a47') ]]]

And `ResponseContentDisposition`. Move the `$disposition` code up above, then 
use that value down here.

[[[ code('40358975ff') ]]]

Cool, right? Go download the file one more time. Ha! It downloads *and* uses the
original filename. This is probably the best way to allow users to download private
files. Oh, and if you need even *faster* downloads... cause S3 isn't *that* fast
for large files, you can do the same thing with Cloudfront. Cloudfront is another
service that gives users faster access to S3 files, and has a similar process for
creating signed URLs.

Ok friends, only *one* thing left, and it's a fun one! Let's talk about how our
file upload endpoint *might* look different if we were building a pure API.
