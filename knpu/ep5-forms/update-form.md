# The Edit Form

We know what it looks like to create a *new* Article form: create the form, process
the form request, and save the article to the database. But what does it look like
to make an "edit" form?

The answer is - delightfully - almost identical! In fact, let's copy all of our
code from the `new()` action and go down to `edit()`, where the only thing we're doing
so far is allowing Symfony to query for our article. Paste! Excellent.

.[[[ code('8735cedb73') ]]]

Oh, but we need a few arguments: the `Request` and `EntityManagerInterface $em`. This is
now *exactly* the same code from the new form. So... how can we make this
an edit form? You're going to love it! Pass `$article` as the second argument to
`->createForm()`.

[[[ code('69f50b019d') ]]]

We're done! Seriously! When you pass `$article`, this object - which we just got
from the database becomes the *data* attached to the form. This causes two things
to happen. First, when Symfony renders the form, it calls the *getter* methods on
that `Article` object and uses those values to fill in the values for the fields.

Heck, we can see this immediately! This is using the new template, but that's fine
temporarily. Go to `/article/1/edit`. Dang - I *don't* have an article with `id`
1. Let's go find a real id. In your terminal, run:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM article'
```

Perfect! Let's us id `26`. Hello, completely pre-filled form!

The *second* thing that happens is that, when we submit, the form system calls the
*setter* methods on that *same* `Article` object. So, we *can* still say
`$article = $form->getData()`... But these two `Article` objects will be the *exact*
same object. So, we don't need this.

So.. ah... yea! Like I said, we're done! By passing an existing object to `createForm()`
our "new" form becomes a perfectly-functional "edit" form. Even Doctrine is smart
enough to know that it needs to *update* this Article in the database instead of
creating a new one. Booya!

## Tweaks for the Edit Form

The *real* differences between the two forms are all the small details. Update the
flash message:

> Article updated! Inaccuracies squashed!

[[[ code('f72b45d7ed') ]]]

And then, instead of redirecting to the list page, give this route a
`name="admin_article_edit"`. Then, redirect right back here! Don't forget to
pass a value for the `id` route wildcard: `$article->getId()`.

[[[ code('faf37a4d27') ]]]

Controller, done!

Next, even though it worked, we don't *really* want to re-use the same Twig
template, because it has text like "Launch a new article" and "Create". Change
the template name to `edit.html.twig`. Then, down in the `templates/article_admin`
directory, copy the `new.html.twig`  and name it `edit.html.twig`, because, there's
not *much* that needs to be different.

Update the `h1` to `Edit the Article` and, for the button, `Update!`.

[[[ code('501b525dda') ]]]

Cool! Let's try this - refresh! Looks perfect! Let's change some content, hit Update
and... we're back!

## Reusing the Form Rendering Template

Cool *except*... I don't *love* having all this duplicated form rendering logic -
especially if we start customizing more stuff. To avoid this, create a *new* template
file: `_form.html.twig`. I'm prefixing this by `_` *just* to help me remember that
this template will render a little bit of content - not an entire page.

Next, copy the *entire* form code and paste! Oh, but the button needs to be different
for each page! No problem: render a new variable: `{{ button_text }}`.

[[[ code('18c48dff75') ]]]

Then, from the edit template, use the `include()` function to include
`article_admin/_form.html.twig` and pass one *extra* variable as a second argument:
`button_text` set to `Update!`.

[[[ code('3196b64bd2') ]]]

Copy this and repeat it in new: remove the duplicated stuff and say `Create!`.

[[[ code('a72efbac28') ]]]

I love it! Let's double-check that it works. No problems on edit! And, if we go to
`/admin/article/new`... nice!

## Adding an Edit Link

And just to make our admin section *even* more awesome, back on the list page,
let's add a link to edit each article. Open `list.html.twig`, add a new empty
table header, then, in the loop, create the link with `href="path('admin_article_edit')"`
passing an id wildcard set to `article.id`. For the text, print an icon using the
classes `fa fa-pencil`.

[[[ code('4503c9f464') ]]]

Cool! Try that out - refresh the list page. Hello pencil icon! Click any of these
to hop right into that form.

We just saw one of the most pleasant things about the form component: edit and new
pages are almost identical. Heck, the Form component can't even tell the difference!
All it knows is that, if we *don't* pass an `Article` object, it needs to create
one. And if we *do* pass an `Article` object, it says, okay, I'll just update that
object instead of making a new one. In both cases, Doctrine is smart enough
to INSERT or UPDATE correctly.

Next: let's turn to a *super* interesting form use-case: our highly-styled registration
form.
