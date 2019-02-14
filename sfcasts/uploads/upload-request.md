# Uploads, multipart/form-data & UploadedFile

This page uses a Symfony form. And we *will* learn how to add a file upload field
to a form object. But... let's start simpler - with a good old-fashioned HTML
form.

The controller behind this page live at `src/Controller/ArticleAdminController.php`,
and we're on the `edit()` action. Create a totally new, temporary endpoint:
`public function temporaryUploadAction()`. We're going to create an HTML form
in our template, put an input file field inside, and make it submit to this action.
Add the `@Route()` with, how about, `/admin/upload/test` and `name="upload_test"`.
But... don't do anything else yet.

Copy the route name, then open the template for the edit page:
`templates/article_admin/edit.html.twig`. The Symfony form lives inside the
`_form.html.twig` template. So, *above* that form tag, add a new form tag, with
`method="POST"` and `action=""` set to `{{ path('upload_test') }}`. Inside, we only
need one thing `<input type="file">`. We need to give this a name so we can reference
it on the server: how about `name="image"`.

Finally, add `<button type="submit">` and I'll add some classes so that this isn't
the *ugliest* button ever. Say: Upload!

That's it! The simplest possible file upload setup: one field, one button.

## Fetching the File in the Controller

In some ways, uploading a file is really no different than any other form field:
you're always just sending data to the server where each data has a *key* equal
to its `name` attribute. So, the same as any form, to read the submitted data,
we'll need the request object. Add a new argument with a `Request` type-hint - the
one from HttpFoundation - `$request`. Then say: `dd()` - that's dump & die -
`$request->files->get('image')`. I'm using `image` because that's the `name`
attribute used on the field.

Cool! What do you think this will dump out? A string filename? An array? An object?
Let's find out! Choose a file - I'll go into my `I <3 Space` directory, and select
the astronaut photo! Upload!

## multipart/form-data

Oh! It's... null!? I did not see that coming. If you're ever uploading a file and
it's *totally* not working, you've probably made the same mistake I just did. Go
back to the template and add an attribute to the form `enctype="multipart/form-data"`.

Yep! Mysteriously, you *never* need this on your forms... *until* you have a file
upload field. It basically tells your browser to send the data in a different
*format*. We're going to see *exactly* what this means soon cause we are *crushing*
the magic behind uploads.

Fortunately, PHP understand this format *and* this format supports file uploads.
Refresh the form so the new attribute is rendered. Let's choose the astronaut again.
And before hitting Upload, open up your developer tools and go to the Network tab:
I want to see what this request looks like. Hit upload!

Nice! This time we get an `UploadedFile` object *full* of useful data.

But before we dive into that, look down at the network tools and find the POST
request we just made. If you look at the request headers... here it is: our
browser sent a `Content-Type: multipart/form-data` header. *This* is because of
the `enctype` attribute. It also added this weird `boundary=----WebkitFormBoundary`,
blah, blah, blah thing.

Ok: this stuff is super-nerdy-cool. *Normally*, when you do *not* have that
`enctype` attribute, when you submit a form, all of the data is sent in the body
of the request in a big string full of what looks like query parameters. That's
kind of invisible to us, because PHP parses all of that and makes the data available.

But when you add the `multipart/form-data` attribute, it tells our browser to send
the data in a different format. It's actually kind of hard to see what the body
of these requests look like - Chrome hides it. No worries! Through the magic of
TV... boom! *This* is what the body of that request looks like.

Weird, right! Each field is separated by this mysterious `WebkitFormBoundary` thing...
which is the string that we saw in the `Content-Type` header! Our form only has
one field, but if we had multiple, this separator would be between *every* field.
Our browsers invents this string, separates each piece of data with it, then sends
this separator up with the request so that the server knows how to parse everything.

*Why* is this cool? Because we can now send up *multiple* pieces of information
about our `name="image"` field, like the original filename on our system and what
type of file it is... which, by the way, can be totally faked by the user. More on
that later. After all that, we've got the data itself!

If you look *all* the way at the bottom, it has another `WebKitFormBoundary` line.
If there were more fields on this form, you'd see their data below - all separated
by another "boundary".

So... that's it! It literally tells our browser to send the data in a different
format - and PHP understands *both* formats just fine. We *need* this format when
doing file uploads because a file upload is *more* than just its contents: we
also want to send some metadata. And also, due to how the data is encoded, if you
*were* able to send binary data on a normal request - without the `multipart/form-data`
encoding - it would increase the amount of data you need to upload by as much as
three times! Not great for uploads!

## The UploadedFile Object

Once the data arrives at the server, PHP automatically reads in the file and saves
it to a temporary location on your server. Symfony then takes *all* of these details
and puts it into a nice, neat `UploadedFile` object. You can see the `originalName`:
`astronaut.jpeg`, the `mimeType` and - *importantly* - the location on the filesystem
where the file is temporarily stored.

If we do *nothing* with that file, PHP will automatically delete it at the end of
the request. So... our job is clear! We need to move that into a final location
and... do a bunch of other things, like make sure it has a unique filename and the
correct file *extension*. Let's handle that next.
