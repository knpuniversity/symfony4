# Form Type Class

Coming soon...

Hagen people, friends will come to our forms tutorial.

Now you. The first question might ask is forms, do we even need forms on the Internet
anymore? The answer is it depends. Honestly, a lot of apps have moved to a more
single page application, heavy JavaScript format, but as many people have moved
there, there are still just as number of people that are building very form heavy
sites and there is no more powerful form system on the planet than Symfony's form
system because there are so many parts to a form. There is a rendering that form
processing it's data validating in normalizing that data in other things you don't
even think about like `CSRF` protection. So here's the deal with Symfony's `Form`
component. It is incredibly powerful, so if you learn how to harness it, your going
to be very productive at the same time. When it gets to a certain, a few complex
topics, simply form system can be very hard. So we're going to learn how to do almost
everything I can think of what the form and I'm going to tell you when and why to
steer away from the `Form` intake. Simpler paths. All right? As always, if you want to
become a form master, you must code along with me. Download the course code from this
page. When you unzip it, you'll find a start directory inside with these same files
that you see here. Open up that `Readme.md` file for instructions on how to get the
site set up. The last step will be to

find a terminal. Move it to the project and run `php bin/console server:run` to start the
built in web server. Awesome. Move Back, head to `http://localhost:8000`. And welcome
to our work in progress masterpiece. The space bar are real news intergalactic site.
We're aliens everywhere. Can come and find out the latest news on the web. Thanks to
our last tutorial, we can now log in with `admin2@thespacebar.com`.
Password `engage`. Alright, so here's the deal. If you go to `/admin/article/new`, we
get a

big todo. You say we can display articles on our site, but we can't actually create
or edit them yet. The code behind this as in source controller, article admin
controller, and sure enough there is our big beautiful todo

so it's time to get to work on this. The first step to creating a form is too great a
`Form` class, so one of our source directory. I'll create a `Form` directory though, like
normal, the location doesn't matter. And inside a php class called `ArticleFormType`
form. Classes are usually called form types and the only rule is that they extend a
class called `AbstractType`. Oh, but of course they'll see we don't have that yet
because we don't have the `Form` system installed yet. So move over. Open a new
terminal tab and run 

```terminal
composer require form
```

Perfect. Now I want to move back. Once
php storm is indexed, we can find our `AbstractType` class perfect the way you use it.
Uh, next, go to the code, generate menu or command in a Mac, click over on methods.
And the one method you're always going to override his `buildForm` will mention a few
of these other methods as well though they're are much less important and it's
simple. Build your form, but using this `$builder` object and the `add()` method. So let's
add two fields right now, `title` and `content` which are mirroring two fields that we
have inside of our actual

`Article` class. And yeah, that's it. That's all we need to do for the form right now.
Once you've created the form, you'll use it inside of your controller class by saying
`$form =` and using a shortcut `createForm()`, and then you'll pass it the class that you
want to create. `ArticleFormType::class`. I'll delete the `return` the response stuff
instead, or `return this->render()` to render a temp twig template. How about
`article_admin/new.html.twig`, and we need to pass the form into twig template.
Let's call the variable `articleForm` and then set it to. And this is a. This is
tricky `$form->createView()`. You don't just pass the `$form`, object directly into
it, you pass it, `createView()`. By the way, the form object is this object that knows
everything about your form, including the fields and how to run them.

To create the template. I'm going to cheat because I have the Symfony plugin
installed. I'll put my cursor into the template and hit `alt+enter` and click create
twig template and then hit enter again to confirm the location, but there's no magic
going on. You're going to see that this is `templates/article_admin/new.html.twig`. 
You might remember from previous tutorials, we have a `base.html.twig`,
but we also have a `content_base.html.twig`, which gives us a little bit of markup
and a `content_body` block. Let's use that, so we'll say `{% extends 'content_base.html.twig %}`
have to wait and then will override the `content_body` end block. Put an 
`<h1>Launch a new Article</h1>`

with of course our rocket emerging, and then to run to the form we're going to use a
few special form rendering functions, `{{ form_start() }}`, and we'll pass
that `article_form` at the end `{{ form_end(articleForm }}` that literally creates the form,
start tagging any form end tag, which might seem silly, but there's actually a little
bit of extra magic happening inside of there, which we'll talk about later to run to
the actual fields. We can render all of them at once with `{{ form_widget(articleForm) }}`,
and then we need a submit button, so I'll say `<button type="submit">`, and then we'll
use our boot started classes at `btn btn-primary`, and we'll say `Create`, and that
is it form class created in our controller. Render it inside of our template. Now
when we move over and refresh, just like that, we have a functional form. Next, let's
talk about processing that form submit, and then we're going to make it a whole lot
fancier.