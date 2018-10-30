# Form Type Class

Hey friends! And welcome to, what *I* think will be, a *super* fun tutorial:
the one about cookies! Um, forms!

The *first* question you *might* ask is: forms? Do we even *need* forms anymore
in this age of JavaScript frontends? Aren't forms *so* 2016? The answer is... it
depends. I get to talk to a lot of developers and, honestly, it comes down to
what you're building. Yea, some apps *are* using modern JavaScript frontends. But
just as many are building form-rich interfaces. So, if that's you - hi! o/.

There is *no* more powerful form system on the planet than Symfony's Form component.
Oh, and there are *so* many pieces to a form: rendering the form, handling the submit,
validating data, normalizing data, and other things that you don't even *think* about,
like CSRF protection. Here's the truth about Symfony's Form component: yes, it *is*
crazy powerful. And when you learn to harness that power, you will be incredibly
productive. At the same time, in some situations, the form system can be *really*
hard & complex. It can make your job *harder* than if you didn't use it at all!

So here is our big goal: to learn how to do almost *everything* you can think of
with a form *and* to identify those complex scenarios, and find the simplest path
through them. After all, *even* if you use and love the form system, it doesn't
mean that you *have* to use it in *every* single situation.

## Project Setup

As always, to become the *master* of form tags, inputs & textareas,  you should
totally code along with me. Download the course code from this page. When you unzip
it, you'll find a `start/` directory inside with the same files that you see here.
Open up the `README.md` file for instructions on how to get the site set up. The
last step will be to find a terminal, move into the project, sip some coffee, and
run:

```terminal
php bin/console server:run
```

to start the built-in web server. Woo! Now, find your browser and head to
`http://localhost:8000`. Welcome to our work-in-progress masterpiece: The Space Bar!
Our intergalactic news site where aliens *everywhere* can quickly catch up on only
the *most* important news... after a 500 year nap in cryosleep.

Thanks to our last tutorial, we can even log in! Use `admin2@thespacebar.com`,
password `engage`. Then head over to `/admin/article/new` to see.... oh! A big TODO!

Yep! We can display articles but... we can't actually create or edit them yet. The
code behind this lives in `src/Controller/ArticleAdminController.php` and, sure
enough, *past* us got lazy and just left a TODO.

## Creating a Form Class

Time to get to work! The first step to building a form is always to create a form
*class*. Inside `src`, add a new `Form/` directory... though, like normal, you can
put this stuff *wherever* you want. Inside, a new PHP class called `ArticleFormType`.
Form classes are usually called form "types", and the only rule is that they must
extend a class called `AbstractType`. Oh! But of course! I can't find that class
because... *we* haven't installed the form system yet! No problem!

Find your terminal, open a new tab, have *another* well-deserved sip of coffee, and
run:

```terminal
composer require form
```

Perfect! Back in our editor, once PhpStorm finishes indexing, we *should* be able
to find the `AbstractType` class from the `Form` component.

Got it! Now, go to the Code -> generate menu, or Cmd+N on a Mac, and click override
methods. There are several methods that you can override to control different parts
of your form. But, by *far*, the most important is `buildForm()`.

[[[ code('33698f9a63') ]]]

Inside this method, our job is pretty simple: use this `$builder` object to, um... build the form!
Use `$builder->add()` to add two fields right now: `title` and `content`. These are the
two most important fields inside the `Article` entity class.

[[[ code('9ecdd3f8b5') ]]]

And... that's it! We'll do more work here later, but this is enough.

## Creating the Form Object

Next, find your controller so we can render the form. Start by saying `$form =` and
using a shortcut: `$this->createForm()`. Pass the *class* that you want to create:
`ArticleFormType::class`. I'll delete the `return` response stuff and, instead,
render a template with `return $this->render('article_admin/new.html.twig')`. To
render the form, we need to pass that in. Let's call the variable `articleForm`
and set it to - this is tricky - `$form->createView()`. Yep: don't pass the
`$form` object directly to Twig: always call `createView()`. This transforms the
Form object into another object that is *super* good at rendering forms and telling
funny stories at parties.

[[[ code('f41dc0b97a') ]]]

## Rendering the Form

To create the template, I'll cheat! Ha! Thanks to the Symfony plugin, I can put my
cursor on the template name, hit `alt+enter`, click "Create Twig Template" and
hit enter again to confirm the location. There's no real magic here: that
just created the file for us at `templates/article_admin/new.html.twig`. 

Oh, and you *might* remember from previous tutorials that, in addition to the normal
`base.html.twig`, we also have a `content_base.html.twig`, which gives us a *little*
bit of real markup and a `content_body` block that we can override. Let's use that:
`{% extends 'content_base.html.twig %}` and then, override the block `content_body`,
with `{% endblock %}`. Add an `<h1>Launch a new Article</h1>` with, of course, a
rocket emoji! Zoom!

[[[ code('b890a3a4b9') ]]]

To render the form, we get to use a few special form *rendering* functions:
`{{ form_start() }}` and pass that the `articleForm` variable. At the end
`{{ form_end(articleForm }}`. And in the middle, `{{ form_widget(articleForm) }}`.
Oh, and for the submit button, you *can* build this into your form class, but
I prefer to add it manually: `<button type="submit">`, some classes:
`btn btn-primary`, and then `Create`!

[[[ code('a875f5499b') ]]]

And... we're done! We create a form class, create a Form object from that in the
controller, pass the form to Twig, then render it. We'll learn a *lot* more about
these rendering functions. But, more or less, `form_start()` renders the opening
form tag, `form_end()` renders the form *closing* tag... plus a little extra magic,
and `form_widget()` renders *all* of the fields.

Try it! Find your browser and refresh! Woohoo! Just like that, we have a functional
form. Sure, it's a bit ugly - but that will be *super* easy to fix. Before we get
there, however, we need to talk about handling the form submit.
