# File Validation

I've ignored it long enough - sorry! We've *gotta* add some validation to the upload
field. Because... right now, we can upload *any* file type - it's madness! This
is supposed to be an image field people! We need to *only* allow pngs, jpegs, gifs,
image stuff.

## Validating an Unmapped Field

Normally we add validation to the entity class: we would go into the `Article`
class, find the property, and add some annotations. But... the field we want to
validate is an *unmapped* form field - there *is* no `imageFile` property in
`Article`.

No worries: for unmapped fields, you can add validation directly to the form with
the `constraints` option. And when it comes to file uploads, there are two really
important constraints: one called `File` and an even stronger one called `Image`.
Add `new Image()` - the one from the `Validator\Constraints`.

[[[ code('b0dec53405') ]]]

## The Image Constraint

And... that's all we need! That's enough to make sure the user uploads an image.
Check it out: find your browser, Google for "Symfony image constraint" and click
into the docs.

The `Image` constraint *extends* the `File` constraint - so both basically have
the same behavior: you can define a `maxSize` or configure different `mimeTypes`.
The `Image` constraint just adds... more super-powers. First, it pre-configures
the `mimeType` option to only allow images. And you get a crazy-amount of
other image stuff - like `minWidth`, `maxWidth` or `allowPortrait`.

So let's test it! Refresh the page and browse. Oh, the Symfony Best Practices PDF
snuck into my directory. Select that, update and... boom! This file is not a valid
image.

## Validating the File Size

Go back to the docs and click to see the `File` constraint. The other most common
option is `maxSize`. To see what that looks like, set it to something *tiny*,
like `5k`.

[[[ code('aefa1102c7') ]]]

Ok: browse and select *any* of the files. Hit update and... perfect: the
file is too large.

Change that back to `5M`, or whatever makes sense for you.

[[[ code('0133a520fe') ]]]

## Validation and upload_max_filesize

Oh, but, remember a few minutes ago when we tried to upload the stars photo? It's
3 megabytes, which is way under the 5 megabytes we just set, but *above* my
php.ini `upload_max_filesize` setting. That caused a really *nasty* error.

Well, try selecting it again and updating. Yes! When you use the `File` or `Image`
constraint, they *also* catch any PHP-level upload errors and display them quite
nicely. You *can* customize this message.

## Making the Upload Field Required

And... that's it! Sure, there are a more options and you can control all the messages -
but that's easy enough. Except... there *is* one tricky thing: how can we make the
upload field required? Like, when someone *creates* an article, they should be required
to upload an image before saving it.

Simple, right? Just add a `new NotNull()` constraint to the `imageFile` field.
Wait, no, that won't work. If we did that, we would need to upload a file even
if we were just editing a field on the article: we would literally need to upload
an image *every* time we changed anything.

Okay: so we want the `imageFile` to be required... but *only* if the `Article`
doesn't already have an `imageFilename`. Start by breaking this onto multiple
lines. Then say `$imageConstraints =`, copy the `new Image()` stuff and paste it
here. 

[[[ code('7aa3ba2a66') ]]]

Down below, set `'constraints' => $imageConstraints`. Oh... and let's
spell that correctly.

[[[ code('129238d7bc') ]]]

*Now* we can conditionally add the `NotNull()` constraint *exactly* when we need
it. Scroll up a little. In our forms tutorial, we used the `data` option to get
the `Article` object that this form is bound to. If this is a "new" form, there
may or may not be an `Article` object - so this will be an `Article` object or
`null`. I also used that to create an `$isEdit` variable to figure out if we're
on the edit screen or not.

We can leverage that by saying if this is *not* the edit page or if the article
doesn't have an image filename, then take `$imageConstraints` and add
`new NotNull()`. We'll even get fancy and customize the message:
`Please upload an image`.

[[[ code('2dc226f67c') ]]]

Just saying if `!$isEdit` is probably enough... but *just* in case, I'm checking
to see if, *somehow*, we're on the edit page, but the `imageFilename` is missing,
let's require it.

Cool: testing time! Refresh the entire form, but don't select an upload: we know
that this `Article` *does* have an image already attached. Hit update and...
works fine! Now try creating a new Article, fill in a few of the required fields,
hit create and... boom! Please upload an image!

Validation, check! Next, let's fix how this renders: we've *gotta* see the filename
after selecting a file - seeing nothing is bummin' me out.
