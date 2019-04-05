# S3 Asset Paths

Hey! Flysystem is *now* talking to S3! We know this because we can see the
`article_image` directory and all the files inside of it. But when we went back
to the homepage and refreshed, nothing worked!

Check out the image `src` URL: this is *definitely* wrong, because this *now* needs
to point to S3 directly. But! Things get even *more* interesting if you go back
to the S3 page and refresh. We have a `media/` directory! And if you dig, there
are the thumbnails! Woh!

This means that this thumbnail request *did* successfully get processed by a Symfony
route and controller and it *did* correctly grab the *source* file from S3, thumbnail
it and write it *back* to S3. That's freaking cool! And it worked because we already
made LiipImagineBundle play nicely with Flysystem. We told the "loader" to use
Flysystem - that's the thing that downloads the source image when it needs to
thumbnail it - *and* the resolver to use Flysystem, which is the thing that
actually saves the final image.

## Correcting our Base URL

So if our system is working so awesomely... why don't the images show up? It's
because of the *hostname* in front of the images: it's pointing at our local
server, but it *should* be pointing at S3.

Click any of the images on S3. Here it is: every object in S3 has its own, public
URL. Well actually, every object has a URL, but whether or not anyone can *access*
that URL is another story. More on that later. I'm going to copy the very beginning
of that, and then go open `services.yaml`. Earlier, we created a parameter called
`uploads_base_url`. LiipImagineBundle uses this to prefix every URL that it renders.
The current value includes `127.0.0.1:8000` because that's our `SITE_BASE_URL`
environment variable value. That worked fine when things were stored locally...
but not anymore!

Change this to `https://s3.amazonaws.com/` and then our bucket name, which is
already available as an environment variable: `%env()%`, then go copy `AWS_S3_ACCESS_BUCKET`,
and paste.

[[[ code('7f573eb4bb') ]]]

*This* is our new base URL. What about the `uploads_dir_name` parameter? We're
not using that at *all* anymore! Trash it.

Ok, let's try it! Refresh and... it actually works! I mean... of course, it works!

## Correcting the Absolute URLs

There's one other path we need to fix: the absolute path to uploaded assets that
are *not* thumbnailed. Open up `src/Service/UploaderHelper.php` and find the
`getPublicPath()` method... there it is. *This* is a super-handy method: it allows
us to get the full, public path to any uploaded file. This `$publicAssetBaseUrl`
property... if you look on top, it comes from an argument called `$uploadedAssetsBaseUrl`.
And in `services.yaml`, *that* is bound to the `uploads_base_url` parameter... that
we just set!

There are a few layers, but it means that, in `UploaderHelper` the `$publicAssetBaseUrl`
property is *now* the long S3 URL, which is *perfect*!

Head back to down `getPublicPath()`. *Even* before we changed `uploads_base_url`
to point to S3, we were *already* setting it to the absolute URL to our domain...
which means that *this* method already had a subtle bug!

Check it out: the original purpose of this code was to use
`$this->requestStackContext->getBasePath()` to "correct" our paths in case our
site was deployed under a sub-directory of a domain - like `https://space.org/thespacebar`.
In that case, `getBasePath()` would equal `thespacebar` and would automatically
prefix all of our URLs.

But ever since we started including the full domain in `$publicAssetBaseUrl`, this
would create a broken URL! We could remove this. Or, to make it *still* work if
`$publicAssetsBaseUrl` happens to *not* include the domain, above this, set
`$fullPath = `, copy the path part, replace that with `$fullPath`, and paste.

[[[ code('bc50111e3b') ]]]

Then, if `strpos($fullPath, '://') !== false`, we know that `$fullpath` is already
absolute. In that case, return it! That's what our code is doing. But if it's
*not* absolute, we can keep prefixing the sub-directory.

[[[ code('58e18ed956') ]]]

Hey! The files are uploading to S3 and our public paths are pointing to the new
URLs *perfectly*. Next, we can simplify! Remember how we have one public filesystem
and one private filesystem? With S3, we only need one.
