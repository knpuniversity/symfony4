# Absolute Asset Paths

Coming soon...

Yeah.

What are the things I'm noticing is that this word `uploads` the directory where
uploads are being stored is starting to show up in a few places. We have it here in
our LiipImagineBundle. We also have it in one up a Flysystem here. That's where
they are uploaded to. And also an UploaderHelper, uh, for our public path and method
we can use to get the absolute public path. Um, it's not a huge problem but I don't
like repetition. And again, when we try and move this to s three later, um, that's
gonna be a problem because we're going to need to kind of hunt that down and make it
work well everywhere. So this is a really nice clean up that we can do. Go to
`services.yaml` and we're just going to create two new parameters. One called 
`uploads_dir_name`.

Okay.

Set you "uploads". And this is going to be basically, what's the name of the directory
where we are storing uploaded files. Then regret another one called `uploads_base_url`.
It's gonna look a little weird at first. And then the almost the same thing `/` and then
`%uploads_dir_name%`. This will be like the base URL that you need to
use. If you want to get a URL to anything and the `uploads/` directory migrating these
do new things. We can do quite a bit of cleanup. For example, in LiipImagineBundle
, we actually need the URL here so we can go over here and referenced the new
uploads base URL `%uploads_base_url%` and that's done in one up Flysystem. We
actually need sort of the directory name here. So I'm going to use the uploads, her
name, `%uploads_dir_name`.

Okay.

And then the last place we these things is an `UploaderHelper` and upload helper. Here
we actually need um, the base URL because this is basically going to be the, that you
were aware, uh, what servers installed. Usually that's an empty string and then we'll
have the base you right there. So this is something that we need to use dependency
injection to get. So I'm actually going to add a new argument to my method here
called.

Okay.

`string $uploadedAssetsBaseUrl`. And then I'm gonna create a new property. I'm gonna
give it a slightly different name, not for any particular reason called 
`$publicAssetBaseUrl`. You are out and down here and we'll say 
`$this->publicAssetBaseUrl = $uploadedAssetsBaseUrl`


Now we can use this below. We know this is going to be sad to /uploads. So you can
basically concatenate it right here. Say `$this->publicAssetsBaseUrl`
that `/` and then the path. Then finally we're going to need to bind this a new
variable. So obviously it's not gonna be able to auto wire this path here. You can
see that air if you try to reload any page. Yup. CanNot auto wire this argument. So
we're gonna go back into `services.yaml` and we can just bind that. So we'll bind
`$uploadedAssetsBaseUrl: '%uploads_base_url%'`

Okay, now we'll go back.

Cool. Looks like everything is working. And that was in some ways a small detail, but
it really tightened everything up here. Um, because we have all this like upload
directory configuration sort of in this one spot and it's going to let us do
something really cool here in a second. Now first to make sure. Now first I want to
really triple check that I have this public path thing working correctly. Remember,
this is a, we're actually using this right now. This is the, um, early recreated a
twig extension and it allowed us have an upload `uploaded_asset()` function. And if you
call this, it eventually actually uses the upload half help bring calls, `getPublicPath()`
. What I want to do now is, um, we're showing the thumbnail image and our form
here,

okay.

But what might be useful is to allow the user to actually click this and see the full
image. So this is pretty easy to do at this point because we have a way to get the
full path to it. So I'm going to say h a, h a ref = and we'll use `uploaded_asset()`. And
then we just need to pass it the path. So it's going to be this whole long thing
here. `articleForm.vars.data.imagePath`. That's it. And we'll wrap our
image in that. I'll even do a little `target="_blank"`.

I'm good.

So I move over and refresh. You can click that and boom, that gives us the full
absolute path to the, um, to the actual direct image. Now, one last thing I want to
talk about here, and it's something that I know is going to come up for a lot of us
right now. If I inspect element on my image, you'll notice that the h ref, both the
ATF and the thumbnail image are relative. They don't include the hostname. Now,
that's not a problem, the normal web context. But eventually you're gonna find
yourself in a situation where you need to render a page as a pdf or you need to send
an email and you need to reference the uploaded file. And suddenly this is going to
fail.

You need to send an email from a console command. Suddenly this is going to fail
because you're not in a web context. There is no, um, browsers. So there's no current
host name. So in a lot of cases you're going to find yourself in a situation where
you want an absolute you were to this and there are a few ways to solve this and I
honestly went back and forth on the, on the way that I wanted to do it. Now finally
settled on something that we've used here on Symfony casts for years and that is that
in `.env` file, we actually create our own environment variable called 
`SITE_BASE_URL`. And by default I'm gonna set this to our base, our localhost:8000

Okay.

And of course you can override this if you create a `.env.local` file, um, and
on production you would do that too. Whatever your production domain is. Then we're
going to go back into our `services.yaml` file. So `config/services.yaml` and
for the `uploads_base_url`, this is going to be basically the full URL that we want
before every single upload an image, I'm going to put `%env()%`
inside of there use `SITE_BASE_URL`. So just like that, every single path to
every single uploaded asset is now going to include the absolute, um, uh, path with
the domain. So try it out. Refresh. Boom. There it is. Look at both local. It's going
8,000 and locals call on 8,000. I don't really see in Symfony gas. We basically
always rented the absolute path. We don't really, you know, you couldn't have it. You
could have something where you toggle it on or off and don't really see the point of
that. Um, but yeah, there you go. Absolutely. Urls.