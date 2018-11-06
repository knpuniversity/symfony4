# The Edit Form

We know what it looks like to create a *new* Article form: create the form, process
the form request, and save the article to the database. But what does it look like
to make an "edit" form?

The answer is - delightfully - almost identical. In fact, let's copy all of our
code from the `new()` action, go down to `edit()` - where the only thing we're doing
so far is allowing Symfony to query for our article. Pate! Excellent. Oh, but we
need a few arguments: the `Request` and `EntityManagerInterface $em`.

This is now *exactly* the same code from the new form. So... how can we make this
an edit form? Very simple: pass `$article` as the second argument to `->createForm()`.

That's it! I love that! When you pass `$article`, the object that we just got from
the database becomes the *data* attached to he form. This causes two things to happen.
First, when Symfony renders the form, it calls the *getter* methods on that `Article`
object in order to fill in the form.

Heck, we can see this immediately! This is using the " template, but that should
be fine for a minute. Go to `/article/1/edit`. Ok, I *don't* have an article with
`id` 1 - so let's go find a real id. In the terminal, run

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM article'
```

Perfect! Let's us id `26`. Hello, completely pre-filled form!

The *second* that happens is that, when we submit, the form system calls the *setter*
methods on that *same* `Article` object. So, we *can* still say
`$article = $form->getData()`. But these two `Article` objects will be the *exact*
same object. So, we don't need this.

So.. ah... yea! that's it! By passing an existing object to `createForm()` our
new form becomes a perfectly-functional edit form. Even Doctrine is smart enough
to know that it needs to *update* this ARticle in the database instead of creating
it. Booya!

## Tweaks for the Edit Form

The *real* differences between the two forms are all the small details. Update the
flash message:

> Article updated! Inaccuracies squashed!

And then, instead of redirecting to the list page, give this route a
`name="admin_article_edit"`. Then, redirect right back here! But don't forget to
pass a value for the `id` route wildcard: `$article->getId()`.

Controller, done!

Next, even though it worked, we don't *actually* want to re-use the same Twig
template because it has things like "Launch a new article" and the word "Create".
Change the template name to `edit.html.twig`. Then, down in the `templates\`
directory, copy the new template and name it `edit.html.twig` because, there's
not *much* that needs to be different.

Update the `h1` to `Edit the Article` and, for the button, `Update!`.

Cool! Let's try this - refresh! Looks perfect! Let's change some content, hit Update
and... we are back!

## Reusing the Form Rendering Template

But, I don't *love* having all this duplicated form rendering logic - especially
if we start customizing more stuff. To avoid this, create a *new* template file:
`_form.html.twig`. I'm prefixing this by `_` *just* to help me remember that this
template renders just a little bit of content - not an entire page.

Now, copy the *entire* form code and paste! Oh, but the button needs to be different
for each page! No problem: render a new variable: `{{ button_text }}`.

Then, from the edit template, use the `include()` function to include
`article/_form.html.twig` and pass any *extra* variable as a second argument:
`button_text` set to `Update!`.

Copy this and repeat it in new: remove the duplicated stuff and say `Create!`.

Perfect! Let's double-check that it works. No problems on edit! And, if we go to
`/article/new`... nice!

And just to make our admin section *even* more awesome, back on the list page,
let's add a link to that edit each article. Open `list.html.twig`, add a new empty
table header, then, in the loop, add a link with `href="path('admin_article_edit')"`
passing an id wildcard set to `article.id`. For the text, print an icon using the
class `fa fa-pencil`.

Cool! Try that out - refresh the list page. Hello pencil icon! click any of this
to hop right to that form.

We just saw one of the most pleasant things about the form component: edit and new
pages are no different. Heck, the Form component can't even tell the difference!
All it knows is that if we *don't* pass it in an `Article` object, it needs to
create one. And if we *do* pass it an `Article` it says, okay, I'll just update
that object instead of making a new one. In both cases, Doctrine is smart enough
to INSERT or UPDATE automatically.

Next: let's turn to a *super* interesting form use-case: our highly-styled registration
form.
