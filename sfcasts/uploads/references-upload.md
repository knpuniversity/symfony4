# Uploading References

Unlike the main form on this page, this form will submit to a *different* endpoint.
And instead of continuing to put more things into `ArticleAdminController`, let's
create a new controller for everything related to article references:
`ArticleReferenceAdminController`. Extend `BaseController` - that's just a small
base controller we created in our Symfony series - it extends the normal
`AbstractController`. So nothing special happening there.

## The Upload Endpoint

Back in the new class, create `public function uploadArticleReference()` with
the normal `@Route` above this - make sure to get the one from `Symfony/Component`.
Set the URL to, how about, `/admin/article/{id}/references` - where the `{id}` is
the `Article` id that we want to attach the reference to. Add
`name="admin_article_add_reference"`. Oh, and let's also set `methods={"POST"}`.

That's optional, but it'll let us create *another* endpoint later with the same
URL that can be used to *fetch* all the references for a single article.

Let's keep going! Because the article `{id}` is in the URL, add an `Article $article`
argument. Oh, and we need some security! You can only upload a file if you have
access to *edit* this article. In this app, we check that with this
`@IsGranted("MANAGE", subject="article")` annotation, which leverages a custom
voter that we created in our Symfony series. It basically makes sure that you are
the *author* of this article or a super admin.

Finally, we're ready to fetch the file: add the `Request` argument - the one from
`HttpFoundation` - and let's `dd($request->files->get())` and then the name from
the input field: `reference`.

Solid start. Copy the route name and head back in the template. Set the `action`
attribute to `{{ path() }}`, the route name, and for the placeholder part, I'll
use multiple lines. Pass `id` set to `article.id`. Oh wait... we don't have an
`article` variable inside this template. We do have the `articleForm` variable,
and we could get the `Article` from that... but to help shorten things, let's
properly pass this in.

Find the `edit()` action of `ArticleAdminController` and pass in an `article`
variable. Now we can say `article.id`.

Phew! Ok, let's check this out: refresh and inspect element on the form. Yep,
the URL looks right, `enctype` attribute is there. Ok, try it: select the Symfony
Best Practices doc and... upload! Yes! It's our favorite `UploadedFile` object!

These article references are special because we need to keep them private: they
should *only* be accessible to the author if the article or a super admin. The
process for uploading & download private files, is a bit different.

## Setting up UploaderHelper

But, we'll start in very similar way: by opening our *favorite* service, and all-around
nice class, `UploaderHelper`. Down here, ad a new
`public function uploadArticleReference()` that will have a `File` argument and
return a `string`... pretty much the same as the other method, except that we won't
need an `$existingFilename` because we won't let `ArticleReference` objects be
updated. If you want to upload a modified file - cool! Delete the old `ArticleReference`
and just upload a new one - you'll see what I mean as we keep building this out.

To get started, just `dd($file)`.

Back in the controller, let's finish this *whole* thing. Set the file to an
`$uploadedFile` object and I'll add the same inline documentation that says this
is an `UploadedFile` object - the one from `HttpFoundation`. The say
`$filename =`... oh - we still need the `UploaderHelper` object. Add that argument:
 `UploaderHelper $uploaderHelper`. Then
`$filename = $uploaderHelper->uploadArticleReference($uploadedFile)`.

We know that won't work yet... but if we use our *imaginations*, we know that
someday it should return the new filename that was stored on the filesystem.
To put this value into the database, we need to create a new `ArticleReference`
object and persist it.

## Tightening Up ArticleReference

Oh, but before we do - go open that class. This is a *very* traditional entity:
it has some properties and everything has a getter and a setter. That's great,
but because every `ArticleReference` *needs* to have its `Article` property set...
and because an `ArticleReference` will never *change* articles, find the `setArticle()`
method and... delete it!

Instead, add a `public function __construct()` with a required `Article` argument.
Set *that* onto the `article` property. This is an optional step - but it's always
nice to thing critically about your entities: what methods do you not need?

## Saving ArticleReference & the Original Filename

Back up in our controller, say `$articleReference = new ArticleReference()` and
pass `$article`. Call `$article->setFilename($filename)` to store the unique filename
where this file was stored on the filesystem.

But remember! There are a couple of *additional* pieces of information that we can
set on `ArticleReference` - like the *original* filename - set that to
`$uploadedFile->getClientOriginalName()`. Now, *technically* this method can return
`null`, though, I'm not actually sure if that's something that can happen in any
realistic scenario. But, just in case, add `?? $filename`. So, if the client original
name is missing for some reason, fall back `$filename`.

Finally, *just* in case we ever want to know what *type* of file this is, we'll
store the file's mime type. Set this to `$uploadedFile->getMimeType()`. This can
*also* return null - so default it to `application/octet-stream`, which is sort
of a common way to say "I have no idea what's inside this file".

With that done, let's save this: add the `EntityManagerInterface $entityManager`
argument, then add `$entityManager->persist($articleReference)` and
`$entityManager->flush()`. Finish with `return redirectToRoute()` and send the
user back to the edit page: `admin_article_edit` passing this `id` set to
`$article->getId()`.

Yep - that's the route on the edit endpoint.

Alright! With any luck, it should it our `dd()` statement. Go back to your browser:
I already have the Simply Best Practices PDF selected. Hit update - excellent!
`UploadedFile` coming from `UploaderHelper`.

Next: let's move the uploaded file... except that... we can't move it using the
filesystem object we have now... because we can't store these private files in
the `public/` directory.
