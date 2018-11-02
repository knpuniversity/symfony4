# Dynamic Select Js

Coming soon...

We finished the heavy lifting here. Our form is now really smart. Thanks to these
event listeners, no matter what data we start with or what data we submit for the
`location` field, the `specificLocationName` field is changed correctly so that we're
able to save, um, the values from that drop down. So the only thing we need to do now
is add some fancy JavaScript. Let me change this location dropped down to a star. We
need to have an Ajax call that goes and changes this to these stars for changes to
the planets. Then it changes it to planets. So in an `ArticleAdminController`, I'm
going to add a new end point for this. I'm gonna call a 
`public function getSpecificLocationSelect()`. And I'm going to type in Symfonys 
`Request` object. The idea is this is going to take, we're going to send the location 
that was just selected to this end point and it is going to return the new html needed 
for the specific location name. So above this I'll put the normal `@Route()`, we'll 
call it `/admin/articles/location-select`. And I'll give it a 
`name="admin_article_location_select"`

inside the logic is fairly simple. We're gonna. Create a new `Article`, object 
`$articles = new Article()`. They were going to set the location onto that. So the way 
I'm going to do this is when we make the AJAXrequest, we're going to add a query 
parameter question mark. Location equals. So here I can say `$article->setLocation()`, 
and then `$request->query->get('location')`

we're doing here is we're not going to save this `Article` object, we're just creating
this `Article` objects so that we can create the form for that. So 
`$form = $this->createForm(ArticleFormType::class, $article)`. We know that
internally thanks to our event listeners, specifically our `PRE_SET_DATA` event
listener. This form will now be set up correctly, the specific name part based on
wherever the location is that was sent up. So now we're just gonna return were
ultimately to return. Here is just that one field. But first I'm gonna check to see
if there is no field, there's no refield, just return an empty response. What I
literally mean is, `if (!$form->has('specificLocationName')` and `return new Response()`
The one from `HttpFoundation` with no content and we're returning at two. Oh,
four status code to have for just means it was successful, but we have no content
descend back and remember we might not have a `specificLocationName` because if
there's no location set or if the location is interstellar space,

homework, because they need to have like a. there's a switch that's broken on the
furnace and then he said that the theater thing, Murphy's select interstellar space.
There's no options either. If we do have that form, if we do have that field, we want
to render it, so we're actually going to return a. we're going to render a template.
It's gonna be a bit of a strange template or the `article_admin/`. We're going to call
this one `_specific_location_name.html.twig`. Then pass in the form like
normal `'articleForm' => $form->createView()`. All right, then I'll put my
cursor on that name or do a alt enter to create that twig template. But this is not
going to be a full page. We're literally going to say 
`{{ form_row(articleForm.specificLocationName) }}`
and that's it. This end endpoint is just going to
return this specific location name and we should already be able to try this. If we
kind of copied this, you were out. I'll open a new tab, 
`localhost:8000/admin/article/location-select?location=star`

and Yep, looks good. We get the star dropdown for your solar system. We get the solar
system dropdown. Excellent.

All right, so next, in order to power our JavaScript, go to `_form.html.twig`, our
JavaScript is going to, is going to need to know a few things. It's going to need to
be able to find the location field because it's going to need to know when that
changes and it's also gonna need to know where it is going to need to know where this
`specificLocationName` field is so that it can change it dynamically. So to the
`location` field, I'm going to pass a variable thing and we're actually going to set
some special attributes on this specifically. I'm going to set one call 
`data-specific-location-url` set to `path('admin_article_location')`. Select one setting a
data attributes that we can read that in JavaScript instead of hard coding the Url.
Then we'll just going to set a class on this called `js-article-form-location`. That's
going to be something that we'll use to find this field. So we're going to attach the
JavaScript. The second thing I'm gonna do is I'm going to surround my specific
location name feel with a new `<div>` with it, with aj `js-specific-location-target`
Dev, and then I'll just indent that

and now we're ready for the JavaScript. Open up your `public/` directory and remember in
this application we're not using Webpack encore yet, so we just have really simple
JavaScript. Bring a new JavaScript file called `admin_article_form.js`, and then I'm
actually going to paste in some JavaScript that I already prepared. You can download
this JavaScript, you can get this JavaScript at the Vba code block on this page. Now
before we talk about this, we need, let's go ahead and include it from our two
templates. So we can't include JavaScript directly from `_form.html.twig`
because that's an included template. So we'll go into our edit that age. My tweak,
I'm override `{% block javascripts %}`, call the `{{ parent() }}` function and then want a `<script>` tag
`src="{{ asset('js/admin_article_form.js') }}`. I'll copy that and I'll do, I'll go to the new
template. We already over the, a JavaScript block there. So what does copy and that
JavaScript? Alright, so this is unless we mess something up, this should work, but
let's go and actually look at this `admin_article_form.js` because you have the entire
flow is susie. We're just using jquery and here we have a document not ready. The
first thing I do is I select the two fields. I slept the location select,

which is going to be the actual select element, uh, for the location. And then I also
select these specific location target that is the div that is around our specific
location. Name field.

Yeah.

So then locations like on change, what we do is we make an Ajax call and for the you
were out. We read that data attribute that reset the data specific location, you well

off of our locations. So that's why we. That data attribute was a nice way to get
that for the data. I set location to location, select that vowel, so whatever the
value is of the selected location, that will pass that as a query parameter. Then on
success, we're going to get back the html of the new form field, but if we don't get
back html, that means that we've selected an option that should not have another
dropdown. So we do is we actually look inside of these specific location target.
That's the live around there and we try to find a select element. If there is one and
we removed that, that's to make sure that that doesn't get submitted. Then on the
specific location target itself, we add a bootstrap class called d that's for
display. None. That's going to make sure that the entire, the entire element is
hidden, including the label, not just the select element, but if there is some hd
mouth, there is a field. Then we're going to find that specific location target.
We're going to put the entire row into that html and we're gonna. Make sure that our
display non-class is removed so you can see it's sort of straightforward, but there
are a lot of moving pieces. So let's check this out. I'm going to refresh the edit
page.

Currently we are on the star, but on my console to mix, there's no airs. Change this
to the solar system and yes we got it and if we change this to interstellar space,
nice. It is gone. And specifically what'd you can see here is that that form group is
still there with this specific location name, but it's just the label. Because of the
display, none. It's been replaced some changes back to the solar system now. Yep. R,
d nine is gone and now we have a forum group with the actual select inside of it. So
let's just make sure this actually works. Let's go to the solar system. Let's change
this to earth. It update and yeah, we've got it. Let's go back to the star in. That
saves just fine. That's the end result. Works really well, but it is a bit
complicated setup. Now there are a few small details we need to clean up before are
fully done and we'll go ahead and clean those up next.