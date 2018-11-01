# Bind Your Form to a Class

We created a form type class, used it in the controller to process the form submit
*and* rendered it. This is pretty basic, but the form system is already doing a lot
for us!

But... I think the form component can do more! Heck, I think it's been downright
*lazy*. `$data = $form->getData()` gives us an associative array with the submitted &
normalized data. That's cool... but it *does* mean that we need to set all of that
data onto the `Article` object manually. Lame!

## Setting the data_class Option

But, no more! Open `ArticleFormType`. Then, go back to the Code -> Generate menu -
or Cmd+N on a Mac - select "Override Methods" and choose `configureOptions()`.
Just like with `buildForm()`, we don't need to call the parent method because it's
empty. Inside add `$resolver->setDefaults()` and pass an array. This is where
you can set *options* that control how your form behaves. And, well... there
aren't actually very many options. The most important, by *far*, is `data_class`.
Set it to `Article::class`. This *binds* the form to that class.

[[[ code('9aca337718') ]]]

And... yep! *That* little option changes *everything*. Ready to see how? Back in
your controller, `dd($data)`.

[[[ code('ffd6473436') ]]]

Now, move back to your browser. Watch closely: right now both fields are simple
text inputs... because we haven't configured them to be anything else. But, refresh!

## Form Field Type Guessing

Whoa! The content is now a `textarea`! We haven't talked about it yet, but we can,
of course, configure how each field is rendered. By default, if you do nothing,
everything renders as a text input. But, when you bind your form to a class, a
special system - called the "form type guessing" system - tries to *guess* the proper
"type" for each field. It notices that the `$content` property on `Article` is a longer
`text` Doctrine type. And so, it basically says:

> Hey peeps! This content field looks pretty big! So, let's use a textarea
> field type by default.

Anyways, form field type guessing is a *cool* feature. But, it is actually *not*
the super important thing that just happened.

What was? Create another breaking news story:

> Orion's Belt: for Fashion or Function?

Click Create and... yes! Check it out! `$form->getData()` is now an `Article` object!
And the `title` and `content` properties are already set! *This* is the power of
the `data_class` option.

When the form submits, it notices the `data_class` and so creates a `new Article()`
object for us. Then, it uses the *setter* methods to populate the data. For example,
the form has two fields: `title` and `content`. When we submit the form, it calls
`setTitle()` and then `setContent()`. It's basically just an automatic way to do
what we are *already* doing manually in our controller. This is *awesome* because
we can remove code! Just say `$article = $form->getData()`, done. To
help PhpStorm I'll add some inline documentation that says that this is an `Article`.

[[[ code('02e644a65d') ]]]

That's great! Our controller is tiny and, when we submit, bonus! It even works!

## Model Classes & Complex Forms

In most cases, *this* is how I use the form system: by binding my forms to a class.
But! I *do* want you to remember one thing: if you have a super complex form that
looks different than your entity, it's perfectly okay to *not* use `data_class`.
Sometimes it's simpler to build the form exactly how you want, call `$form->getData()`
and use that associative array in your controller to update what you need.

Oh, and while we *usually* see form types bound to an *entity* class, that's not
required! This class could be *any* PHP class. So, if you have a form that doesn't
match up well with any of your entities, you *can* still use `data_class`. Yep!
Create a new model class that has the same properties as your form, set the
`data_class` to that class, submit the form, get *back* that model object from the
form, and use it inside your controller to do whatever you want!

Oh, and if this isn't *quite* making sense: no worries - we'll practice this later.

## Form Theme: Making your Form Beautiful

Before we keep going, let's take 30 seconds to make our ugly form... beautiful!
So far, we're not controlling the markup that's rendered in *any* way: we call a
few form rendering functions and... somehow... we get a form!

Behind the scenes, *all* of this markup comes from a set of special Twig templates
called "form themes". And yea, we *can* and totally *will* mess with these. If
you're using Bootstrap CSS or Foundation CSS, ah, you're in luck! Symfony comes
with a built-in form theme that makes your forms render *exactly* how these systems
want.

Open `config/packages/twig.yaml`. Add a new key called `form_themes` with one
element that points to a template called `bootstrap_4_layout.html.twig`.

[[[ code('3d40790fa9') ]]]

This template lives deep inside the core of Symfony. And we'll check it out later
when we talk more about form themes. Because right now... we get to celebrate!
Move over and refresh. Ha! Our form is instantly pretty! The form system is now
rendering with Bootstrap-friendly markup.

Next: let's talk about customizing the "type" of each field so we can make it look
and act exactly how we need.
