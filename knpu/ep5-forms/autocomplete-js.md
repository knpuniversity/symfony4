# Autocomplete JavaScript

From a backend perspective, the custom field is done! When the user submits a string
email address, the data transformer turns that into the proper User object, *with*
built-in validation.

But from a *frontend* perspective, it could use some help. It would be *way*
more awesome if this field had some cool JavaScript auto-completion magic where
it suggested valid emails as I typed. So... let's do it!

Google for "Algolia autocomplete". There are a lot of autocomplete libraries,
and this one is pretty nice. Click into their documentation and then to the GitHub
page for `autocomplete.js`.

Many of you might know that Symfony comes with a great a JavaScript tool called
Webpack Encore, which helps you create organized JavaScript and build it all into
compiled files. We have *not* been using Encore in this tutorial yet. So I'm going
to keep things simple and continue without it. Don't worry: the most *important*
part of what we're about to do is the same no matter what: it's how you connect
custom JavaScript to your form fields.

## Adding the autocomplete.js JavaScript

Copy the script tag for jQuery, open `templates/article_admin/edit.html.twig` and
override `{% block javascripts %}` and `{% endblock %}`. Call the `{{ parent() }}`
function to keep rendering the parent JavaScript. Then paste in that new
`<script>` tag.

[[[ code('2d1f6404e1') ]]]

Yes, we *are* also going to need to do this in the new template. We'll take care
of that in a little bit.

Now, if you scroll down a little on their docs... there it is! This page has
some CSS that helps make all of this look good. Copy that, go to the `public/css`
directory, and create a new file: `algolia-autocomplete.css`. Paste this there.

Include this file in our template as well: override `{% block stylesheets %}`
and `{% endblock %}`. This time add a `<link>` tag that points to that file:
`algolia-autocomplete.css`. Oh, and don't forget the `parent()` call - I'll add
that in a second.

[[[ code('439978f03b') ]]]

Finally, for the custom JavaScript logic, in the `js/` directory, create a new
file called  `algolia-autocomplete.js`. Before I fill *anything* in here, include
that in the template: a `<script>` tag pointing to `js/algolia-autocomplete.js`.

[[[ code('2dd6cc5a99') ]]]

## Implementing autocomplete.js

Initial setup done! Head back to their documentation to find where it talks about
how to use this with jQuery. It looks *kinda* simple: select an element, call
`.autcomplete()` on it, then... pass a ton of options that tell it how to
fetch and process the autocomplete data.

Cool! Let's do something similar! I'll start with the `document.ready()`
block from jQuery just to make sure the DOM is fully loaded. Now: here is the key
moment: how can we write JavaScript that can *connect* to our custom field? Should
we select it by the id? Something else?

I like to select with a *class*. Find all elements with, how about, some
`.js-user-autocomplete` class. Nothing has this class yet, but our field will soon.
Call `.autocomplete()` on this, pass it that same `hint: false` and then an array.
This looks a bit complex: add a JavaScript object with a `source` option set to a
`function()` that receives a `query` argument and a callback `cb` argument.

Basically, as we're typing in the text field, the library will call this function
and pass whatever we've entered into the text box so far as the `query` argument.
*Our* job is to determine which results match this "query" text and pass those back
by calling the `cb` function.

To start... let's hardcode something and see if it works! Call `cb()` and pass it
an array where each entry is an object with a `value` key... because that's how
the library wants the data to be structured by default.

[[[ code('49e179ff87') ]]]

Thanks to my imaginative code, no matter *what* we type, `foo` and `bar` *should*
be suggested.

## Adding the js- Class to the Field

And... we're almost... sorta done! In order for this to be applied to our field, all
*we* need to do is add this class to the author field. No problem! Copy the class
name and open `UserSelectTextType`. Here, we can set a *default* value for the `attr`
option to an array with `class` set to `js-user-autocomplete`.

[[[ code('d8e290f178') ]]]

## Field Options vs View Variables

Up until now, if we've wanted to add a `class` attribute, we've done it from inside
of our Twig template. For example, open `security/register.html.twig`. For the form
start tag, we're passing an `attr` variable with a `class` key. Or, for the fields,
we're adding a `placeholder` attribute.

`attr` is one of a few things that can be passed either as a view variable or *also*
as a field *option*. But, I want to be clear: options and variables are *two* different
things. Go back and open the profiler. Click on, how about, the `author` field.
We know that there is a set of options that we can pass to the field from inside
the form class. And then, when you're rendering in your template, there is a *different*
set of view variables. These are two *different* concepts. However, there *is* some
overlap, like `attr`.

Behind the scenes, when you pass the `attr` option, that simply becomes the default
value for the `attr` view variable. The `attr` option, just like the `label`
and `help` options - exists *just* for the added convenience of being able to set
these in your form class *or* in your template.

Anyways, thanks to the code in `UserSelectTextType`, our field *should* have this
class. Let's try it! Close the profiler, refresh and... ah! I killed my page! The
CSS is gone! I *always* do that! Go back to the template and add the missing
`parent()` call: I don't want to completely *replace* the CSS from our layout.

Ok, try it again. Much better. And when we type into the field... yes! We get
`foo` and `bar` no matter what we type. Awesome!

Next, hey: I like `foo` and `bar` as much as the next programmer. But we should
probably make an AJAX call to fetch a *true* list of matching email addresses.
