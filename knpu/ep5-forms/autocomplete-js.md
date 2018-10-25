# Autocomplete Js

Coming soon...

This custom field and data transformer is cool because this field now works exactly
how I want it to, except it would be even cooler if I had some awesome little
JavaScript autocomplete here because I really can't tell like who the actual valid
users are,

so it's a little bit difficult for me to use this, so let's do that. Google for it.
`Algolia` autocomplete. There are lots of autocomplete libraries, but `Algolia` has a
nice little JavaScript library. I'll click into their documentation, but what I
really want is their little autocomplete, that js thing. Now, many of you might know
that a Symfony comes with a really great a JavaScript library called `Webpack Encore`
to help you build a really, really a professional, beautiful JavaScript. We're not
using that in this tutorial yet. So I'm going to do things the very. I'm going to do
things. I'm going to keep things very simple. Uh, but the important part here is how
we can add JavaScript to our custom fields. So in this case, I'm going to copy the
script tag for `jQuery` and then open your `templates/article_admin/edit.html.twig` 
in override the `{% block javascripts %}` at the `{% endblock %}`, then call the `{{ parent() }}`
function so that we don't override the parent blocks. And then we'll we'll paste in
that `<script>` tag. Yes, we are also, we're also going to need to do this on the new
template. We'll take care of that in a little bit. Second, if you scroll down a
little bit of eventually there it is. There is some css that actually helps make this
all look good. Let's copy that. Then I'll open the `public/` directory. There it is, and
in css I'll create a new file called `algolia-autocomplete.css`

paste that in there, close that up, and that will override the
`{% block stylesheets %}`. `{% endblock %}`. This time we'll add a `<link>` tag so you that
`algolia-autocomplete.css` file and PhpStorm doesn't look happy. I think it's
just. There we go. It just got confused and finally to actually implement the logic
we want in the js directory, I'm also going to create a new file call 
`algolia-autocomplete.js`. Before I fill anything in here, I'll also go up here and create
a `<script>` tag for that `src="js/algolia-autocomplete.js"`. All right, so if
you go back and look at their documentation, I basically talks about how to use this
with `jQuery`. You basically need to select an element and then there's some code that
you can put here to tell it where to go and get the code from.

So I'll actually copy. No, I'm not going to copy into that. So we're going to
basically do that same thing by hand here. I'll start just to be safe with a document
that ready block from jquery to make sure the page is fully loaded. And then we're
going to do is I'm going to select all elements that have some `.js-user-autocomplete` 
class. Nothing has this yet, but our field will soon. Then we call auto
complete on it. We'll pass it that same `hint: false`. Then it's a little complex. We
need to pass it in `array` and then an `object`, and then we need a `source` option set to a
`function()` that receives a `query` which is the value in the field and then a `callback`.
So basically as we're typing in the field, the library is going to call our `source()`
function. It's going to be our job to figure out what results to pass back and the
past back. We're actually going to call execute this callback function and pass it
our array of options, which right now I'm just going to hard code. I'm to create this
little data structure with a value key because that's how the library and likes the
values to be constructed and bar

and that's it.

Now, in order for this to be a to be applied to our field, all we need to do is add
that class to our aba field, so let's just do that all the time. Automatically a copy
of this class here, that end `UserSelectTextType`. We can actually set in default
value for the `attr` our option and say `'class' => 'js-user-autocomplete` up until now if
we've wanted to add a class attribute, we've actually been doing that from inside of
our tweet template. For example, `security\registered.html.twig`

, we're passing a V, a variable called `attr`, and then we can
pass a `class` there or a variable called her with a `placeholder`. `Attr` is one of the few
things that are both can be past either as view variables or also as form options,
but these are generally two different things and I want to make sure we don't get
them confused, so if I go back and open my profiler and I click on, for example the
author field, remember that there are a number of options that you can pass to your
field and these are the things that you would pass in your form class and then when
you're rendering your template, there are a bunch of view variables. These are two
different lists. They just happen to have a little bit of overlap like a ttr, which
exists as an option and if you pass that, that becomes the default value for your,
for your view variable. Anyways, inside of our `UserSelectTextType`, we now are giving
it a default a `class`, so with any luck that should work. I'll close the profiler
refresh

and Oh, I killed my page. CSS has gone. I always do that and edit that each month to
make sure you call the `{{ parent() }}` function in your style sheet spot or else you lose all
of the other style sheets. All right? Much better. And now when we type into our
field, no matter what we type, we always get the food and the bar options because we
have this hard coded in. So the next thing we need to do is create an end point in
Ajax end point that's going to be able to return us the list of user objects so that
we're not hard coating them. To do that. Let's create a new controller for this.
Doesn't matter how you organize this, but I'm going to create an `AdminUtilityController`. 
I'll make that extend the normal `AbstractController`

and then I'm going to create a `public function getUsersApi()`, so we're gonna Credit
Api end points, and above this I'll give it the `@Route()`, the one from the routing
components, and then `/admin/utility/users`, and just to be extra fancy, I'll say
`methods="GET"`. This is only a good end point right inside of this, we're basically
just gonna return the `User` objects. We're not even going to worry about filtering
them yet, so I'll say `UserRepository $userRepository`, then 
`$users = $userRepository->findAllEmailAlphabetical()` though it doesn't matter.
And down below I'll `return $this->json()`. Oh, return this on user's key and that's
it. Let's copy that. You were on seat. This is actually working. It's all credit. New
Tab here. We'll go to `localhost:8000/admin/utility/users`. And Whoa, a circular
reference has been detected. Oh, so this means that when it was trying to see,
realize the `User` object, it's somehow got into a circular reference. Check this out,
open up your user entity class. By default, the serializer is going to see realize
every single a property you have on it or more accurately every single property that
has a guitar method.

This means that it is serializing our API tokens, for example. Well, I want to
serialize the API token. Api token has a reference back to the users, so it
serializes the user and it kind of goes on forever until it gets deep enough that
it's notices that it's in a loop and then it throws an exception. Well, the thing is
we don't really want to serialize all of those fields anyways. We just want to
serialize a couple of basic fields and we already did this earlier.

In `AccountController`. We created an API end point that return one user object and
when we did that, the key was that we told the serializer to only serialize the
groups called main look in your `User` entity class. We didn't materialize everything.
We just added an act groups and main above all the fields that we want it to
serialize. So we just need to do the same thing here instead of our AdminUtilityController. 
Second argument is the status code. Who wants 200? We don't need any
custom headers, but we do need to pass a groups option set to Maine. I know a lot of
square brackets there. Now to go back and refresh. Got It's. There is a nice usable
list. We can use a different serialization group here if we wanted to. To return even
less data are the last thing you need to do is make sure that this is secure because
right now this controller is open to the world and we might not want everybody to be
able to list all the users. So this is a little bit tricky because our 
`ArticleAdminController` requires `ROLE_ADMIN_ARTICLE`. Where did the `User` object? Ignore that.

So next, let's hook up the end points.