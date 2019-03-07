# API Endpoint & Errors with Dropzone

The AJAX upload finishes successfully... but the response is a redirect... which
doesn't break anything technically... but it's weird. The problem is that our
endpoint isn't setup to be an API endpoint - it's 100% traditional: we're redirecting
on error and on success.

Now that we *are* using this as an API endpoint, we need to fix that. And... this
kinda simplifies things. For the validation error, we can say
`return $this->json($violations, 400)` to return a nice JSON response of the errors.

And at the bottom, we don't *really* need to return anything yet, but it's pretty
standard to return the JSON of a resource after creating it. So, return
`return $this->json($articleReference)`.

Let's try it! Move over, refresh... even though we don't need to, and select
`astronaut.jpg`. This time... it failed! Let's see what the error looks like. Hmm,
actually, better: click to open the profiler - you can always see the error here.
Oh:

> A circular reference has been detected when serializing object of class `Article`.

This is a *super* common problem with the serializer, and we saw it earlier. We're
serializing `ArticleReference`. And, by default, that will serialize all the properties
that have getter methods... including the `article` property, which is an `Article`
object. Then when it serializes the `Article`, it finds the `$articleReference`
property and tries to serialize the `ArticleReference` objects... in an endless
loop.

The easiest way to fix this is to define a serialization group. In `ArticleReference`,
above the `id` property, add `@Groups` and let's invent one called `main`. Put this
above all the fields that we actually want to serialize, how about `id`, `$filename`,
`$originalFilename` and `$mimeType`. We're not actually *using* the JSON response
yet so it doesn't matter - but we will start using it in a few minutes.

Back in the controller, let's break this onto multiple lines. The second argument
is the status code and we should actually use `201` - that's the proper status
code when you've correctly *created* a resource. Next is headers - we don't need
anything custom, and, for context, add an array with `groups` set to `['main']`.

Let's see if that fixed things. Close the profiler and select "stars". Duh - I totally
forgot - the stars file is too big - you can see that it failed. But when you
hover over it... `object Object`? That's not great - we'll fix that in a minute.

Select `Earth from the Moon.jpg` and... nice! It works and the JSON response looks
awesome!

## Displaying Errors Correctly

Ok, so let's look back at what happened with stars. This failed validation and so
the server returned a 400 status code. Dropzone *did* see that - it knows it failed.
But, by default, Dropzone expects the Response to be just a string with the error,
not a nice JSON structure with a `detail` key like we have.

No worries: we just need a little extra JavaScript to help this along. Back in
`admin_article_form.js`, add another option called `init` and set that to a `function() {}`.
Dropzone calls this when it's setting itself up, and it's a great place to add
extra behavior via events. For example, want to do something whenever there's an
error? Call `this.on('error')` and pass that a callback with two arguments: a
`file` object that holds details about the file that was uploaded and `data` - the
data sent back from the server.

Because the real validation message lives on the `detail` key, we can say:
if `data.detail`, `this.emit('error')` and pass `file` and the actual error message
string: `data.detail`.

That's it! Refresh the *whole* thing.. and upload the stars file again. It failed...
but when we hover on it! Nice! There's our validation error.

Next: now that are files are automatically uploaded via AJAX, the reference list
should *also* automatically update when each upload finishes. Let's render that
whole section via JavaScript.
