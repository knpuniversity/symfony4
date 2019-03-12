# S3 Public Private

Coming soon...

We know that we now have Flysystem talking to S3, because we can see an
article, image directory and everything's uploaded. But then when I went back to the
homepage and refreshed, nothing worked. And what's interesting is,

okay,

the you were out here is obviously wrong because this needs to be pointing at S3
But the really interesting thing is if you go back to the root of our, um,
bucket and refresh, oh there is actually a `media/` directory,

the thumbnail, he did work. So basically, uh, this request went into a Symfony, a
route one controller, it did correctly. Grab our source file from S3, thumbnail
it and write it back to S3. That's because if we set up things correctly,
earlier in leap imagined bundle at you, remember we did the work of setting up the
loader, which is the thing that actually downloads the image when it needs to
thumbnail it. And the resolver, which is the thing that actually saves the final
image. We set both of those up with Flysystem. So the system is actually working. So
why don't these images actually load? Well because of the public path here, because
for some reason it's actually pointing at `127.0.0.1:8000` when it
should be pointing at an S3 you were out. Um, if you click any of these images,
you can see every URL has a, every, every object has an s three you were out that you
can use. In fact, I'm going to copy the very beginning of that.

Okay.

And what we need to do is go into our `services.yaml` file. And you remember we created
a parameter here called `uploads_base_url`. This is what needs to change.
This `uploads_base_url` is used by LiipImagineBundle and it's how it knows
how to prefix every single URL when it finally does the rendering. So right now it's
using `127.0.0.1:8000` because that is our site base.
You well and that worked fine when we are actually storing things locally. So the
cool thing is that we can change this now.

To `https://s3.amazonaws.com/` and then we're actually going to use the bucket name. So
we will say `%env()%`, and I'll go grab our `AWS_S3_ACCESS_BUCKET`
That is our new base URL. And if you look this `uploads_dir_name`
we're not actually using that anymore so we can totally get rid of that. And
when we refresh uhmm it actually works. So next I want you to go into the admin
section and /admin /article you'll need to log in. Again, password, engage and a
quick to edit any of these. The first thing I want you to see is if you actually the
thumbnail storing five to fix. You click this, it's access denied.

And before we keep going, this one other little thing I want to fix, it's not
actually a problem for us, but if you look in `src/ServiceUploaderHelper`, you
remember we have a method in here called, there it is. `getPublicPath()`. This is the
method that you can actually use if you want like the full public path, uh, to the
asset without any thumbnailing. This `$publicAssetBaseUrl` argument. If you look
on top, that actually comes from an argument called `$uploadedAssetsBaseUrl`,
which is actually set to `uploads_base_url`. So it was a few layers going on here,
but basically that's the parameter we just said. It's an `UploaderHelper` are 
`$publicAssetBaseUrl` is now going to be this long S3 URL, which is great.
Even before it was actually our, um, our full, uh, host name.

So I should have fixed this earlier, but actually this `getPublicPath()` code. Here's a
little bit strange because the way we've been using things, this is always an
absolute URL. So the `->requestStackContext->getBasePath()` thing was something that we
added. Uh, in case your site was, uh, deployed under a sub directory. We can really
remove this code if we want to. Now we've now made it so that we're always going to
put the absolute url out here, but if you want to like play it safe, we could
actually do something like this. We could first get the full path, which is equal to
this stuff down here. And then we'd say, Hey, let's actually check to see if the full
path `strpos($fullPath, '://') !== false`. Then we know for sure that
the `$fullpath` is absolute. So if it's already absolute just return. In our case it's
always absolute. But you know, if you want to be able to switch back between having
local URLs and uh, absolutely. Where else you can do this.

All right, next

when you look at our admin section, so go to `/admin/article`, you'll need a log in
back in again, `admin1@thespacebar.com` password `engage` and go ahead and edit
any of these. All right, so obviously everything here works just fine.

Yeah,

I'll select a couple of references to upload. You're not suppose to take a little bit
longer because they're making requests to S3 in the background. Perfect. There
we go. And can we download these? Let's try it. Hit download and yeah, and downloads
just fine. And the reason that works is because inside of our 
`ArticleReferenceAdminController` you search for download inside of here you remember we are reading from s
three we opened a stream from Flysystem, so from S3 and we're sending that
back. So we planned ahead. Everything just works nicely. We don't have to make a lot
of changes to our code but there is one problem. You clicked this image and we set it
up so that you click this image, it will go directly to the full image of that
version of that file and Huh? Access denied. If you look at the URL that looks right.
So the, the key thing is here is that an S3 when you upload a file you can make
it publicly accessible or private and by default files are private. In fact, the only
reason that we're even able to see the thumbnails you see this also uses the S3
URL except that this URL

does work. Is that the LiipImagineBundle? It's smart enough to make sure that when
it uploads it's thumbnails, it uploads them as public, but by default when he used
Flysystem or use S3 in general is going to upload them as private. So to fix
that we need to go into her `UploaderHelper` and go down into our `uploadFile()`. So so
far we've been passing, this `$isPublic` and we've been using two different filesystems
based on that flag. But if you remember our two filesystems right now are actually
identical. We don't need to filesystem as many more with S3 the same one
filesystem can actually be used for public or private files. So we can take out of
this `$filesystem=` part and just always use the one filesystem. Now it's a tell it
that a file should be visible or public or private. We can pass in options on the
end, weren't passive `visibility` options set to if `$isPublic`  use 
`AdapterInterface`. The one from `Flysystem`, `::VISIBILITY_PUBLIC` else,
`AdapterInterface::VISIBILITY_PRIVATE` and that will take care of the
problem.

I have to try that

actually. That's not too bad.

Drive out. Let's close up this tab. Let's upload a new article image. How about
rocket before that fixed? That would have been private.

Cool. The thumbnail works and when we click it now it is actually a public image and
this is something that you can see when you are looking at the individual files. Um,
so if we go back to space bar article image rockets, you can go to permissions and
you could see my account as a all permissions but can see public access and public
access to read. They don't have Publix access to write, but they do have public
access to read. So we don't need a private filesystem at all. So we can do some
serious cleanup here. So let's start in `config/packages/oneup_flysystem.yaml` We don't
need a `private_uploads_adapter` anymore

at all.

Next in `services.yaml` there no `private_upload_filesystem` anymore. So we're going
to get rid of is fine.

Okay.

And then finally an `UploaderHelper` go all the way at the top. We don't need a
`$privateFilesystem` anymore. We'll get rid of our `$privateUploadFilesystem` arguments.
And then there were two other places that we were using the `$privateFilesystem`. The
first one is down here in `readStream()`. You can see `$isPublic`. We were doing the
different filesystems. We don't need that. We're always just going to read from the
one filesystem. Now

we don't care if it's public or not.

And then delete file. Same thing. We don't care if it's public or not. We're just
going to read from the uh, delete from the one that same filesystem. These two
methods were called in our controller. So `readStream()` is actually right here. We
don't need the second argument anymore. It doesn't exist. And then delete, same
thing. We can remove that second argument because there is no second argument
anymore.

Cool. And the last thing we'll do, it's totally optional, but having this word public
here, it doesn't really make any sense anymore. It's just our `uploads_adapter` and
it's just our `uploads_filesystem`. So we'll take out the `public_` there. We also need to
do it and LiipImagineBundle and we referenced the service ID that's created
is so now just be the `uploadS_filesystem` and in `services.yaml`. Our `bind:` here is
also not going to have the word public in it. And instead of binding to 
`$publicUploadFilesystem`, I'll now bind to `$uploadFilesystem`. That doesn't mean, and also I will
need to also change that instead of my help or I just want to see, I want you to see
what that error is. Unused binding upload filesystem in `S3Client`. This is that
generic error that says you are actually, this is not as good as mayor as I want to.
It's more obvious. If you delete this, delete this here. It'll say, hey, can I auto
wire, I'll `UploaderHelper` argument, `$publicUploadFilesystem`. Um, uh, it
basically isn't specified and that's because we just renamed it to `$uploadFilesystem`.
So let's go into `UploaderHelper`, and here we go. You make sure that those buying
arguments match right there.

And finally, we don't need a `public/uploads/` director anymore. I'm just going to kill
it. And then my `.gitignore` file. I'm gonna take out that custom `public/uploads/`
that we had added earlier and we're good to go. So putting things in S3 actually
simplifies things quite a bit once you get it all set up because you only need one
filesystem and you can specify in an object by object basis, whether it's public or
private. So it's pretty awesome.