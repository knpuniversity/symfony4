# Form Rendering Variables

Find the `form_row()` documentation. There is one *super* important thing that
almost all of these functions share: their last argument is something called
`variables`.

These `variables` are *key* to controlling how each part of each field is rendered.
And it's explained a bit more at the bottom. Yep - this table describes the most
common "variables" - which are kind of like "options" - that you can pass to most
fields, including `label`. 

Let's override that variable for the title. Pass a second argument to `form_row()`
set to an array with a `label` key. How about, `Article title`.

Try that! Reload the form. Boom! Label changed!

## Discovering the Form Variables

There are a *ton* of variables that you can pass to the form rendering functions
to change how the field is rendered. And the *list* of those variables is actually
*slightly* different for each field type. The *best* way to see *all* the possibilities
is back inside our best friend: the form profiler

Click on the field you want to customize - like `title`. I'll collapse the options
stuff. Remember: options are what we can pass to the third argument of the `add()`
function in our form class.

For rendering, we're interested in the "View Variables". Behind the scenes, each
part of each field is rendered by a mini Twig template that lives inside Symfony.
We'll see this later. These variables are *passed* to those Twig templates and used
to control, well, almost everything.

Hey! There's the `label` variable we just overrode! Notice, it's `null`: the values
inside the profiler represent the values at the moment the form object is passed
into the Twig template. So, if you override a value - it won't show up here. No
big deal - just don't let it surprise you.

Ah, and there's the `help` message and a whole bunch of other things that help the
form do its job, like `full_name`, which will be the `name` attribute, and even
the `id`.

By the way, if it's useful, in addition to overriding these variables, you can access
them directly in your template. I don't need it here, but you could, for example
print `articleForm.title.vars.id`.

If you go back and look at your form, that will print the `id` attribute that's
printed on the form field. Pretty cool, though, the *real* purpose of variables
is to *override* them when rendering each field.

## form_label(), form_widget(), form_help(), form_errors()

Using `form_row()` gave us a more flexibility, because we can reorder the fields
and override any variables we need. If you need a little bit *more* flexibility,
another option is to render the 4 components of each field independently.

for example, get rid of `form_row`. And, instead, render each part manually:
`{{ form_label(articleForm.title) }}`, and for this function, the second argument
is the label. Then `{{ form_errors(articleForm.title) }}` for the validation errors,
`{{ form_widget(articleForm.title }}` to print the input field, and
`{{ form_help(articleForm.title }}`.

Let's see how this compares to using `form_row()`. Refresh! Hmm - it's *almost*
the same. If you look more closely, of course! The other fields are wrapped in this
`form-group` div, but the title doesn't have that!

When you render things at this level, you start to lose some of the special formatting
that `form_row()` gives you. Sure, it's easy to re-add that div. But `form_row`
also adds a special error class to that div when there is a validation error.

For that reason, let's go back to using `form_row()`. A little bit later, we're
going to learn how we can use `form_row()`, but completely customize how it looks
for one specific form, or across your entire site, by creating a "form theme". It's
kind of the best of both worlds: you can render things in the lazy way, but still
have the control you need.

But before we get there - let's learn how to create an "edit" form so we can update
articles!