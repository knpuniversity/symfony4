# S3 & Private Object via ACLs

Head to `/admin/article` and log back in since we cleared our database recently:
`admin1@thespacebar.com`, password `engage`. Edit any of the articles. Everything
*should* work just fine: I'll select a few references to upload and... it works
nicely. It *is* a bit slower now that the server is sending the files to S3 in the
background, though that should be less noticeable once we're on production, especially
if our server is also hosted on AWS.

So... can we download these? Try it! Yea, it works great! Open up
`ArticleReferenceAdminController` and search for "download". Here it is: the
download is handled by `downloadArticleReference`: we open a file stream from
Flysystem - which is now from S3 - and stream that back to the user. By planning
ahead and using Flysystem, when we switched to S3, *nothing* had to change!

But, there is *one* tiny problem. Back on the page, click the image. Access denied!?
This *should* show us the full-size, original image. Hmm, the URL *looks* right.
And, indeed! The problem isn't the path, the problem is with that file's *permissions*
on S3.

Each file, or "object" on S3 can be set to be publicly accessible *or* private.
File are *private* by default. In fact, the only reason that we can see the thumbnails,
which are *also* stored in S3... is that LiipImagineBundle is smart enough to make
sure that when it saves the files to S3, it saves them as *public*.

When an author uploads an article image, we need to do the same thing: we *do*
want the original images to be public.

## Giving the Images Public ACL

Head over to `UploaderHelper` and find `uploadFile()`. So far, we've been using
the `$isPublic` argument to choose between the public and private filesystem objects.
But when we changed to S3, I temporarily made these two filesystems *identical*.
That wasn't on accident: with S3, we don't need two filesystems anymore! We can
use the same one for both public and private files, and control the visibility
on a file-by-file basis.

Check it out: remove the `$filesystem =` part and always use `$this->filesystem`. 

[[[ code('f6adbbb3f1') ]]]

To tell Flysystem that a file should be public or private, add a *third* argument
to `writeStream()`: an array of options. The option we want is `visibility`. If
`$isPublic` is true, use `AdapterInterface` - the one from `Flysystem` -
`::VISIBILITY_PUBLIC`. Otherwise, `AdapterInterface::VISIBILITY_PRIVATE`.

[[[ code('f4d97470e8') ]]]

Cool, right? That won't instantly change the permissions on the files we've already
uploaded. So let's go upload a new one. Close the tab, select a new file, how
about `rocket.jpg` and... update! The thumbnail still works and if you click it,
yes! The original file is public!

By the way, you can see this setting when you're looking at the individual files
in S3. Click back to the root of the bucket, find the `rocket.jpg` file and click
it. Under "Permissions", here we go. *My* account has all permissions, of course,
and under "Public Access", *Everyone* has "Read object" access.

## Remove that Extra Private Filesystem!

Hey! This is awesome! Thanks to the object-by-object permissions super-power of
S3, we don't need an extra "private" filesystem at all! We can do some serious
cleanup! Start in `config/packages/oneup_flysystem.yaml`: remove the
`private_uploads_adapter` and filesystem.

[[[ code('043eb07d5b') ]]]

Next, in `services.yaml`, because there's no `private_upload_filesystem` anymore,
remove that bind.

[[[ code('c76aad6e67') ]]]

That will break `UploaderHelper` because we're using that bind on top. But...
we don't need it anymore! Remove the `$privateFilesystem` property and
the `$privateUploadFilesystem` argument.

[[[ code('82b7442d12') ]]]

But, we're still using that property in two places... the first is down in `readStream`.
Now that everything is stored in *one* filesystem, delete that old code, remove
the unused argument and always use `$this->filesystem`. Reading a stream is the
same for public and private files.

[[[ code('b3f5a5d6e3') ]]]

Repeat that in `deleteFile()`: delete the extra logic & argument, and use `$this->filesystem`
*always*.

[[[ code('a989ac4499') ]]]

Let's see... these two methods are called from `ArticleReferenceAdminController`.
Take off that second argument for `readStream()`. 

[[[ code('c4c29ea97d') ]]]

Then, search for "delete", and remove the second argument from `deleteFile()` as well.

[[[ code('b8bd3faaa1') ]]]

That felt great! There's one more piece of cleanup we can do, it's optional, but
nice. Using the word "public" in the adapter and filesystem isn't accurate anymore!
Let's use `uploads_adapter` and `uploads_filesystem`. 

[[[ code('63910c1d01') ]]]

We reference this in a few spots. In `liip_imagine.yaml`, take out the `public_` 
in these two spots.

[[[ code('2b8ce1dbc7') ]]]

And in `services.yaml`, update the "bind" in the same way. Hmm, and I think I'll
change the argument name it's binding to: just `$uploadFilesystem`.

[[[ code('bd907c2335') ]]]

That *will* break `UploaderHelper`: we need to rename the argument there. But,
let's just see what happens if we... "forget" to do that. Refresh the page:

> Unused binding `$uploadFilesystem` in `S3Client`.

This is that generic... and somewhat "inaccurate" error that says that we've
configured a bind that's never used! The error is even better if we temporarily
delete the bind entirely. Ah, here it is:

> Cannot autowire `UploaderHelper`: argument `$publicUploadFilesystem` references
> an interface, but that interface cannot be autowired.

This is saying: Hey! I don't know what you want me to send for this argument!
Put the bind back, then, in `UploaderHelper`... here it is. Change the argument
to match the bind: `$uploadFilesystem`.

[[[ code('e2bf705455') ]]]

Oh, and there's one more thing we can get rid of! Do we need the `public/uploads`
directory anymore? No! Delete it! And inside `.gitignore`, we can remove the
custom `public/uploads/` line we added.

So by putting things in S3... it simplifies things!

Next: now that I've been complimenting our S3 setup and saying how awesome it, I
have a... confession to make! We've just introduced a hidden performance bug. Let's
crush it!
