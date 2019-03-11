# Absolute Asset Paths

One of the things I've noticed is that this word `uploads` - the directory where
uploads are being stored - is starting to show up in a few places. We have it here
in our `liip_imagine` config file, the `oneup_flysystem.yaml` file and in
`UploaderHelper`: it's used in `getPublicPath()`.

## Centralizing the uploads/ Path

It's not a *huge* problem, but repetition is a bummer and this will cause some
issues when moving to S3: we'll need to hunt down all of these paths and change
them to point to the S3 domain.

Let's tighten this up. In `services.yaml`, create two new parameters: The first
will be `uploads_dir_name` set to `uploads` - this is the name of the directory
where we are storing uploaded files. Call the second one `uploads_base_url` and
set this to almost the same thing: `/` and then `%uploads_dir_name%`. This represents
the base URL to the uploaded assets.

Thanks to these, we can do some cleanup! In `liip_imagine.yaml`, we need the URL.
Copy `uploads_base_url` and then use `%uploads_base_url%`.

Next, in `oneup_flysystem.yaml`, we need the directory name. Copy the other parameter:
`%uploads_dir_name%`.

The last place is in `UploaderHelper`. The `getBasePath()` call will give us the
directory where the site is installed - usually an empty string. Then we need to
pass in the `uploads_base_url` parameter.

Add a new argument to the constructor: `string $uploadedAssetsBaseUrl`. I'll create
the property by hand and give it a slightly different name: `$publicAssetBaseUrl`,
not for any particular reason. Set that in the constructor:

Back in `getPublicPath()`, use this: `getBasePath()` then `$this->publicAssetsBaseUrl`,
which will contain the `/` at the beginning.

Cool! But, Symfony will not be able to autowire this string argument. You can
see the error if you try to reload any page. Yep!

We know how to fix that: back in `services.yaml`, add a bind:
`$uploadedAssetsBaseUrl` set to `%uploads_base_url%`. Now... it works!

## Linking to the Full Image

Small step, but with all this config in one spot, we can do something kinda cool...
with almost no effort. But first, I want to *triple* check that all this public
path stuff is setup correctly. Our `getPublicPath()` method is currently used
in one spot: by the `uploaded_asset()` Twig function. But, we're not actually
*using* this Twig function anywhere at the moment.

So try this: in the form, we're showing the thumbnail. It might be useful to allow
the user to click this and see the *original* image. That's pretty easy: add
`<a href="">` and use `uploaded_asset(articleForm.vars.data.imagePath)`.

That's it! Wrap this around the `img` tag and let's also add `target="_blank"`.

Cool. Test that - refresh and... click. Nice! This sends us directly to the *source*
image.

## Absolute URLs

Thanks to our setup, we can now solve a really annoying problem. Inspect element
on the image: notice that both the `href` and the image `src` paths do *not*
contain the domain name. That's not a problem at *all* in a normal web context.
But if you ever try to render a page into a PDF with something like `wkhtmltopdf`
or create a console command to send an email that references an uploaded file,
well... suddenly, those paths will start to break! In those contexts, you *need*
the URLs to be absolute.

There are a few ways to solve this... and honestly, I went back and forth on the
best approach. I finally settled on something that we've used here on SymfonyCasts
for years. Open your `.env` file. We're going to create a brand new, custom
environment variable called `SITE_BASE_URL`. Set the default value to
`https://localhost:8000`.

Remember: this file *is* committed to git, so this is the *default* value. You
can create a `.env.local` file to override this value locally or on production.
Or, of course, if it's easy, you can override this by setting a real environment
variable.

Next, go back to `services.yaml`. And for the `uploads_base_url`, use
`%env()%` and inside, `SITE_BASE_URL`: that's the syntax for referencing an
environment variable.

And... just like that - *every* single path to every single uploaded asset will now
be absolute. Seriously! Test it out! Boom! Both the link `href` and the image `src`
contain the `https://localhost:8000` part.

And, sure, you could add some config so that you could turn this on only when you
need it... but I don't really see the point. I'll keep absolute URLs always.

Next: let's start uploading *private* assets: stuff that can't be put into the
`public/` directory because we need to check security before we let a user download
it.
