# Uploading References

Unlike the main form on this page, this form will submit to a *different* endpoint.
And instead of continuing to put more things into `ArticleAdminController`, let's
create a new controller for everything related to article references:
`ArticleReferenceAdminController`. Extend `BaseController` - that's just a small
base controller we created in our Symfony series: it extends the normal
`AbstractController`. So nothing magic happening there.

## The Upload Endpoint

Back in the new class, create `public function uploadArticleReference()` and, above,
`@Route`: make sure to get the one from `Symfony/Component`.
Set the URL to, how about, `/admin/article/{id}/references` - where the `{id}` is
the `Article` id that we want to attach the reference to. Add
`name="admin_article_add_reference"`. Oh, and let's also set `methods={"POST"}`.

That's optional, but it'll let us create *another* endpoint later with the same
URL that can be used to *fetch* all the references for a single article.

Let's keep going! Because the article `{id}` is in the URL, add an `Article $article`
argument. Oh, and we need security! You can only upload a file if you have access
to *edit* this article. In our app, we check that with this
`@IsGranted("MANAGE", subject="article")` annotation, which leverages a custom
voter that we created in our Symfony series. It basically makes sure that you are
the *author* of this article or a super admin.

Finally, we're ready to fetch the file: add the `Request` argument - the one from
`HttpFoundation` - and let's `dd($request->files->get())` and then the name from
the input field: `reference`.

Solid start. Copy the route name and head back to the template. Set the `action`
attribute to `{{ path() }}`, the route name, and for the placeholder part, I'll
use multiple lines and pass `id` set to `article.id`. Oh wait... we don't have an
`article` variable inside this template. We do have the `articleForm` variable,
and we *could* get the `Article` from that... but to shorten things, let's properly
pass it in.

Find the `edit()` action of `ArticleAdminController` and pass an `article`
variable. *Now* we can say `article.id`.

Phew! Ok, let's check this out: refresh and inspect element on the form. Yep,
the URL looks right and the `enctype` attribute is there. Ok, try it: select
the Symfony Best Practices doc and... upload! Yes! It's our favorite
`UploadedFile` object!

These article references are special because we need to keep them private: they
should *only* be accessible to the author or a super admin. The process for
uploading & downloading private files is, a bit different.

## Setting up UploaderHelper

But, we'll start in very similar way: by opening our *favorite* service, and all-around
nice class, `UploaderHelper`. Down here, add a new
`public function uploadArticleReference()` that will have a `File` argument and
return a `string`... pretty much the same as the other method, except that we won't
need an `$existingFilename` because we won't let `ArticleReference` objects be
updated. If you want to upload a modified file - cool! Delete the old
`ArticleReference` and upload a new one. You'll see what I mean as we keep
building this out.

To get started, just `dd($file)`.

Back in the controller, let's finish this *whole* darn thing. Set the file to an
`$uploadedFile` object and I'll add the same inline documentation that says that
this is an `UploadedFile` object - the one from `HttpFoundation`. Then say
`$filename =`... oh - we don't have the `UploaderHelper` service yet! Add that
argument: `UploaderHelper $uploaderHelper`. Then
`$filename = $uploaderHelper->uploadArticleReference($uploadedFile)`.

We know that won't work yet... but if we use our *imagination*, we know that...
someday, it should return the new filename that was stored on the filesystem.
To put this value into the database, we need to create a new `ArticleReference`
object and persist it.

## Tightening Up ArticleReference

Oh, but before we do - go open that class. This is a *very* traditional entity:
it has some properties and everything has a getter and a setter. That's great,
but because every `ArticleReference` *needs* to have its `Article` property set...
and because an `ArticleReference` will never *change* articles, find the `setArticle()`
method and... obliterate it!

Instead, add a `public function __construct()` with a required `Article` argument.
Set *that* onto the `article` property. This is an optional step - but it's always
nice to think critically about your entities: what methods do you *not* need?

## Saving ArticleReference & the Original Filename

Back up in our controller, say `$articleReference = new ArticleReference()` and
pass `$article`. Call `$article->setFilename($filename)` to store the unique filename
where this file was stored on the filesystem.

But remember! There are a couple of *new* pieces of info that we can set on
`ArticleReference` - like the *original* filename. Set that to
`$uploadedFile->getClientOriginalName()`. Now, *technically* this method can return
`null`, though, I'm not actually sure if that's something that can happen in any
realistic scenario. But, just in case, add `?? $filename`. So, if the client original
name is missing for some reason, fall back to `$filename`.

Finally, *just* in case we ever want to know what *type* of file this is, we'll
store the file's mime type. Set this to `$uploadedFile->getMimeType()`. This can
*also* return null - so default it to `application/octet-stream`, which is sort
of a common way to say "I have no idea what this file is".

With that done, save this: add the `EntityManagerInterface $entityManager`
argument, then `$entityManager->persist($articleReference)` and
`$entityManager->flush()`. Finish with `return redirectToRoute()` and send the
user back to the edit page: `admin_article_edit` passing this `id` set to
`$article->getId()`.

Yep - that's the route on the edit endpoint.

Alright! With any luck, it should hit our `dd()` statement. Go back to your browser:
I already have the Symfony Best Practices PDF selected. Hit update... yea!
`UploadedFile` coming from `UploaderHelper`.

Next: let's move the uploaded file... except that... we can't move it using the
filesystem service object we have now... because we can't store these private files
in the `public/` directory. Hmm...
