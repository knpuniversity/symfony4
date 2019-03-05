# Dropzone

Coming soon...

One of the most common requests, like half of this tutorial was multiple file
uploads. So I think the idea is that, hey, I have this article here, I want to be on
the upload multiple references, I wanna upload them at the same time so maybe I can
choose a file here instead of sucking one five and it's like the whole directory and
it just works. And I think a lot of people are picturing that as maybe like a field
inside the form of multiple file uploads. If I select one, there's another one below
that and I'm not going to do that way. I don't like doing it that way. And there are
two reasons. First of all, as I've mentioned, I'm using some sort of collection form
type of `CollectionType` form inside of a form with an upload field is just going to
be a nightmare.

The other thing is I don't like the idea of having, you know, 10 file uploads
attached to a single form submit because one, if I, if I did have a, a field in my
forum where I could select multiple files when if I selected 10 files and hit upload
and nine of them we spent all the time, I'll putting all 10 but one of them had a
validation error. What happens then? Do we try to save the nine and send back in the
air? Um, it's just not a good situation. I also want my files to start uploading as
soon as I select them. So in my opinion, if you want something a little fancier, it
would be able to upload multiple files. You need to write some JavaScript. So that's
exactly what we're going to do. What you're going to create an awesome widget over
here where we can upload multiple files, delete them, edit their file name.

Lots of good stuff. So do you do this? We're going to use a library called drop zone.
Probably the most popular library for doing JavaScript. Uploading a, here it is. It
creates a little area like this. You select the file and it just starts uploading.
Boom, super easy. So I'm gonna search for a drop zone CDN. Now normally in Symfony
I'm using Webpack encore. And so whenever I'm using a third party library, I'll
actually install it via yarn and then require in my is properly in this tutorial. For
simplicity, I am not using a Webpack encore. And instead as you can see in our edit
template, we're just including a normal JavaScript file and a JavaScript file lives
in our `public/js/` directory `admin_article_form.js`. And we're just writing kind of like
normal traditional, um, JavaScript. So again, that's to keep it simple and hopefully
it should be simple to work this into Webpack on court. So in this case, I'm just
going to grab the JavaScript and CSS files that we need. So let's grab the men
JavaScript copy that go into my edit template and I'll paste that up here. So a copy
of the men JavaScript, I'll actually copy the script tag with SRI. That's kind of a
nice one because it gives you whole script tag plus the integrity. And then I'll do
the same thing with the CSS copy link tag with SRI this time we don't have style
sheets block yet, so I'll override that `{% block stylesheets %}{% endblock %}` call the 
`{{ parent() }}` function, then paste that link tag that cool.

So we, the way that you work with, um, drops down usually is you basically, you don't
need to have a `<button>` or even an `<input type="file">` anymore. You just need to have a
`<form>` tag and you give it a class called `dropzone` by default drops on it. It's just
going to look for that class on your page. And so when we refresh, boom, we have a
drop zone. Yeah. This, the cool thing is, is that when we select a file, it's going
to automatically upload it via Ajax and it's going to use our `action=` so it's going
to upload via Ajax to our end point. So in theory it should just sort of work. So
let's go all the way up here and let's do a `dump()` on her `$uploadedFile`. Not a, not a
dd because, because it's easier to debug this if we just do a dump and you'll see why
in a second. So now I'm gonna Select a file in upload and the first school thing is
you can see down here it actually our Ajax request show up here. So this is actually
the age extra cost for that end point. So I can, I'm going to hold a commander
control to open that in a new tab. And down here

it doesn't work.

I didn't shoot work and now we're looking at the uh, profiler for that Ajax request.
Actually that's not entirely true. If you looked when we made a post request to the
and the Ajax request, it actually got a three o to redirect and redirect it to the
article edit page. So we're actually looking at the profiler for that article edit
page. It's a little bit confusing. If it lasts 10 he gets a little more obvious that
we posted to the a `/admin/article/41/references`. That's our um, end point for
uploading. And for whatever reason that returned a three o two and redirected us to
this edit. So I actually want to go and look at this references page because down
here under the debug we can see the dump from our controller and interesting it's
actually `null` and the reason it's not is that by default dropzone uploads a field
called file. You need to look that up and in our controller we're expecting it to be
called reference. That's an easy fix and in general it's what we're seeing here is
that the nice ability of dropzone to just add a classicals drops on it, it takes
care of everything else. It doesn't really work. We're going to want to do some
custom configuration to set this up.

So in `admin_article_form.js`, here's how we're going to do this. First thing is way at
the very top. We're going to call a method call `Dropzone.autoDiscover = false;`
That's going to tell him not to do that thing where it automatically sets up
the upload field for us. Actually we should be able to see for your fresh. Now

there we go.

And you might need to do a force refresh. Now, inside of here at the very top of the,
uh, document dot ready, I'm going to call a new method called `initializeDropxone()`

Copy that.

And then down at the bottom we'll just create a new `function` called `initializeDropzone()`
on good. If I were using Webpack encore, I'd probably start organizing these
functions into multiple files or modules, importing them, but we're just keeping
things simple. So in order to find my form that I actually want to turn into a drop
zone, I'm going to add another class here called `js-reference-dropzone`, a copy of
that. And then inside of our JavaScript I'll say 
`var formElement = document.querySelector()`

there,

that `.js-reference-dropzone`. This is, I'm just using a little bit of
JavaScript here and not using jquery. You could use jquery, um, just doing things and
that kind of simplest way. And just in case it shouldn't happen. But if there's not a
form element, `!formElement`, then we'll just `return;`. And then finally down here we
can say far `dropzone = new Dropzone()`. I would say `formElement` in here, we can pass
in some options. And the one option where you're at nene, nene, or and now is
`paramName` set to `referencez. All right, cool. So that should do it. Let's go over
here. Refresh, upload a, how about earth? That jpeg.

Yeah.

And it looks successful but it's not really, once again, we can see the post here.
I'm gonna open that up in a new tab. Careful. Once again, it's a redirected. So this
is actually the link to the real dump and debug. Yes. Now we have are `UploadedFile`.

Oh,

and if you close this and refresh, kind of look at our file list. Yeah, there's earth
dot jpeg. The reasons redirecting is that in our entire controller is not really set
up to be in Ajax Controller. It's set up to be kind of a traditional controller.
We're redirecting on air and then at the bottom or redirecting on success. So this is
really an API end point. Now let's extra refactor that a little bit to uh, to be a
proper API endpoint. And this actually simplifies our code that if there's a, there's
a validation error, we can say `return $this->json($violations, 400)` that's going to turn
that into a nice JSON response to those violations. At the bottom we can say 
`return $this->json()`, and will return the `$articleReference`. Beautiful staff would go over and
refresh.

Okay,

it's uploading astronauts and Oh, this time it failed. Look, we got a post request
the 500 error, check that out

and

there we go. The air is not very clear. Actually, I'm going to look at the air at
different way. Let's go through the profiler. You can see the air. There we go. Ooh.
A circular reference has been detected when serializing object of class 
`App\Entity\Article`. So this is a really common problem with the serializer. What's happening
here, and we've seen this before, is we're serializing the `ArticleReference` by
default, that's going to sterilize all the properties that have gutters. So then it
serializes the one of them is the article. Then when it goes in the article,

mmm.

Eventually actually

finds the `$articleReference` property down here. So you realize that attic reference
kind of gets actually stuck in this circular reference thing. So the way that we
fixed this, the way it usually fix this is by using serialization groups. So on
article reference, I'm just going to add an `@Groups()` and I'll just invent a group
called `main` and I'm going to put this above all of the fields that actually wanted to
serialize. So I'll do id, let's do `$filename`, `$originalFilename` and `$mimeType`. It
doesn't really matter right now. We're not actually using that return. JSON Response
we might use, we are going to use it in a few minutes. So the fields you want to
return just depends on what you actually need to use. And then in our controller
we're going to break this into multiple lines. And second argument is the status
code. Uh, technically we should actually use a `201` here. That's the status
code he used. When he creates something, you don't need any custom headers. And for
the context, that's where we add our `['groups' => 'main']`. That kind of thing is a called
groups output. All right. So I'll close the profiler. Let's just refresh to get a
fresh page load here.

Yeah,

it's time. We'll get stars.

Oh Shit.

Oh and actually I forgot. Stars is too big. And look at this air here. Object,
object. Let's fix that in one second. Let's do earth from the moon and nice. That
works. Network perfectly. And the response looks awesome. So again, back to the
stars. Thinner object object. So the issue is that by default drop zone, when you
have some sort of an like it correctly saw that the 400 air here, 400 status code or
return is an air, but it expects the air message just to be a string in the response.
Whereas we're returning this nice JSON structure and our actual messages on this
detail key. So we need to add a little bit of JavaScript that drops on to understand
that.

So the place to do this as back in `admin_article_form.js`, and we're gonna add another
option called a `init` and set that to a `function() {}`. This is what we're going to do. Lots
of different customization on drop zone. And we're gonna start attaching event
listeners. So you can say `this.on('error', ...)`, but basically say, hey, one of those an air I
want you to call this `function() {}` dropzone and we'll pass you a `file` object that represents
the file that was uploaded. And I'll pass you the `data` that was sent back from the
server on the air. Here we can say if `data.detail`, so if there is a `detail` key
on the `data` on the, on the response, um, meeting, it's our type of response. We're
going to say `this.emit('error')`, and we're going to readmit a different air pass
at the same file in the same pass at `data.detail`. And that should be it. So go
back, refresh the whole thing. Let's upload stars. We know that's too big and it
didn't work, but when we hover over it, yes, this time we have the right air message.
Awesome.