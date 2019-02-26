# Image Preview on the Form

Let's use a thumbnail on the show page too. The size here is restricted to a width
of 250. Copy the first filter, paste, and call this one, how about,
`squared_thumbnail_medium`. Set the size to 500 by 500. Copy the name and this
time go into `show.html.twig`. Add the `|imagine_filter()` and paste!

Reload! It works! The first time it has the `resolve` in the URL: it's being handled
by a Symfony route & controller. The second time, it points directly to the file
that was just saved. Awesome!

## Adding an Image Preview to the Form

While we're kicking butt, go back to the article admin section and click to edit
the article we've been working on. Hmm, it's not obvious that this article has an
image attached... or what image it is. It would be *much* better if we rendered
it next to the field.

We got this. Open the form template `templates/article_admin/_form.html.twig`.
Let's think: to render an image, we could create a form theme that automatically
makes the `form_row()` function render an image preview for file fields. That's
cool. *Or*, we can keep it simple and do it right here.

Create a `<div class="row"></div>` and another `<div class="col-sm-9"><div>` inside
to set up a mini grid. Move the file field here. Now add a div with `class="col-sm-3"`:
*this* is where we'll render the image... if there is one.

To do that, we're going to need the `Article` object. Copy the image path logic
from the homepage and then go find the controller for admin section:
`ArticleAdminController`. When we render the template - this is in the `new()` action -
we're *only* passing the form variable. In `edit()`, we're doing the same thing.
We *could* add an `article` variable here - that's a *fine* option. But, we don't
need to.

Back in the template, we can say `{% if articleForm.vars.data %}` - *that* will
be the `Article` object - then `.imageFilename`. If we have an image filename,
print `<img src="{{ }}">` and paste. Replace `article` with `articleForm.vars.data`.
And yes, I *should* add an `alt` image - please do that! Set the height to 100,
because the actual thumbnail is 200 for quality reasons.

Try that! Refresh and... yes! To make sure we didn't break anything, try creating
a new article. Whoops - we broke something!

> Impossible to access attribute imageFilename on a null variable

Ah, we need to be careful here because `articleForme.vars.data` *may* be `null`
on a "new" form - it depends how you set it up. The easiest fix is to add `|default`.
It's kinda weird... when you add `|default`, it surpresses the error and just returns
`null`, which, for the if statement, is the same as `false`. It looks a little weird,
but works great. Try it. All better.

Next, now that we have a real upload system, our article data fixtures are broken!
How can we load data fixtures that set the `imageFilename` to a file that exists
in our upload directory? By using our file upload system *inside* the fixtures!
Well, at least, sort of.
