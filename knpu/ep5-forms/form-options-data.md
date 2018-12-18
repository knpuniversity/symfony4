# Tweak your Form based on the Underlying Data

New goal team! Remember this author field? It's where we added all this nice
auto-complete magic. I want this field to be *fully* functional on the "new form",
but *disabled* on the edit form: as *wonderful* as they are, some of our alien
authors get nervous and sometimes try to change an article to look like it was written
by someone else.

This is the first time that we want the *same* form to behave in two different ways,
based on *where* it is used.

Let's see: on our new endpoint, the form creates the new `Article` object behind
the scenes for us. But on the edit page, the form is *modifying* an *existing*
`Article`: we pass this *to* the form.

So, hmm, in the `buildForm()` method of our form class, if we could get *access* to
the data that was passed to the form - either the existing `Article` object or maybe
nothing - then we could use that info to build the fields differently.

## Accessing Data via $options

Fortunately... that's *easy*. The secret is the `$options` argument that's passed
to us. Let's see what this looks like: `dd($options)` and then go back and refresh
the edit page.

[[[ code('536e2e08eb') ]]]

Wow! There are a *ton* of options. And *all* of these are things that
we could configure down in `configureOptions()`. But, the majority of this stuff
isn't all that important. However, there is *one* super-helpful key: `data`. It's
set to our `Article` object! Bingo!

Now, open another tab and go to `/admin/article/new`.

Oh. This time there is *no* `data`... which makes sense because we never passed
anything to the form. That's great! We can use the `data` key to get the underlying
data. How about: `$article = $options['data'] ?? null;`

[[[ code('69da5fc13d') ]]]

If you don't know that syntax, it basically says that I want the `$article` variable
to be equal to `$options['data']` if it *exists* and is not null. But if it does *not*
exist, set it to null. Let's dump that and make sure it's what we expect.

Refresh the new article page - yep - `null`. Try the edit page... there's the `Article`
object. *Now*, we are dangerous. Remove the `dd()` and create a new variable:
`$isEdit = $article && $article->getId()`.

[[[ code('a2dc83b1ec') ]]]

You *might* think that it's enough just
to check whether `$article` is an object. But actually, on our new endpoint, if
we wanted, we *could* instantiate a `new Article()` object and pass *it* as the
second argument to `createForm()`. You do this sometimes if you want to pre-fill
a "new" form with some default data. The form system would *update* that `Article`
object, but Doctrine would still be smart enough to insert a new row when we save.

Anyways, *that's* why I'm checking not only that the `Article` is an object, but
that it also has an `id`.

## Dynamically disabling a Field

This is great, because, our goal was to *disable* the `author` field on the
edit form. To do that, we can take advantage of an option that every field type
has: `disabled`. Set it to `$isEdit`.

[[[ code('a73b78356e') ]]]

Ok, let's try that out! Refresh the edit page. Disabled! Now try the new page:
*not* disabled. Perfect!

Oh, by the way, this `disabled` option does *two* things. First, obviously, it
adds a `disabled` attribute so that the browser prevents the user from modifying
it. But it *also* now *ignores* any submitted data for this field. So, if a nasty
user removed the `disabled` attribute and updated the field, meh - no problem - our
form will ignore that submitted data.

## Conditionally Hiding / Showing a Field

I want to do *one* more thing. The `publishedAt` field: I want to *only* show that
on the *edit* page. Because, when we're creating a new article, I don't want the
admin to be able to publish it immediately. To do that, instead of just disabling
it, I want to remove the field *entirely* from the new form.

So, yea - we *could* leverage this `$isEdit` variable: that would totally work. But,
let's make things more interesting: I want the ability to choose whether or not the
`publishedAt` field should be shown when we *create* our form in the controller.

Here's the trick: go down to the edit form. The `createForm()` method actually
has a *third* argument: an array of options that you can pass to your form. Let's
invent a new one called `include_published_at` set to `true`.

[[[ code('b80b828495') ]]]

Before doing *anything* else, try this. A huge error! *Just* like with the options
you pass to an individual *field*, you can't just *invent* new options to pass to
your form! The error says: look - the form does *not* have this option!

So... we'll add it! Copy the option name, go into  `ArticleFormType` and, down
in `configureOptions()`, add  `include_published_at` set to `false`. *This* is
enough to make this a valid option... with a default value.

[[[ code('2f63cc89de') ]]]

Now, up in `buildForm()`, the `$options` array will *always* have an
`include_published_at` key. We can use that below to say
`if ($options['include_published_at'])`, then we want that field. Remove it
from above, then say `$builder` paste and... clean that up a little bit.

[[[ code('2c9183fcae') ]]]

I love it! On the edit form, because we've overridden that option to be `true`,
when we refresh... yes! We have the field! Open up the profiler for your form and
click on the top level. Nice! You can see that a passed option
`include_published_at` was set to `true`.

For the new page, we should *not* have that field. Try it! Woh! An error from Twig:

> Neither the property `publishedAt` nor one of the methods `publishedAt()`, blah
> blah blah, exist in some `FormView` class.

It's blowing up inside `form_row()` because we're trying to render a field that
doesn't exist! Go open that template: `templates/article_admin/_form.html.twig`,
and wrap this in an if statement: `{% if articleForm.publishedAt is defined %}`,
then we'll render the field.

[[[ code('a9888295b6') ]]]

Try it again. The field is gone! And because it's *completely* gone from the form,
when we submit, the form system will *not* call the `setPublishedAt()` method at
all.

Next: let's talk about another approach to handling the situation where your form
looks different than your entity class: data transfer objects.
