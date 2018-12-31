# JS to Auto-Update the Select Options

Thanks to these event listeners, no matter what data we start with - or what data
we submit - for the `location` field, the `specificLocationName` field choices will
update so that everything saves.

The last step is to add some JavaScript! When the form loaded, the `location` was
set to "Near a star". When I change it to "The Solar System", we need to make an Ajax
call that will fetch the list of planets and update the option elements.

## Adding the Options Endpoint

In `ArticleAdminController`, let's add a new endpoint for this:
`public function getSpecificLocationSelect()`. Add Symfony's `Request` object as
an argument. Here's the idea: our JavaScript will send the location that was just
selected to this endpoint and *it* will return the new HTML needed for the
entire `specificLocationName` field. So, this won't be a pure API endpoint that
returns JSON. We *could* do that, but because the form is already rendering our
HTML, returning HTML simplifies things a bit.

[[[ code('8d8dbe6b51') ]]]

Above the method add the normal `@Route()` with `/admin/article/location-select`.
And give it a `name="admin_article_location_select"`.

[[[ code('2a09d44c30') ]]]

Inside, the logic is kinda cool: create a new `Article`:
`$article = new Article()`. Next, we need to set the new location *onto* that. When
we make the AJAX request, we're going to add a `?location=` query parameter. Read
that here with `$request->query->get('location')`.

[[[ code('b831904592') ]]]

But, let's back up: we're *not* creating this `Article` object so we can save it,
or anything like that. We're going to build a temporary *form* using this Article's
data, and render *part* of it as our response. Check it out:
`$form = $this->createForm(ArticleFormType::class, $article)`. We know that,
thanks to our event listeners - specifically our `PRE_SET_DATA` event listener -
this form will now have the correct `specificNameLocation` options based on whatever
location was just sent to us.

[[[ code('a7982b49db') ]]]

Or, the field may have been removed! Check for that first:
`if (!$form->has('specificLocationName')` then just `return new Response()` -
the one from `HttpFoundation` - with no content. I'll set the status code to 204,
which is a fancy way of saying that the call was successful, but we have no content
to send back.

[[[ code('1692d4194a') ]]]

If we *do* have that field, we want to render it! Return and render a new template:
`article_admin/_specific_location_name.html.twig`. Pass this the form like
normal `'articleForm' => $form->createView()`. Then, I'll put my cursor on the
template name and press alt+enter to make PhpStorm create that template for me.

[[[ code('a4e9c93405') ]]]

Inside, just say: `{{ form_row(articleForm.specificLocationName) }}` and that's
it.

[[[ code('d6f4ea0cb6') ]]]

Yep, we're literally returning *just* the form row markup for this *one* field.
It's a weird way to use a form, but it works!

Let's go try this out! Copy the new URL, open a new tab and go to
`http://localhost:8000/admin/article/location-select?location=star`

Cool! A drop-down of stars! Try `solar_system` and... that works too. Excellent!

## JS Setup: Adding data- Attributes & Classes

Next, open `_form.html.twig`. Our JavaScript will need to be able to *find* the
`location` `select` element so it can read its value *and* the `specificLocationName`
field so it can replace its contents. It also needs to know the URL to our new
endpoint.

No problem: for the `location` field, pass an `attr` array variable.
Add a `data-specific-location-url` key set to `path('admin_article_location')`.
Then, add a class set to `js-article-form-location`.

[[[ code('ab47270a79') ]]]

Next, *surround* the `specificLocationName` field with a new
`<div class="js-specific-location-target">`. I'm adding this as a new element *around*
the field instead of *on* the select element so that we can remove the field without
losing this target element.

[[[ code('f4e44d4a0a') ]]]

## Adding the JavaScript

Ok, we're ready for the JavaScript! Open up the `public/` directory and create a new
file: `admin_article_form.js`. I'm going to paste in some JavaScript that I prepped:
you can copy this from the code block on this page.

[[[ code('d23dcbc965') ]]]

Before we talk about the specifics, let's include this with the `script` tag.
Unfortunately, we can't include JavaScript directly in `_form.html.twig` because
that's an included template. So, in the edit template, override
`{% block javascripts %}`, call the `{{ parent() }}` function and then add a
`<script>` tag with `src="{{ asset('js/admin_article_form.js') }}`.

[[[ code('f39e0e9bc8') ]]]

Copy that, open the new template, and paste this at the bottom of the `javascripts` block.

[[[ code('55abfb7da4') ]]]

Before we try this, let's check out the JavaScript so we can see the entire flow.
I made the code here as simple, and unimpressive as possible - but it gets the
job done. First, we select the two elements: `$locationSelect` is the actual
`select` element and `$specificLocationTarget` represents the `div` that's around
that field. The `$` on the variables is meaningless - I'm just using it to indicate
that these are jQuery elements.

Next, when the `location` select changes, we make the AJAX call by reading the
`data-specific-location-url` attribute. The `location` key in the `data` option
will cause that to be set as a query parameter.

Finally, on success, if the response is empty, that means that we've selected an
option that should *not* have a `specificLocationName` dropdown. So, we look inside the `$specificLocationTarget` for the select and remove it to make sure it doesn't
submit with the form. On the wrapper div, we also need to add a Bootstrap class
called `d-none`: that stands for display none. That will hide the entire element,
including the label.

If there *is* some HTML returned, we do the opposite: replace the entire HTML of
the target with the new HTML and remove the class so it's not hidden. And... that's
it!

There are a *lot* of moving pieces, so let's try it! Refresh the edit page. The
current location is "star" and... so far, no errors in my console. Change the
option to "The Solar System". Yes! The options updated! Try "Interstellar Space"...
gone!

If you look deeper, the `js-specific-location-target` div *is* still there, but
it's hidden, and only has the `label` inside. Change back to "The Solar System".
Yep! The `d-none` is gone and it now has a `select` field inside.

Try saving: select "Earth" and Update! We got it! We can keep changing this all day
long - all the pieces are moving perfectly.

I'm super happy with this, but it *is* a complex setup - I totally admit that. If
you have this situation, you need to choose the best solution: if you have a big
form with 1 dependent field, what we just did is probably a good option. But if
you have a small form, or it's even more complex, it might be better to skip the
form component and code everything with JavaScript and API endpoints. The form
component is a great tool - but not the best solution for every problem.

Next: there are a *few* small details we need to clean up before we are *fully*
done with this form. Let's squash those!
