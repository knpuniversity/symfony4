# Rendering the File List Client Side

Here's the plan. Since we're using Dropzone to upload things via Ajax, I want to
transform this entire section into a fully JavaScript-driven dynamic widget. Some
of this stuff we're going to talk about isn't strictly related to handling uploads,
but I got a lot of requests to show a full upload "gallery" where you can upload,
edit, delete and re-order files. So... let's do that!

Select another file to upload, like `rocket.jpeg`. It uploads... but you don't
see it on the list until we refresh. Lame! Instead of rendering this list inside
Twig, let's render it via JavaScript. Once we've done that, updating it dynamically
will be easy!

## Article References Collection Endpoint

To power the frontend, we need a new API endpoint that will return all of the
references for a specific Article. We got this: go into
`ArticleReferenceAdminController` and create a new public function called
`getArticleReferences()`. Add the `@Route()` above this with
`/admin/article/{id}/references`.

*This* time, the `id` is the article id. URLs aren't technically important, but
this is on purpose: in an API, `/admin/article/{id}` would be the URL to get info
about a specific article. Adding `/references` onto that is a nice way to read
its references.

Now add the `methods="GET"` - yes you *can* leave off the curly braces when
there's just one method - and `name="admin_article_list_references"`.

Down in the method, add the `Article` argument and don't forget
the security check: `@IsGranted("MANAGE", subject="article")`. We can use the
annotation this time because we *do* have an `article` argument. Then, oh, it's
beautiful: `return $this->json($article->getArticleReferences());`.

How nice is it!? Let's check it out: in the browser, take off the `/edit` and
replace it with `/references`. And... oh boy, it explodes!

> Semantical error: Couldn't find constant article... make sure annotations
> are installed and enabled.

Well, they are - this is a *total* rookie mistake I made with my
annotations. On the `@IsGranted` annotation, it should be `subject="article"`.
Try it again. *Here* we go - that's the error I was expecting: our favorite
circular reference has been detected.

This is the *exact* same thing we saw a second ago when we tried to serialize a
single `ArticleReference`. And the fix is the same: we need to use the `main`
serialization group.

Pass 200 as the status code, no custom headers, but one custom `groups` option
set to `main`.

Try it again. Gorgeous! That contains *everything* we need to render the list in
JavaScript.

## JavaScript Rendering

To do that, we're not going to use Vue.js or React. Those are both *wonderful*
options, and if you're serious about building some high-quality front-end apps,
you need to give them a serious look. But, to keep the concepts understandable,
I'm going to stick to jQuery and a few modern JavaScript techniques.

Start in `edit.html.twig`. Find the list and completely empty it: we'll fill this
in via JavaScript. But add a new class so we can find it: `js-reference-list`.
Let's also add a `data-url` attribute: I want to print the URL to our new endpoint
to make it easy for JavaScript to fetch the references. Copy the new route name,
paste it into `path` and add pass the `id` route wildcard set to `article.id`.

## The ReferenceList JavaScript Class

Next, in `admin_article_form.js`, I'm going to paste in a class that I've started:
you can copy this from the code block on this page. This uses the newer "class"
syntax from JavaScript... which is compatible with *most* browsers, but not all
of them. That's why I've added this note to use Webpack Encore, which will rewrite
the new syntax so that it's compatible with whatever browsers you need.

Before we dive into this class, let's start using it up on our `document.ready()`
function. Say `var referenceList = new ReferenceList()` and pass it
`$('.js-reference-list')` - that's the element we just added the attribute to.

And... yea! The class mostly takes care of the rest! In the `constructor()`, we
take in the jQuery element and store it on `this.$element`. It also keeps track of
all the *references* that it has, which starts empty and calls `this.render()`,
whose job is to completely fill the `ul` element.

`this.references.map` is a fancy way to loop over the references array, which is
empty at the start, but won't be forever. For each reference, it creates a string
of HTML that is basically a copy of what we had in our template before. This uses
a feature called template literals that allows us to create a multi-line string
with variables inside - like `reference.originalFilename` and `referenced.id`.
The data from the references will ultimately come from our new endpoint, so I'm
using the same keys that our JSON has.

I *did* hardcode the URL to the download endpoint instead of doing something fancier.
You could generate that with FOSJsRoutingBundle if you want, but hardcoding it
is also not a huge deal.

Finally, at the bottom, we take all that HTML and stick it into the element. This
is a bit similar to what React does, but *definitely* less powerful.

Back up in the constructor, the references array *starts* empty, but we immediately
make an Ajax call by reading the `data-url` attribute off of our element. When it
finishes, we set `this.references` to its data and once again call `this.render()`.

Phew! Let's see if it actually works! Refresh and... yes! If you watched closely,
it was empty for a *moment*, then filled in once the AJAX call finished.

## Dynamically Adding the Row

Now that we're rendering this in JavaScript, we have a clean way to add a *new*
row whenever a file finishes uploading. Back inside the `init` function for Dropzone,
add another event listener: `this.on('success')` and pass a callback with the same
`file` and `data` arguments. To start, just `console.log(data)` so we can see what
it looks like.

Ok, refresh, select any file and... in the console... nice! We *already* did the
work of returning the new `ArticleReference` JSON on success... even though we
didn't need it before. Thanks past us!

And *now*, we're dangerous. If we can somehow take that data, put it into
the `references` property in our class and re-render, we'll be good!

To help that, add a new function called `addReference()`. This will take in a new
reference and then push it onto `this.references`. Then call `this.render()`.

For people that are used to React, I *do* want to mention two things. First, we're
*mutating*, um, changing the `this.references` property when we say
`this.references.push()`. Changing "state", which is basically what this is, is
a big "no no" in React. But in our simpler system, it's fine. Second, each time
we call `this.render()`, it is *completely* emptying the `ul` and re-adding all
the HTML from scratch. Front-end frameworks like React or Vue are *way* smarter
than this and are able to update *just* the pieces that changed.

Anyways, inside of `initializeDropzone()`, add a `referenceList` argument: we're
going to force this to get passed to us. I'll even document that this will be an
instance of the `ReferenceList` class.

Back on top, pass in the object - `referenceList`.

And *now* inside success, instead of `console.log()`, we'll say
`referenceList.addReference(data)`.

Cool! Give your page a nice refresh. And... let's see: `astronaut.jpg` is the last
file on the list currently. So let's upload `Earth from the Moon.jpeg`. It uploads
and... boom! So fast! We can even instantly downloaded it.

Next: let's keep leveling up: authors need a way to *delete* existing file
references.
