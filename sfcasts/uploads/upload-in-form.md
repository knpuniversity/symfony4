# File Upload Field in a Form

We're rocking! We know what it looks like to upload a file in Symfony: we can work
with the `UploadedFile` object and we know how to move the file around. That feels
good!

It's time to talk about how to work with a file upload field inside a Symfony form.
And then, we *also* need to save the *filename* of the uploaded image to our `Article`
entity. Because, ultimately, on the homepage, we need to render each image next to
the article.

## What your Entity Should Look Like

In the `src/Entity` directory, let's look at the `Article` entity. Ok great:
the entity is *already* setup! It has an `$imageFilename` field that is a *string*.
This is important: the uploaded file will be stored... *somewhere*:
on your server, in the cloud, in your imagination - it doesn't matter. But in the
database, the *only* thing you will store is the *string* filename.

## Adding the FileType to the Form

The form that handles this page lives at `src/Form/ArticleFormType.php`. In
`ArticleAdminController`... if you scroll up a little bit... here is the `edit()`
action and you can see it using this `ArticleFormType`. Right now, this is a nice
traditional form: it handles the request and saves the `Article` to the database.
Beautifully... boring!

In `ArticleFormType`, add a new field with `->add()` and call it `imageFilename`
because that's the name of the property inside `Article`. For the type, use
`FileType::class`.

[[[ code('4b6eee2483') ]]]

But... there's a problem with this. And if you already see it, extra credit points
for you! Move over and refresh. Woh.

> The form's view data is expected to be an instance of class `File` but it is
> a `string`.

Um... ok. The problem is not super obvious... but it clearly hates *something*
about our new field. Here's the explanation: *we* know that when you upload a
file, Symfony gives you an `UploadedFile` *object*, *not* a `string`. But, the
`imageFilename` field here on `Article`... that *is* a `string`! Connecting the
form field *directly* to the string property doesn't make sense. We're missing
a layer in the middle: something that can work with the `UploadedFile` object, move
the file, and *then* set the new filename onto the property.

## Using an Unmapped Field

How can we do that? Change the field name to just `imageFile`. There is *no* property
on our entity with this name... so this, on its own, will *not* work. Pretty commonly,
you'll see people *create* this property on their entity, *just* to make the form
work. They don't persist this property to the database with Doctrine... so the idea
*works*, but I don't love it.

Instead, we'll use a trick that we talked a lot about in our forms tutorial: add
an option to the field: `'mapped' => false`. 

[[[ code('4df0eada50') ]]]

If you've never seen this before, we'll explain it in a minute. Now that we have 
a new `imageFile` field, let's go render it! Open `edit.html.twig`. Remove the 
HTML form - we're done with that. The Symfony form lives in `_form.html.twig`. 
After the title, add `{{ form_row(articleForm.imageFile }}`.

[[[ code('6a184b9f2e') ]]]

Nothing special here.

This submits back to `ArticleAdminController::edit()`. Go inside the
`$form->isValid()` block. When you have an unmapped field, the data will *not*
be put onto your `Article` object. So, how can we get it?
`dd($form['imageFile']->getData())`.

[[[ code('3bc4c4ea34') ]]]

Let's try that! Go back to your browser and hit enter on the URL: we need the
form to totally re-render. Hey! There's our new field! Select the astronaut again.
Um... did that work? Cause... I don't see the filename on my field. Yes: it *did*
work - we don't see anything because of a display bug if you're using Symfony's
Bootstrap 4 form theme. We'll talk about that later. But, the file *is* attached
to the field. Hit Update!

Yes! It's our beloved `UploadedFile` object! We *totally* know how to work with
that! Oh, but before we do: I want to point out something cool. Inspect element
and find the `form` tag. Hey! It has the `enctype="multipart/form-data"` attribute!
We get that for free because we use the `{{ form_start() }}` function to render
the `<form>` tag. As *soon* as there is even *one* file upload field in the form,
Symfony adds this attribute for you. High-five team!

## Moving the Uploaded File

Time to finish this. Let's upload a different file - `earth.jpeg`. And... there's
the dump. We have two jobs in our controller: move this file to the final location
*and* store the new filename on the `$imageFilename` property. Back in the controller,
scroll down to `temporaryUploadAction()`, steal all its code, and delete it.

Up in `edit()`, remove the `dd()` and set this to an `$uploadedFile` variable.
Add the same inline phpdoc as last time

[[[ code('d63cbc215b') ]]]

then paste the code. Yep! We'll move the file to `public/uploads` and give it a unique 
filename. Take off the `dd()` around `move()`. 

[[[ code('ac10833f6c') ]]]

*Now*, call `$article->setImageFilename($newFilename)` 

[[[ code('e8d47716d8') ]]]

and let Doctrine save the entity, *just* like it already was.

Beautiful! I *do* want to point out that the `$newFilename` string that we're storing
in the database is *just* the filename: it doesn't contain the directory or the
word `uploads`: it's... the filename. Oh, for my personal sanity, let's upload
things into an `article_image` sub-directory: that'll be cleaner when we start
uploading multiple types of things. Remove the old files.

[[[ code('704482e4c2') ]]]

Moment of truth! Find your browser, roll up your sleeves, and refresh! Um...
it *probably* worked? In the `uploads/` directory... yea! There's our Earth file!
Let's see what the database looks like - find your terminal and run:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM article WHERE id = 1'
```

Let's see, the id of this article is 1. Yes! the `image_filename` column is
*totally* set! Fist-pumping time!

## Avoid Processing when no Upload

Oh, but there is one tiny thing we need to clean up before moving on. What if we
just want to, I don't know, edit the article's title, but we don't need to change
the image. No problem - hit Update! Oh... That's HTML5 validation. You might remember
from the forms tutorial that this `required` attribute is added to *every* field...
unless you're using form field type guessing. It's annoying - fix it by adding
`'required' => false`.

[[[ code('4a84034625') ]]]

Let's try it again. Refresh, change the title, submit and... oof.

> Call to a member function `getClientOriginalName` on null

Of course! We're not uploading a file! So the `$uploadedFile` variable is null!
That's ok! If the user didn't upload a file, we don't need to do *any* of this
logic. In other words, `if ($uploadedFile)`, then do all of that. Otherwise,
skip it!

[[[ code('c25e2d4ea2') ]]]

Refresh now. Got it!

Next: This is looking good! Except that... we need this *exact* same logic in
the `new()` action. To make a *truly* killer upload system, we need to refactor
the upload logic into a reusable service.
