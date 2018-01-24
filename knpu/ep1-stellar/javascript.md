# JavaScript & Page-Specific Assets

The topic of API's is... ah ... a *huge* topic and *hugely* important these days.
We're going to dive deep into API's in a future tutorial. But... I think we at *least*
need to get to the basics right now.

So here's the goal: see this heart icon? I want the user to be able to click it
to "like" the article. We're going to write some JavaScript that sends an AJAX
request to an API endpoint. That endpoint will *return* the *new* number of likes,
and we'll update the page. Well, the number of "likes" is just a fake number for
now, but we can still get this entire system setup and working.

## Creating the new JavaScript File

Oh, and by the way, if you look at the bottom of `base.html.twig`, our page *does*
have jQuery, so we can use that:

[[[ code('0f67bed719') ]]]

In the `public/` directory, create a new `js/` directory and a file inside called,
how about, `article_show.js`. The idea is that we'll include this *only* on
the article show page.

Start with a jQuery `$(document).ready()` block:

[[[ code('9a89d847ed') ]]]

Now, open `show.html.twig` and, scroll down a little. Ah! Here is the hardcoded
number and heart link:

[[[ code('25f0a86b61') ]]]

Yep, we'll start the AJAX request when this link is clicked and update the "5" with
the new number.

To set this up, let's make few changes. On the link, add a new class `js-like-article`.
And to target the 5, add a span around it with `js-like-article-count`:

[[[ code('66074f8c17') ]]]

We can use those to find the elements in JavaScript. 

Copy the link's class. Let's write some very straightforward... but still awesome...
JavaScript: find that element and, on click, call this function. Start with the
classic `e.preventDefault()` so that the browser doesn't follow the link:

[[[ code('3c57856ea0') ]]]

Next, set a `$link` variable to `$(e.currentTarget)`:

[[[ code('47d551cc3a') ]]]

This is the link that was just clicked. I want to toggle that heart icon between
being empty and full: do that with `$link.toggleClass('fa-heart-o').toggleClass('fa-heart')`:

[[[ code('ca28181c58') ]]] 

To update the count value, go copy the other class: `js-like-article-count`. Find
it and set its HTML, for now, to `TEST`:

[[[ code('3843aac1f8') ]]]

## Including Page-Specific JavaScript

Simple enough! All we need to do *now* is include this JS file on our page. Of course,
in `base.html.twig`, we *could* add the script tag right at the bottom with the
others:

[[[ code('2aa8f813aa') ]]]

But... we don't really want to include this JavaScript file on *every* page, we only
need it on the article *show* page.

But how can we do that? If we add it to the `body` block, then on the final page,
it will appear too early - *before* even jQuery is included!

To add our new file at the bottom, we can *override* the `javascripts` block.
Anywhere in `show.html.twig`, add `{% block javascripts %}` and `{% endblock %}`:

[[[ code('d937677f48') ]]]

Add the script tag with `src=""`, start typing `article_show`, and auto-complete!

[[[ code('e4b338c175') ]]]

There *is* still a problem with this... and you might already see it. Refresh the
page. Click and... it doesn't work!

Check out the console. Woh!

> $ is not defined

That's not good! Check out the HTML source and scroll down towards the bottom.
Yep, there is literally only *one* script tag on the page. That makes sense! When
you override a block, you *completely* override that block! All the script tags
from `base.html.twig` are gone!

Whoops! What we *really* want to do is *append* to the block, not *replace* it.
How can we do that? Say `{{ parent() }}`:

[[[ code('7e5179e625') ]]]

This will print the *parent* template's block content first, and *then* we add
our stuff. *This* is why we put CSS in a `stylesheets` block and JavaScript in
a `javascripts` block.

Try it now! Refresh! And... it works! Next, let's create our API endpoint and hook
this all together.
