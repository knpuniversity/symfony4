# Form Theme Block Naming & Creating our Theme!

When Symfony renders the "label" part of a password field type... it *should* be
looking for a `password_label` block name. And... it *is*. But... that block doesn't
exist! What's going on?

Here's the situation: the label will look the same for probably *every* field
type: there's no difference between how a label should render for a text field versus
a choice drop-down. To avoid duplicating the label code over and over again, the
block system has a fallback mechanism.

## Block Prefixes

Go back to your browser, click on the form icon on the web debug toolbar and select
`plainPassword`. Go check out the "View Variables". Ah, here it is: the very
special `block_prefixes` variable! This is an array that Symfony uses when trying
to find which block to use. For example, to render the "widget" for this field,
Symfony *first* looks for a block named `_user_registration_form_plainPassword_widget`.

This *super* specific block name will allow us to change how the widget looks for just
*one* field of the form. We'll do this a bit later. If it does *not* find that block,
it *next* looks for `password_widget`, then `text_widget`, and finally
`form_widget`. There *is* a `password_widget` block but, when the label is being
rendered, there is *not* a `password_label` block. Ok, so it next looks for
`text_label`. Let's see if that exists. Nope! Finally, it looks for `form_label`.
Search for that. Got it!

*This* is the block that used to render *every* label for *every* field type.

## The Form Rendering Big Picture

Open up `register.html.twig`: let's back up and make sure this all makes sense.
When we call `form_widget(registrationForm)`, that's a shortcut for calling
`form_row()` on each field. That means that the "row" part of each field is rendered.
Not surprisingly, the "row" looks exactly the same for all field types. In other
words, in `bootstrap_4_layout.html.twig`, you probably won't find a `password_row`
block, but you *will* find a `form_row` block. Keep searching until you find it...
there it is!

Ah, I love it! It has some special logic on top, but then! Yes: it renders a `div`
with a `form-group` class then calls the `form_label()`, `form_widget()` and
`form_help()` functions! The reason you don't see `form_errors()` here is that it's
called from inside of `form_label()` so we can get the correct Bootstrap markup.

## Creating our Form Theme

We *now* know enough to be dangerous! If we could override this `form_row` block
*just* for the registration form, we could simplify the markup to match what we
need. How do we do that? By creating our own form theme... which is just a template
that contains these fancy blocks.

If you create a form theme in its own template file - like
`bootstrap_4_layout.html.twig` - you can reuse it across your *entire* app by adding
it to `twig.yaml` after bootstrap. Or, you can add some code to your Twig template
to use a specific form theme template only on certain forms.

But, we actually will *not* create a separate template for our form theme. Why not?
If you only need to customize a *single* form, there's an easier way. At the top
of the template where you form lives, add `{% form_theme %}`, the name of your form
variable - `registrationForm` - and then `_self`.

[[[ code('48a1e6d334') ]]]

This says:

> Yo form system! I want to use *this* template as a form theme template for the
> `registrationForm` object.

As *soon* as we do this, when Symfony renders the form, it will *first* look for
form theme blocks right inside of *this* template. Yep, we could copy that `form_row`
block from Bootstrap, paste it, and start customizing!

Let's do that! But, actually, the Bootstrap `form_row` block is a bit fancier than
I need. Instead, open `form_div_layout.html.twig` and find the block there. Copy
that and, in `register.html.twig`, paste this anywhere.

[[[ code('bc91de8542') ]]]

Hmm - let's remove the wrapping `<div>` and see if this works! Deep breath - refresh!
I saw something move! Inspect the form and... yes! That wrapping `div` is gone!

[[[ code('4f9a34cc56') ]]]

When Symfony looks for the `form_row()` block it finds *our* block and uses it.
All the other parts - like the `widget` and `label` blocks - are *still* coming from
the Bootstrap theme. It's *perfect*.

But, we have more work to do! Next, let's learn a lot more about what we can do
inside of these form theme blocks.
