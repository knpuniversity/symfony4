# Signed Urls

Coming soon...

There's one other performance enhancing that we can do and it relates to download
them. So right now if I hit download a, it works fine, but if you know, if these were
got, if these were bigger files, you would notice is that these would actually start
to slow down a little bit. And the reason is that in our `ArticleReferenceAdminController`
search for download, you remember we're actually, you're reading a stream
from S3 and sending that directly to the user. But this also, there's still
doesn't mean there's sort of a middleman or server has to stream from S3 and
then we have to send that to the user. There's no real reason to do that. We should
just be able to have the user download the file directly from S3. The problem is
that the, the URLs are not public number. These are private files. So if we just go
into our, um,

okay,

our bucket, I'll refresh the homepage of our bucket and go into article reference. We
can click these files, they have a public URL, but if we go to it, it's not really
public. It's access to tonight so it doesn't work. We can solve this by using
something called a signed URL and this is a time when we're going to do something
with our kind of remote filesystem that goes beyond what Flysystem to do can do. And
so we're going to have to deal with directly with S3 a itself, which actually
turns out to be pretty easy. Google for "S3 PHP client signed url" and
you'll find their documentation about this. So it's pretty cool. You can basically do
is you can say, hey S3 I want to make a get request to download this object.
Um, and then you can basically say I want you to create a signed URL that is valid
for 20 minutes switch you can do is you can create a download link that anyone can
access to download a file but are only exists for a short amount of time. Now to do
this, we need to interact directly with the S3 clients. And may remember that a
few minutes ago we actually created an S3 client so that we can use it with 
Flysystem. So awesome. So we can just use that. The other thing we're going to need to
do is we're going to need our bucket names that when we actually run this object
code, here we have the bucket name.

So in article reference in `downloadArticleReference()`, let's add another argument
here.

Yeah,

removed the `$uploaderHelper` we're actually not going to need that anymore. And
that's three and said say `S3Client $s3client`. And then we're also going to need 
these `string $s3BucketName` and this will not auto wire. So I'm going to copy 
that and go to my `services.yaml` and add a bind for this. So I'm going to buy an 
`$s3BucketName:` to our

okay.

And I'll need the bucket name. So I'll copy the environment variable syntax for that.
There we go. So now I can get the bucket name. Uh, next I want you to copy the
`$disposition =` line because we're going to put that back in a second. But otherwise
delete everything inside of here. And actually if you want to, I'll put the
disposition back. I'll come in and out cause I'm the copies and other stuff.

Uh,

I'll put that back so that I can actually go and copy these a few lines of code from
their documentation. Perfect. So `$s3Client->getCommand('getObject', [])` my bucket or
replace that with `$s3BucketName` and then the key, that's like the path to the
file. So this is going to be `$reference->getFilePath()`,

then `$request = $s3Client->createPresignedRequest()`, you can use whatever
you want here. Um, it's a pretty small file. You don't need much time. And then
finally, what we also want to do is create a pre-sign URL. So we can do here is we
can take that request and we can turn that into a, you were out. So it's going to
give us a big long, you were held that will temporarily be valid to download that.
And what we're done going to do is actually just redirected user's browser to that
URL. So down here we can say `return new RedirectResponse()`, and then the
code that they had over here was to typecast `(string) $request->getUri()`

and that should be it. Let's try it. Let's go back. We don't really need to refresh
the page, but we'll do it to be safe and let's download our best practices. Boom.
Well sort of first thing it did work. That's awesome. Um, but it didn't have as
download it like we did before and that's because we lost the content disposition
header that we were setting. But check this out. It's pretty cool. It's actually just
the, you were out to our specific file, but then the sign you were l Amazon put a
special signature on the end of this and that's going to make this URL a valid for
only a short period of time. This is an awesome way to make private files temporarily
public. All right, so what about our content disposition? Now you know where I can
actually hit this and it says download what we can put that back.

One of the cool things is that there are lots of options you can pass to this, uh,
last argument. Um, and you also have options where you can control, well you want to
do is basically say, hey S3, I want you to allow the user to access his file.
And when you do, here are a few response headers that I want you to send to the
client. So for example, if we want, we can, one of the keys we can do here is we can
say `ResponseContentType` as an option. They have to set the `Content-Type` header
and here we can use `$reference->getMimeType()`. The other one we can do is
`ResponseContentDisposition`. And that's where I'll grab our disposition code down
here, move that back up above our command and we can set that to the `$disposition`. So
that should cause those same two headers to come back as they did before. It's now
when we hit download, boom, it actually downloads that. So that's the way that I
really recommend having users download files of any significant size from S3

mmm.

You can also do the same thing with cloudfront. Cloudfront is another service that
gives me even a faster access to S3 process is fair to similar, et Cetera, et
cetera. I'll talk about that later.