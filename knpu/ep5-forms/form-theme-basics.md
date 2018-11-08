# All about Form Themes

There's just one problem left with our registration form - it looks terrible! It
does *not* look like our original form, which was styled really nicely. One of the
*trickiest* things to do with the form system is to style, or *theme* your forms.
The system is super powerful - we just need to unlock it!

## Adding Attributes to the <form> Tag

Here's the goal: make our form render the same markup that we had before. First,
the form tag itself had a class called `form-signin`. Google for
"Symfony form function reference". Hey, we know this page before! It lists all of
the functions that we can call to render different parts of our form! And, it
will give us a clue about how we can customize each part.

For example, the first argument to `form_start()` is called `view`. When you see
the "view", it's referring to your form variable. The *really* important argument
is the second one: `variables`. We saw this before: almost *every* function on
here has this mysterious variables argument. This is an array of, literally, Twig
variables, that are used to render each part of the form.

For example, there is apparently a variable called `method` that you can set to
control the `method` attribute on the form. But, it's not as simple as: every variable
becomes an attribute - the `method` variable is actually a bit special. So, how
can we set a `class` attribute?

Scroll all the way down to the bottom of this page. Remember this table? It shows
the most common variables that we can override. One of the most important ones is
`attr`. Let's try that! Add a second argument - an array, with an `attr` key set
to *another* array with `class` set to `form-signin`. Phew! And while I'm here,
we also had an `<h1>` before. Let's add that right at the beginning of the form.

One small step forward! Let's try it! Oh, already, *so* much better. Heck, I can
even see my agree to terms checkbox again!

## The Core Bootstrap Form Theme

Now, things get more interesting. The original fields were just a label and an
input. The input has a class on it, but otherwise, it's pretty straightforward.
But the Bootstrap form theme renders everything inside of a `form-group` div.
*Then* there's the `<label>` and the `<input>`. So, hmm: we need to change how all
of this markup has rendered. To do that we need to dive deep: we need to learn
*how* the form theme system works under the hood.

Earlier, we opened `config/packages/twig.yaml` and added a `form_themes` line
that pointed to a core template called `bootstrap_4_layout.html.twig`. This...
instantly, made everything pretty!

Whenever Symfony renders *any* part of your form, there is a Twig template deep
in the core that contains the markup for that one piece: like the label, the widget
or the errors. And we can *override* this!

Press Shift+Shift to open this template: `bootstrap_4_layout.html.twig`. Yep!
This is probably the *strangest* Twig template that you'll ever see. It's, huh,
just a *ton* of blocks: block `time_widget`, `percent_widget`, `file_widget` and
many, many more.

## Form Theme Template & Block System

Here's how it works: every field has five different component: the `row` and the
4 things it contains: the field `widget` `label`, `errors` and `help`. When you
render each of those parts, Symfony opens this template, selects the correct block
for the thing it's rendering, and renders it like a mini-template. It passes all
the variables into that block. Yea, it's a *totally* cool, but weird use of Twig.

Go back to our form class to see an example. Oh, I totally forgot! We can set
`email` to an `EmailType::class`. That will make it render as `<input type="email">`
instead of text. And *that* will give us some extra HTML5 validation.

Here's the key: to render the "widget" part of an "email" field type, Symfony looks
for a block called `email_widget`. That's the pattern: the block is always the
field type - `email` - then the "part" - `widget`. Ok... so let's look for the
`email_widget` block!

Oh... boo - it doesn't exist? What? This block lives in *another* template that
lives right next to this. I'll click the `Form` directory on top, then open a
*super* important template called `form_div_layout.html.twig`.

*This* is Symfony's default form theme template. And *even* if you don't list this
in your `twig.yaml` file, Symfony *always* uses it. What I mean is: when Symfony
searches for a block - like `email_widget` - it will look in
`bootstrap_4_layout.html.twig` first. But if it is *not* there, it will *also*
look here/

Let's search again for `email_widget`. Boom! *This* is the block that's responsible
for rendering the `widget` part of the `email` field. Want to find the block that
renders the widget part of the `PasswordType`? There it is: `password_widget`.
Both of these execute *another* block - `form_widget_simple` to do the real work.

So... cool! By understanding the naming system for these blocks, we can create
our *own* form theme template and create blocks that can override *any* part of
*any* field type. Sweet!

But... there is *one* surprise left. What is the name of the block that Symfony
looks for when you're rendering the `label` part of a password type field? It's
`password_label`, right? Search for that. It's not found! To understand why, we
need to learn a bit about field type hierarchy. Then, we'll be ready to create
our own form theme. That's next!