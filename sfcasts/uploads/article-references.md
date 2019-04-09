# Setup for Uploading Private Article References

New challenge folks! Our alien authors are *begging* for a new feature: they want
to be able to upload "supporting" files and attach them to the article - like
PDFs that they're referencing, images... text notes... really anything. But these
files will *only* be visible to anyone that can *edit* an article. I'll call these
"article references" and every article will be able to have zero to many references, which is where things start to get interesting.

## Creating the ArticleReference Entity

Let's create the new entity:

```terminal
php bin/console make:entity
```

Call it `ArticleReference` and give it an `article` property. This will be a
`relation` back to the `Article` class. This will be a ManyToOne relation:
each `Article` can have many ArticleReferences. Then, this will be not null in
the database: every `ArticleReference` *must* be related to an `Article`. Say yes
to map the other side of the relationship - it's convenient to be able to say
`$article->getArticleReferences()`. And  `no` to orphan removal - we won't be using
that feature.

Nice! Ok, this needs a few more fields: `filename` a string that will hold the
filename on the filesystem, `originalFilename`, a string that will hold the
*original* filename that was on the user's system - more on that later - and
`mimeType` - we'll use that to store what *type* of file it is - which will
come in handy later.

And... done! Next run:

```terminal
php bin/console make:migration
```

Let's go make sure the migration file doesn't contain any surprises... yep!

> CREATE TABLE `article_reference`

... with a foreign key back to `article`. Run that with:

```terminal
php bin/console doctrine:migrations:migrate
```

## Removing Extra Adder/Remover

Before we get back to work, open the `Article` entity. The command *did* create
the `$articleReferences` property that allows us to say
`$article->getArticleReferences()`. That's super convenient. It also added
`addArticleReference()` and `removeArticleReference()`. I'm going to delete these:
I'm just not going to need them: I'll read the references from the article, but
never set them from this direction.

[[[ code('2d16bec1ee') ]]]

## Form CollectionType

Ok team: let's think about how we want this to work. The user needs to be able to
upload *multiple* reference files to each article. A lot of you *may* be expecting
me to use Symfony's `CollectionType`: that's a special field that allows you to
embed a *collection* of fields into a form - like multiple upload fields.

Well... sorry. We are definitely *not* going to use `CollectionType`. That field
is hard *enough* to work with if you want to be able to add or delete rows. Adding
uploading to that? Oof, that's crazy talk.

We're going to do something different. And it's going to be a *much* better user
experience anyways! We're going to leave the main form alone and build a separate
"article reference upload", sort of, "widget", next to it that'll eventually upload
via AJAX, allow deleting, editing and re-ordering. It's gonna be schweet!

## Adding the HTML Form

Open the edit template: `templates/article_admin/edit.html.twig`. Everything we're
going to do will be inside of this template, *not* the new template. The reason
is simple: trying to upload files to a *new* entity - something that hasn't been
saved to the database - is super hard! You need to store files in a temporary spot,
keep track of them, and assign them to the entity when your user *does* finally
save - if they ever do that. So, totally possible - but complex. If you can, have
your user fill in some basic data, *save* your new entity to the database, then
show the upload fields.

Anyways, let's add an `<hr>` and set up a bit of structure: `div class="row"` and
`div class="col-sm-8"`. Say "Details" here and move the entire form inside. Now
add a `div class="col-sm-4"` and say "References".

[[[ code('dab375073e') ]]]

Let's see how this looks... nice! Form on the left, upload widget thingy on the
right.

Here's the plan: add a `<form>` tag with the normal `method="POST"` and
`enctype="multipart/form-data"`. Inside, add a single upload field:
`<input type="file" name="">`, how about `reference`. Then,
`<button type="submit">`, some classes to make it not ugly, and "Upload".

[[[ code('e8c865e75e') ]]]

Cool! Yes, we *are* going to talk about allowing the user to upload *multiple*
files at once. Don't worry, things are going to get *much* fancier.

Next, let's get the endpoint setup for this upload and store everything in the
database, including a few pieces of information about the file that we did
*not* store for the article images.
