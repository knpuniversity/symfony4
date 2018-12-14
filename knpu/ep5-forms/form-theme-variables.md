# Form Theming & Variables

We now know that when Symfony renders any part of your form, it looks for a specific
block in this core `form_div_layout.html.twig` template. For example, to render the
"row" part of any field, it looks for `form_row`. We *also* learned that this system
has some hierarchy to it: to render the `label` part of a `TextType` field, it
first looks for `text_label` and then falls back to using `form_label`.

Heck, there is even a `form_start` block that controls the open form tag!

We used this new knowledge to create our first form theme: we told Twig to look
*right* inside *this* template for blocks to use when rendering the registration
form. Our `form_row` block is now hooked into the form rendering process.

## The Bizarre World of a Form Theme Block

When you're inside of a block that's used by the form theming system... your world
is... weird. You *really* need to pretend like this block doesn't even exist in
this template - like it lives all by itself in its *own*, isolated template. Why?
Because these blocks are passed a *completely* different set of variables that come
from the form system: this block doesn't work like *any* of the other blocks in this
template.

I mean, look inside: there is apparently a `help` variable and a `form` variable.
So, the *big* question is: when you're in a form theme block, what variables do
you have access to?

The easiest answer is just to `dump()` inside one of these blocks.

[[[ code('fd3e5bebc6') ]]]

Move over and refresh. Woh! Yes - we see *giant* dumps for *each* row that's
rendered! There's `attr`, `id` and `full_name`. Do these... look familiar? These are
the *exact* variables that we have been *overriding* when rendering our fields!

Look back at `article_admin/_form.html.twig`. We learned earlier that there is a
variable called `label` and that the second argument of `form_row()` is an array
of variables that you want to override. You can see this in the docs: when I search
for `form_row()`, the second argument is `variables`.

Here's the point: when a field is rendered, the form system creates a bunch of variables
to help that process, and we can override them. And *those* variable are ultimately
passed... as variables, to your form theme blocks!

For example, remember how we passed a `method` variable to the `form_start()`
function? Check out the `form_start` block in the bootstrap theme. Surprise!
There is a local `method` variable that it uses to render. We *literally* override
these variables via the form rendering functions.

The point is: when you're inside a form theme block, you have access to a lot of
variables... which is *great*, because we can use those variables to do, well,
whatever we need to!

## Adding a label_attr

Back in `register.html.twig`, remove the `dump()`. On the old form, each label
had an `sr-only` class. That stands for "screen reader only" and it makes the labels
invisible. 

How can we make *our* label tag have this? Hmm. Well, inside our block, we call
`form_label()` and pass in the `form` object - which represents the form object
for whatever field is currently being rendered.

Look back at the form function reference and search for `form_label()`. Ah yes:
the second argument is the label itself. But the *third* argument is an array
of variables! And, apparently, there is a variable called `label_attr`! If we set
that, we can control the attributes on the `label` tag.

In fact, we can see this: open `form_div_layout.html.twig` and search for 
`form_label` to find that block. There it is! It does some complex processing, but
it *does* use this variable.

Actually, this is a great example of one, not-so-great thing about these templates:
they can be crazy complex!

Anyways, back on `register.html.twig`, let's customize the label attributes!
Pass `null` as the label text so it continues to use whatever the normal label is.
Then pass an array with `label_attr` set to another array, and `class` equals `sr-only`.

[[[ code('38ae04d7ea') ]]]

Phew! Let's try that. Move over refresh and... yes! They're gone! They now have
an `sr-only` class! But, hmm... we now have *no* idea what these fields are!
No worries: that was handled before via a `placeholder` attribute. New question:
how can we set this for each field? Well... it's kind of the same thing: we want
a custom attribute on each input.

The `form_widget()` function is being passed this `widget_attr` variable as its
array of variables. So, we *could* add an `attr` key to it! Except... we don't
know what the label should be! You *might* think that we could use the `label`
variable. This *does* exist, but, unless you set the label explicitly, at this
point, it's `null`. The `form_label` block holds the logic that turns the field
name into a humanized label, if it wasn't set explicitly.

No problem: there's another simple solution. Refactor the `form_widget()` call into
three, separate `form_row()` calls. Let me close a few files and - that's right!
The fields are `email` `plainPassword` and `agreeTerms`. Use `.email`, copy those,
paste twice, then `plainPassword` and `agreeTerms`.

For `email` pass a second argument with `attr` then `placeholder` set to `Email`.
Do the same thing for the one other text field: `placeholder` set to "Password".

[[[ code('74cddc9f4b') ]]]

That should be it! And yea, we *could* have been less fancy and *also* passed this
`label_attr` variable directly to `form_row()`. That would have worked *fine*.

Anyways, let's try it! Move over, refresh and... woohoo! The placeholders pop
into place. And other than my obvious typo... I think it looks pretty good!

Next: there's one field left that isn't rendering correctly: the terms checkbox.
Let's learn how to customize how a *single* field renders.
