# Form Type Class

Hey friends! And welcome to a *big* important tutorial: the one about forms!

Now, the *first* question you might ask is: forms? Do we even need forms anymore
in this age of JavaScript frontends? The answer is... it depends. I have the pleasure
of talking to a lot of developers and, honestly, it comes down to what you're
building. While many apps *are* using modern JavaScript frontends, just as many
are building form-rich interfaces. So, if that's you - welcome!

There is *no* more powerful form system on the planet than Symfony's form component.
Oh, and there are *so* many pieces to a form: rendering the form, handling the submit,
validating & normalizing that data, and other things that you don't even *think* about,
like CSRF protection. Here's the truth about Symfony's Form component: yes, it *is*
crazy powerful. And when you learn to harness to harness that power, you will be
incredibly productive. At the same time, in certain situations, the form system can
be *really* hard & complex.

So here is out goal: to learn how to do almost *everything* I can think with a form
*and* to identify those complex scenarios, and find the simplest path through them.
After all, *even* if you use and love the form system, it doesn't mean that you *have*
to use it in *every* single situation.

## Project Setup

As always, if you're *serious* about becoming the master of your forms and inputs,
you should totally code along with me. Download the course code from this page.
When you unzip it, you'll find a `start/` directory inside with the same files
that you see here. Open up that `README.md` file for instructions on how to get
the site set up. The last step will be to find a terminal, move into the project,
sip some coffee, and run:

```terminal
php bin/console server:run
```

to start the built-in web server. Woo! Now, find your browser and head to
`http://localhost:8000`. Welcome to our work-in-progress masterpiece: The Space Bar!
Our intergalactic new site where aliens *everywhere* can quickly catch up on only
the *most* important news after a 500 year nap in cryosleep.

Thanks to our last tutorial, we can now log in with `admin2@thespacebar.com`,
password `engage`. Then head over to `/admin/article/new` to see.... oh! A big TODO!

Yep! We can display articles but... we can't actually create or edit them yet. The
code behind this lives in `src/Controller/ArticleAdminController.php` and, sure
enough, *past* us got lazy and just left a TODO.

## Creating a Form Class

Time to get to work! The first step to building a form is always to create a form
*class*. Inside `src`, add a new `Form/` directory... though, like normal, the
location doesn't matter. And inside, a PPH class called `ArticleFormType`. Form
classes are usually called form "types", and the only rule is that they must extend
a class called `AbstractType`. Oh! But of course! I can't find that class because
*we* haven't installed the form system yet! No problem!

Move back to your terminal, open a new tab, have another sip of coffee, and run:

```terminal
composer require form
```

Perfect! Back in our editor, once PhpStorm finishes indexing, we *should* be able
to find the `AbstractType` class from the `Form` component.

Perfect! Now, go to the Code -> generate menu, or Cmd + N on a Mac and click override
methods. There are several methods that you can override to control different parts
of your form. But, by *far*, the most important is `buildForm()`. Inside this method...
our job is pretty simple: use this `$builder` object to, um... build the form! Use
`$builder->add()` to add two fields right now: `title` and `content`. These are the
two most important fields inside of the `Article` entity class.

And... that's it! We'll do more work here later, but this is enough.

## Creating the Form Object

Next, find your controller so we can render it. Start by saying `$form =` and using
a shortcut `createForm()`. Pass this the *class* that you want to create:
`ArticleFormType::class`. I'll delete the `return` response stuff and, instead,
render a template with `return this->render('article_admin/new.html.twig')`. To
render the form, we need to pass that in. Let's call the variable `articleForm`
and set it to - this is tricky - `$form->createView()`. Yep: don't pass the
`$form` object directly to Twig: always call `createView()`. This transforms the
Form object into another object that is *super* good at rendering forms.

## Rendering the Form

To create the template, I'll cheat! Ha! Thanks to the Symfony plugin, I can put my
cursor on the template name, hit `alt+enter`, click "Create Twig Template" and
hit enter again to confirm the location. But, there's no real magic here: that
just creates the file for us at `templates/article_admin/new.html.twig`. 

Oh and you *might* remember from previous tutorials that, in addition to the normal
`base.html.twig`, we also have a `content_base.html.twig`, which gives us a *little*
bit of real markup and a `content_body` block that we can override. Let's use that:
`{% extends 'content_base.html.twig %}` and then, override the block `content_body`,
with `{% endblock %}`. Add an `<h1>Launch a new Article</h1>` with, of course, a
rocket emoji! 

To render the form, we get to use a few special form *rendering* functions:
`{{ form_start() }}` and pass that the `articleForm` variable. At the end
`{{ form_end(articleForm }}`. And in the middle, `{{ form_widget(articleForm) }}`.
Oh, and for the submit button, you *can* build this into your form class, but
I prefer to add it manually: `<button type="submit">`, some classes:
`btn btn-primary`, and then `Create`!

And... that's it! We create a form class, create a Form object from that in the
controller, pass the form to Twig, then render it. We'll learn a *lot* more about
these rendering functions. But, more or less, `form_start()` renders the opening
form tag, `form_end()` renders the form *closing* tag... plus a little extra magic
we'll talk about later, and `form_widget()` renders *all* of your fields.

Try it! Find your browser and refresh! Woohoo! Just like that, we have a functional
form. Sure, it's a bit ugly - but that will be *super* easy to fix. Before we get
there, however, we need to talk about handling the form submit.
