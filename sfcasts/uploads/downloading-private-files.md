# Endpoint for Downloading Private Files

When we upload an article reference file, it *successfully* gets moved into the
`var/uploads/article_reference/` directory. That's great. And *that* means they're
not publicly accessible to anyone, which is what we wanted, because these files
should *only* be accessible to someone who can edit this article.

## Listing the Uploaded References

So... how *can* we allow authors to download them? As a first step, let's at least
*list* the files in the form. In `edit.html.twig`, add a `<ul>` with some Bootstrap
classes. Then, to loop, `{% for reference in article.articleReferences %}`. Inside,
add an `<li>`, a *bunch* of classes to make it look fancy, and then print, how about,
`reference.originalFilename`.

This is pretty cool: when we move the files onto the server, we give them a weird
filename. But because we saved the *original* filename, we can show that here: the
has *no* idea we're renaming things internally.

Let's see how this looks so far. Nice! 2 uploaded PDF's.

## The Download Controller

To add a download link, we know that we can't just link to the file directly:
it's not public. Instead, we're going to need link them to a Symfony route and
controller and that *controller* will check security and return the file to the
user. Let's do this in `ArticleReferenceAdminController`. Add a new public function,
how about, `downloadArticleReference()`. Add the `@Route()` above this with
`/admin/article/references/{id}/download` - where the `{id}` this time is the
ID of the `ArticleReference` object. Then, `name="admin_article_download_reference"`
and `methods={"GET"}`, just to be extra cool.

Because the `{id}` in the URL will be the id of the `ArticleReference`, we can add
that as an argument: `ArticleReference $reference`. Just `dd($reference)` so we
can see if this is working.

Love it! Copy the route name and head back into the template. Add a `<span>` here
for styling and an anchor with `href="{{ path() }}"`, the route name, and
`id: reference.id`. For the text, we can use a Font Awesome download icon.

Let's see if it works! Refresh and... download! So far so good.

## Creating a Read File Stream

In some ways, our job in the controller is really simple: read the contents of
the file and send it to the user. But... we don't *actually* want to read the
contents of the file into a string and then put it in a Response. Because if it's
a *large* file, that will eat our PHP memory.

This is already why, in `UploaderHelper`, we're using a *stream* to write the file.
And now, we'll use a stream to *read* it. To keep all this streaming logic centralized
in this class, add a new `public function readStream()` with a string `$path` argument
and `bool $isPublic` so we know which of these two filesystems to read from.

Above the method, advertise that this will return a `resource` - PHP doesn't have
a `resource` return type yet. Inside, step 1 is to get the right filesystem using
the `$isPublic` argument.

And I'm going to advertise that this is going to return a `@resource`. There's no return
type right now for resource and PHP. Our first thing, we'll get the right filesystem.
So if it's public, we're going to read where you use `$this->filesystem`. Then,
`$stream = $filesystem->readStream($path)`.

That's... pretty much it! But hold Cmd or Ctrl and click to see the `readStream()`
method. Ah yes, if this fails, Flysystem will return `false`. So let's code defensively:
`if ($resource === false)`, throw a `new \Exception()` with a nice message:

> Error opening stream for %s

and pass `$path`. At the bottom, `return $resource`.

This is great! We now have an easy way to get a stream to *read* any file in
our filesystems... which will work if the file is stored locally or somewhere else.

## Checking Security

In the controller, let's use this: add the `UploaderHelper`. Oh, but before we use
this, I forgot to check security! The goal is to allow these files to be downloaded
by anyone who has access to *edit* an article. We've been checking that via the
`@IsGranted('MANAGE')` annotation - which leverages a custom voter we created in
the Symfony series. We can use this annotation here because the `article` in the
annotation refers to the `$article` argument to the controller.

But in this new controller, we *don't* have an `article` argument, so we can't
use the annotation in the same way. No problem: add
`$article = $reference->getArticle()` and then run the security check manually:
`$this->denyAccessUnlessGranted()` with that same `'MANAGE'` string and `$article`.

Refresh to try it. We *still* have access because we're logged in as an admin.

Next, let's take our file stream and send it to the user! We'll also learn how
to control the filename and force the user's browser to download it.
