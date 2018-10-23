# Form Theme Basics

Coming soon...

The only real problem with their registration form now is it looks terrible. It does
not look like our original registration form did, which was styled really nicely.
This is one of the trickiest things about the `Form` system is a styling it, uh, or
theming it. The system is really powerful, but you need to be able to walk through it
carefully. So let's do it step by step. Basically, we want to convert our new form
into the market that we had before. So the first thing we noticed is that the form
class itself had a class called form Simon watching Google for Symfony form field, a
form

function reference. This is a page that we saw before we'd actually shows us all of
the different functions that we can call to render our form like form start form end.
And this is important because it's going to take, it's going to give us a clue as to
how to customize each part of this. So for example, the second, the first argument to
form view `form_start()` is called `view`. When you see the word view, what you're
referring to is actually your `Form` object. It's called a form view. It's not very
important. The second argument is `variables` in almost every function on here has an
argument called variables. We talked a little about this earlier. These are the
pieces of. This is an array of information that you can pass in in order to modify
how that piece of the form renders. For example, there is a variable called `method`
that you can set to control the HD method attribute on the form. Well we want to do
is actually set a custom attribute on the form

which

which yeah, we have this method, a variable here which ultimately becomes an
attribute, but that doesn't work. Now, that doesn't work for most attributes to know
what you can pass into this variable.

You can go all the way down to the bottom of this page and this is not an exact
guide, but it's going to give you lots of information about the different values that
you can pass into here. The one of the most important ones is `attr`. That's something
you can pass to set custom html attributes, so it's a long way of saying that we can
pass the second argument here with `attr` set to itself, another array with `class` set
to `form-signin`, and while I'm here, one of the things we lost was our `<h1>`,
which is simple. We'll just pop that right on the beginning of the form. All right,
so move over, go back, refresh and Ugh. Already so much better. I can once again see
my agree to terms box. All right, so let's keep going about this to understand how to
you notice that the original fields, they were just a label and an input. The input
has a special class on it, but really there's nothing really special about the markup
at all by default in the bootstrap form theme, everything has rendered instead of a
`form-group` and then there's the `<label>` and the `<input>`. So we actually want to change
how all of this market has rendered. In an order to do that, we need to understand a
little bit about how Symfony renders the markup in the first place.

Now you may remember from earlier that in our `config/packages/twig.yaml` file, we
added this form theme line which pointed to a core template called `bootstrap_4_layout.html.twig` 
Whenever Symfony renders any part of your form, there was
actually a twig template in core which contains the markup for that piece of your
form, like the label or the widget, and we can override that hit shift shift to open
bootstrap for layout. That eight small twig, so this one of the strangest twig
templates you're ever going to see. It's actually just a bunch of blocks, block time
widget, the widget percent widget file widget and many, many more. Here's how it
works. There are basically five different parts for each field. There is the `row` and
actually the roe contains the other four parts which are the `widget` `label`, `errors` and
`help` based on your field type. Symfony goes specific opens this and which part of the
form rendering Symfony actually opens this template and find the exact part to render
that part of the field. For example, if you're rendering a, let's see a. Our `email`
here is a for the `email` field. Oh, I actually forgot. We can make this an 
`EmailType::class` that actually makes it render as `<input type="email">`,

which gives you a little bit more html5 validation. Well, when we rented the
`password` field, that's a `PasswordType`. Wonder rendering the widget part of that
Symfony. It looks inside of this for password. When Symfony of running an email, the
widget part of the email, it looks for a, a blocking here called `email_widget`.

Okay.

If it doesn't find it in here, it actually looks at another template

that's right next to this, so I'm going to click up here and open another one called
`form_div_layout.html.twig`. This is actually Symfonys default form theme, and even if
you don't have it in your toy.yaml file, this is always present, so simply look
actually looks for all the blocks and bootstrap for layout and it also looks for the
blocks and form div layout. The bootstrap one overrides this, so if you search here
for `email_widget`, boom, this is the block that's responsible for rendering the widget
part of the `email` field and you can also look for `password_widget` and that does the
same thing,

but actually there's also a bit of hierarchy behind this because check this out. What
about the so what part? What is the name of the block when you're rendering the label
part of the password? Well, you might think it's called Password_label and actually
you would be right, but because the label looks the same for almost every field type,
simply it has a little bit of a fall back in mechanism. Go back to your browser,
click on the form icon on the web debug toolbar. Let's actually click on the plain
password field and then down here, open up the view variables and you'll notice
something called a block prefixes. This is actually when Symfony is trying to render
the widget for this particular field. It actually first looks for 
`_user_registration_form_plainPassword_widget`.

That actually allows you to modify how the widget looks on a field by field basis.
We'll actually talk about that a little bit. If it doesn't find that block than it
actually looks for a `password_widget` if it doesn't find that than a `text_widget`. And
then finally a `form_widget`, which would be a catchall. So this case there is a
password_widget, but there's not a `password_label`, so it then looks for `text_label`.
Let's look for that `text_label`. Nope. In finally it looks for `form_label`, so
`form_label`, and this is actually what's used when we render the label for almost
every field on the system.

So to bring this all back inside `register.html.twig`, when we call 
`form_widget(registrationForm)`, that's just a shortcut for calling `form_row()` on 
all of the fields in our forum, which means we're rendering the row type the rope 
part of all of our fields and not surprisingly, the row looks exactly the same for 
all visits and field types. In other words, if you've got a `bootstrap_4_layout.html.twig`
search for a `form_row()`, and eventually you'll find a  block called `form_row`. 
This is the block that's responsible for rendering the former school route and you can 
see it just calls the `form_label()` the `form_widget()` and and the `form_help()`.

The reason you don't see the errors here is that the errors are actually rendered
inside of the form label instead of bootstrap. So what we want to do is override the
`form_row()` the way the form row renders just in this one template so that we can make
it render simpler with Jay and not with a surrounding div around everything. So
here's how we're going to do that. We're going to create a form theme, our very own
form theme. Now you create form themes as a separate template and then you can
actually use a form theme globally on every form if you want to, by adding that form
theme into your `twig.yaml`, or you can form theme file by file. To do that. This is
really nice. You can say `{% form_theme %}`, then the name of your
variable `registrationForm` and then `_self` basically says, I want to use
this template as a `form_theme` for the `registrationForm` variable.

Now as soon as you do that, when Symfony renders, it's actually going to look for
blocks inside of this template when it's rendering, so if we want to override the
`form_row()`, we'd get a copy of this block and paste it into our template and actually I
am going to do that, but instead of copying the bootstrap block, which actually is a
little bit fancier than I even need, it, has this form group, I'm actually going to
go and find the form_score, row blocking the parent template. Am I take a little bit
of experimenting to see if you want the bootstrap one or if it's giving you too many
features and then we'll paste that here and just to see if this is working. Let's
remove the wrapping `<div>` and just render all four parts of the form by themselves.
All right, so go back refresh and oh, I saw something. Move it. Inspect element on it
and yes it is using it. You can see the label in the input are just rendered out for
every field. When Symfony looks for the `form_row()` block. If first looks in our
template and we are overriding it, alright, we need to do more work and we need to
know, learn a lot more about how to code inside of these `form_theme` blocks. So let's
do that next.