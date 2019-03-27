# Deleting Files

The next thing our file gallery needs is the ability to delete files. I know
this tutorial is all about uploading... but in these chapters, we're sorta,
*accidentally* creating a nice API for our Article references. We already have
the ability to get all references for a specific article, create a new
reference and download a reference's file. Now we need an endpoint to delete
a reference.

Add a new function at the bottom called `deleteArticleReference()`. Put the
`@Route()` above this with `/admin/article/references/{id}`,
`name="admin_article_delete_reference"` and - this will be important -
`methods={"DELETE"}`. We do *not* want to make it possible to make a GET request
to this endpoint. First, because that's crazy-dangerous. And second, because if
we kept building out the API, we would want to have a different endpoint for
making a GET request to `/admin/article/references/{id}` that would return the
JSON for that one reference.

[[[ code('fda1b36c26') ]]]

Inside, add the `ArticleReference $reference` argument and then we'll add our
normal security check. In fact, copy it from above and put it here.

[[[ code('6727562ac6') ]]]

## The deleteFile() Service Method

Ok: how can we delete a file? Through the magic of Flysystem of course! And the
best place for that logic to live is probably `UploaderHelper`. We already have
functions for uploading two types of files, getting the public path and reading
a stream. Copy the `readStream()` function declaration, paste, rename it to
`deleteFile()` and remove the return type.

[[[ code('dc7c1b9792') ]]]

We'll start the same way: by grabbing whichever filesystem we need. 

[[[ code('05201e37c2') ]]]

Next say `$result = $filesystem->delete()` and pass that `$path`. 

[[[ code('b942c65e95') ]]]

Finally, code defensively: if `$result === false`, throw a new exception 
with `Error deleting "%s"` and `$path`.

[[[ code('39142e4e1f') ]]]

## The DELETE Endpoint

That's nice! Back in the controller, add an `UploaderHelper` argument, oh and
we're also going to need the `EntityManagerInterface` service as well. Remove
the reference from the database with `$entityManager->remove($reference)` and
`$entityManager->flush()`. Then `$uploaderHelper->deleteFile()` passing that
`$reference->getFilePath()` and `false` so it uses the private filesystem.

[[[ code('b84c726b9d') ]]]

Quick note: in the real world, if there was a problem deleting the file from
Flysystem - which is *definitely* possible when you're storing in the cloud - then
you could end up with a situation where the *row* is deleted in the database, but
the file still exists! If you changed the order, you'd have the opposite problem:
the file might get deleted, but then the row stays because of a temporary connection
error to the database.

If you're worried about this, use a Doctrine transaction to wrap *all* of this
logic. If the file *was* successfully deleted, commit the transaction. If not,
roll it back so both the file and row stay.

Anyways, what should this endpoint return? Well... how about... nothing! Return
a `new Response()` - the one from `HttpFoundation` - with `null` as the content
and a 204 status code. 204 means: the operation was successful but I have nothing
else to say!

[[[ code('4a22082327') ]]]

## Hooking up the JavaScript

That's it! That is a *nice* endpoint! Head back to our JavaScript so we can put
this all together. First, down in the `render()` function, add a little trash
icon next to the download link. I'll make this a button... just because semantically,
it requires a DELETE request, so it's not something the user can click without
JavaScript. Give it a `js-reference-delete` class so we can find it, some styling
classes and, inside, we'll use FontAwesome for the icon.

[[[ code('0c067e5d6d') ]]]

Copy that class name and go back up to the constructor. Here say
`this.$element.on('click')` and then pass `.js-reference-delete`. This is called
a delegate event handler. It's handy because it allows us to attach a listener to
any `.js-reference-delete` elements, even if they're added to the HTML *after*
this line is executed. For the callback, I'll pass an ES6 arrow function so that
the `this` variable inside is still my `ReferenceList` object. Call a new method:
`this.handleReferenceDelete()` and pass it the `event` object.

[[[ code('72927ce83a') ]]]

Copy that name, head down, and paste to create that. Inside, we need to do two
things: make the AJAX request to delete the item from the server *and* remove the
reference from the `references` array and call `this.render()` so it disappears.

Start with `const $li =`. I'm going to use the `button` that was just clicked to
find the `<li>` element that's around everything - you'll see why in a second. So,
`const $li = $(event.currentTarget)` to get the button that was clicked, then
`.closest('.list-group-item')`.

[[[ code('4542b576be') ]]]

To create the URL for the DELETE request, I need the `id` of this specific
article reference. To get that, add a new `data-id` attribute on the `li` set
to `${reference.id}`. I'm adding this here instead of directly on the button so
that we could re-use it for other behaviors.

*Now* we can say `const id = $li.data('id')` and `$li.addClass('disabled')` to make
it look like we're doing something during the AJAX call. 

[[[ code('0114135322') ]]]

Make that with `$.ajax()` with `url` set to `'/admin/article/references/'+id` 
and `method: 'DELETE'`. 

[[[ code('c9e0b0947f') ]]]

To handle success, chain a `.then()` on this with another arrow function.

[[[ code('6c4bb13834') ]]]

Now that the article reference has been deleted from the server, let's remove
it from `this.references`. A nice way to do that is by saying:
`this.references = this.references.filter()` and passing this an arrow function
with `return reference.id !== id`.

[[[ code('9a10069221') ]]]

This callback function will be called once for each item in the array. If the
function returns true, that item will be put into the new `references` variable.
If it returns false, it won't be. The end effect is that we get an identical array,
except *without* the reference that was just deleted.

After this, call `this.render()`.

[[[ code('023225cbbf') ]]]

Let's try it! Refresh and... cool! There's our delete icon - it looks a little weird,
but we'll fix that in a minute. Let's see, in `var/uploads` we have a `rocket.jpeg`
file. Let's delete that one. Ha! It disappeared! The 204 status code looks good and...
the file is gone!

It's strange when things work on the first try!

## Alignment Tweak

While we're here, let's fix this alignment issue - it's weirding me out. Down in
the `render()` function, add a few Bootstrap classes to the download link and
make the delete button smaller.

Try that. Better... but it's still just a *touch* off. Add `vertical-align: middle`
to the download icon. It's subtle but... yep - the buttons are lined up now.

[[[ code('2246772317') ]]]

Next: our users are *begging* for another feature: the ability to rename the file
after it's been uploaded.
