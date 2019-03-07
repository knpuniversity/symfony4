# Dropzone: AJAX Upload

When I started creating this tutorial, I got a lot of requests for things to
cover... which, by the way - thank you! Your requests *absolutely* helped drive
this tutorial. One request that I heard over and over again was: handling multiple
file uploads at a time.

It makes sense: instead of uploading files one-by-one, an author should be able
to select a *bunch* at a time! This is something that's *totally* supported by
the web: if you add a `multiple` attribute to a file input, boom! Your browser
will allow you to select multiple files. In Symfony, we would then be handling
an *array* of `UploadedFile` objects, instead of one.

But, I'm *not* going to show how to do that. Mostly... because I don't like the
user experience! What if I select 10 files, wait for *all* of them to upload,
then one is too big and fails validation? If you're not inside a form, you can
probably save 9 of them and send back an error. But if you're inside a form,
good luck: unless you do some serious work, *none* of them will be saved because
the entire form was invalid!

I also want my files to start uploading as soon as I select them and I want to
show a progress bar. Basically... I want to handle uploads via JavaScript. In
fact, over the next few videos, we're going to create a pretty awesome little
widget for uploading multiple files, deleting them, editing their filename and
even re-ordering them.

## Installing Dropzone

First: the upload part. Google for a library called Dropzone: it's probably the
most popular JavaScript library for handling file uploads. It creates a little...
"drop zone" and when you drop a file here or select a file, it starts uploading.
Super nice!

Search for a Dropzone CDN. I normally use Webpack Encore. And so whenever I need
a third-party library, I install it via yarn and import when I need to use it.
If you're using Encore, you *can* do this - and I'd recommend it. But in this tutorial,
to keep things simple, we're *not* using encore. And so, in our edit template, we're
including a normal JavaScript file that lives in the `public/js/` directory:
`admin_article_form.js`, which holds some pretty traditional JavaScript.

To get Dropzone rocking, copy the minified JavaScript file and go to the template
Actually, copy the whole script tag with SRI - that'll include the nice `integrity`
attribute. Grab the minified link tag too. We don't have a `stylesheets` block
yet, so we need to add one: `{% block stylesheets %}{% endblock %}`, call
`{{ parent() }}` and paste the link tag.

Dropzone basically "takes over" your form tag. You don't need a button anymore,
or even the file input. The form tag *does* need a `dropzone` class... but that's
it!

Try it! Refresh and... hello dropzone!

## How Dropzone Uploads

When you select a file with Dropzone, it's smart enough to upload to the `action`
URL on our form. So... in theory... it should just... sort of work.

Back in the controller, scroll up to the upload endpoint and
`dump($uploadedFile)`. I'm not using `dd()` - dump and die - because this will
submit via AJAX - and by using `dump()` without die'ing, we;ll be able to see it
in the profiler. You'll see in a minute.

Ok: select a file. The *first* cool thing is that the file upload AJAX request
showed up down on the web debug toolbar! I'll click the hash and open that up
in a new tab.

This is awesome! We're now looking at *all* the profiler data for that AJAX request!
Actually... hmm... that's not true. Look closely: it says that we were redirected
from a POST request to the `admin_article_add_reference` route. We're looking at
the profiler for the article edit page!

This is a little confusing. Click the "Last 10" link to see a list of the last
10 requests made into our app. Now it's a bit more obvious: Dropzone made a POST
request to `/admin/article/41/references` - that's our upload endpoint. But,
for some reason, that redirected us to the *edit* page. Click the token link to
see the profiler for the POST request.

Check out the Debug tab. There is is: *this* is the dump from our controller...
and it's null. Where's our upoad? The problem is that, by default, Dropzone uploads
a field called `file` - but in the controller, we're expecting it to be called
`reference`.

## Customizing Dropzone

We *could* fix this in the controller... but we can also configure Dropzone to
use the `reference` key. We're going to do that because, in general, as *cool*
as it is that we can just add a "dropzone" class to our form and it mostly works,
to *really* get this system working, we're going to need to customize a *bunch*
of things on Dropzone.

Open up `admin_article_form.js`. First, at the very top, add
`Dropzone.autoDiscover = false`. That tells Dropzone to *not* automatically
configure itself on any form that has the `dropzone` class: we're going to do
it manually.

Try it out - close the extra tab and refresh. Hmm... still there? Maybe a force
refresh? *Now* it's gone. The `dropzone` class still gives us some styling, but
it's not functional anymore.

To get it working again, inside the `document.ready()`, call a new `initializeDropxone()`
function.

Copy that name, and, below, add it: `function initializeDropzone()`. If I were using
Webpack Encore, I'd probably organize this function into its own file and import
it.

The goal here is to find the `form` element and initialize `Dropzone` on it. To
do that, let's add another class to the form: `js-reference-dropzone`. Copy that,
and back inside our JavaScript, say `var formElement = document.querySelector()`
with `.js-reference-dropzone`.

Yes, yes, I'm using straight JavaScript here instead of jQuery to be a bit more
hipster - no big reason for that. There's also a jQuery plugin for Dropzone.
Next, to avoid an error on the "new" form that doesn't have this element, if
`!formElement`, `return`. Finally, initialize things with
`var dropzone = new Dropzone(formElement)`. And *now* we can pass an array of
options The one we need now is `paramName`. Set it to `reference`.

That should do it! Head over and select another file - how about `earth.jpeg`.
And... cool! It looks like it worked. Click to open the profiler for the AJAX
request.

Oh... careful - once again, we got redirected! So this is the profiler for the
edit page. Click the link to go back to the profiler for the POST request and
go back to the Debug tab. Yes! *Now* we're getting the normal `UploadedFile`
object.

Close this and refresh. Look at the list! There is `earth.jpeg`. It worked!
Of course, it's a little weird that it redirected after success... and if there
were a validation error... that would *also* cause a redirect... and so would
look successful to Dropzone. The problem is that our endpoint isn't setup to be
an API endpoint. Let's fix that next and make Dropzone read our validation errors.
