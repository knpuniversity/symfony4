# Registration Form

Head back over to `/register`. We built this in our security tutorial. It *does*
work... but we kind of cheated. Back in your editor, open
`src/Controller/SecurityController.php` and find the `register()` method. Yep,
it's pretty obvious: we did *not* use the form component. Instead, we manually
read and handled the POST data. The template - `templates/security/register.html.twig` -
is just a hardcoded HTML form.

Ok, first: *even* if you use and love the Form component, you do *not* need to use
it in *every* single situation. If you have a simple form and want to skip it,
sure! You can totally do that. But... our registration form is missing one key
thing that *all* forms should have: CSRF protection. When you use the Form component.
you get CSRF protection for free! And, usually, that's enough of a reason for
me to use it. But, you *can* add CSRF protection without the form system: check out
our login from for an example.

## make:form

Let's refactor our code to use the form system. Remember step 1? Create a form
class... like we did with `ArticleFormType`. That's pretty easy. But to be even
*lazier*, we can generate it! Find your terminal and run:

```terminal
php bin/console make:form
```

Call the class, `UserRegistrationFormType`. This will ask if you want this form
to be bound to a *class*. That's *usually* what we want, but it's optional. Bind
our form to the `User` class.

Nice! It created one new file. Find that and open it up!

[[[ code('c3f3ac8eaf') ]]]

## Customizing & Using UserRegistrationFormType

Cool. It set the `data_class` to `User` and even looked at the properties on
that class and pre-filled the fields! Let's see: we don't want `roles` or
`twitterUsername` for registration. And, `firstName` is something that I won't
include either - the current form has just these two fields: `email` and `password`.

[[[ code('ddc545ea7f') ]]]

Ok: step 2: go the controller and create the form! And, yes! I get to remove a
"TODO" in my code - that never happens! Use the normal `$form = this->createForm()`
and pass this `UserRegistrationFormType::class`. But don't pass a second argument:
we *want* the form to create a new `User` object.

Then, add `$form->handleRequest($request)` and, for the if, use
`$form->isSubmitted() && $form->isValid()`.

[[[ code('ecb16775ff') ]]]

Beautiful, boring, normal, code. And now that we're using the form system, instead
of creating the `User` object like chumps, say `$user = $form->getData()`. I'll
add some inline documentation so that PhpStorm knows what this variable is. Oh, and
we don't need to set the `email` directly anymore: the form will do that! And I'll
remove my `firstName` hack: we'll fix that in a minute.

[[[ code('9412a91275') ]]]

About the password: we *do* need to encode the `password`. But now, the plain text
password will be stored on `$user->getPassword()`. Hmm. That *is* a little weird:
the form system is setting the plaintext password on the `password` field. And then,
a moment later, we're encoding that and setting it *back* on that *same* property!
We're going to change this in a few minutes - but, it should work.

[[[ code('214220500b') ]]]

Down below when we render the template, pass a new `registrationForm` variable set
to `$form->createView()`.

[[[ code('4bc93cd2a7') ]]]

Awesome! Let's find that template and get to work. Remove the TODO - we're killing
it - then comment out all the old markup: I want to keep it for reference. Render
with `{{ form_start(registrationForm) }}`,  `form_end(registrationForm)` and, in
the middle, render *all* of the fields with `form_widget(registrationForm)`. Oh,
and we need a submit button. Steal that from the old code and move it here.

[[[ code('2907afba18') ]]]

Perfect! Let's go check this thing out! Refresh! Oh... wow... it looks *terrible*!
Our old form code *was* using Bootstrap... but it was *pretty* customized. We
*will* need to talk about how we can get back our good look.

## Making firstName Optional

But, other than that... it seems to render fine! Before we test it, open your `User`
entity class. We originally made the `firstName` field not `nullable`. That's
the *default* value for `nullable`. So if you *don't* see `nullable=true`, it means
that the field *is* required in the database.

Now, I *do* want to allow users to register *without* their `firstName`. No problem:
set `nullable=true`.

[[[ code('49926260c5') ]]]

Then, find your terminal and run:

```terminal
php bin/console make:migration
```

Let's go check out that new file. Yep! No surprises: it just makes the column not
required.

[[[ code('9a86fc24b2') ]]]

Move back over and run this with:

```terminal
php bin/console doctrine:migrations:migrate
```

Excellent! Let's try to register! Register as `geordi@theenterprise.org`, password,
of course, `engage`. Hit enter and... nice! We are even logged in as Geordi!

Next: we have a problem! We're temporarily storing the plaintext password on
the `password` field... which is a *big* no no! If something goes wrong, we might
*accidentally* save the user's plaintext password to the database.

To fix that, we, for the *first* time, will add a field to our form that does
*not* exist on our entity. An awesome feature called `mapped` will let us do that.
