# Form Options Data

Coming soon...

Alright, new goal guys, remember this author field down here? It says all the nice
auto complete stuff filled in for it. Well, I want to use that field on the new form,
but on the edit form I want to completely disable it so it prints, but it's a
completely disabled field. This is the first time where we want the same form to
behave in a different way from a high level, the way you can make your form behave in
one, behaving a different way. So one way to think about this is if we are

for our new end point that we're creating a new `Article` object behind the scenes, but
if when you're in the `edit()` and point, we're actually modifying an `Article` object. We
pass the article object to the form. So if in our form type in our bill for method,
if we could get access to the data that was passed to us, either the `Article` object
or maybe nothing that we could actually make different decisions inside of bill form
based on that data. The way to do this is by leveraging the `$options` that are passing
this method and we haven't talked about these yet, I'm just going to `dd($options);` and
then go back and refresh the edit page. Nice. So there's actually a ton of options
that are passed into this method and these are actually things that we can configure
on everything you can see here. We can actually configure it down and you `configureOptions()`,
but the vast majority of the stuff are not things that you're going to need
to think about, but there is one key called it `data` which is set to the `Article`
object that we passed in. I'm going to open another tab and go to the `/new` end point.

Notice here there is no `data` which makes sense because we never actually passed
anything in when we instantiated this, but now we know that there's a `data` key that
we can use to get our underlying data, so try this now. Let's say 
`$article = $options['data'] ?? null;`

Okay.

Question question. No, that's a super fancy syntax. Basically to say I want the
article variable to be equal to options data, it actually exists otherwise I want
article to be equal to not and then we will add that article, make sure that gives us
what we want, so refreshing the new page `null` Click on refresh the edit page,
`Article` object. Now we are dangerous. Wait that `dd()` and now create a new variable
called `$isEdit = $article && $article->getId()`. Now you might think it's
enough just to check to see if `$article` is normal or if it's an object, but actually
when you have a. In our new end point, if we had wanted to, we could've actually
instantiate a `new Article()` object and passed it as a second argument to `createForm()`.
We don't need to, but that would be a total legal thing to do and Dr Wood's still be
smart enough to see that as a new object and it would save it. It would insert it. So
that's why I'm checking not only that the article object exists, but checking to see
if it has its id not down below. Inside of our author field, every field has a
`disabled` option and you can set this to `$isEdit`. All right, try that out. Refresh
the other page.

Disabled, refresh the new page,

not disabled. Perfect. By the way, did disabled flag. There's two things. Obviously
it adds a disabled attribute to that fields that appears disabled, but it also means
that if you have a a nasty user that actually maybe removes this and actually send
some data to the form, that data will be ignored, so it's not actually going to
process that data at all. This is a true read only field. All right, so I want to do
one more thing that at least at first seems similar, but I want to do it in a
different way. The `publishedAt` field. I want to only show that on the edit page,
so when we're creating a new form we won't be able to publish it, but then once we
create it on the edit, we will be able to have `publishedAt` field, so I'm going
to hide that field entirely on the new form, but instead of leveraging this is edit
variable, which would totally work, I want to be able to control this behavior from
my controller. Basically, when I create my form, I want to be able to say whether or
not I want to be `publishedAt` field. So for example, down the edit form, every the
create form method actually has a third argument of options. These are options you
can pass to your form. I'm going to invent a new one called `'include_published_at' => true`.

Now what do we do? This just like when we add a an option that doesn't exist to a
field, we get a huge error, says, look, your form does not have this option. You
can't just run around and making up options. Life doesn't work that way, but we can
add that option to our form. Check this out. Copy of the option name. Go into 
`ArticleFormType` in download configured. If fault `configureOptions()`, add 
`'include_published_at' => false`, so by default we don't set the. We weren't set. Include published
APP. Now up in pill form, our options will always include a include published at
field, and we'll be set to true or false. We can use that down here and say 
`if ($options['include_published_at'])` ads, then we want to run to the Polish that field, so I'll
remove it from the main builder down here. I'll say `$builder` and I will paste and then
let's clean that up a little bit. Perfect. So on the edit page we've overwritten that
option and check it out. We have the staff fuel. If you open your profile for your
form, click on the top level. You can actually see the options at the top level. You
can see that there is a past option `include_published_at` was set to `true` because
that's how we set it inside of our controller.

Now for the new page, this should not have the published outfield and in fact we get
a huge error. It's coming from twic. Neither the property published at normal. One of
the methods published up, Blah Blah Blah, has access in some form do you class and
it's blowing up inside of r_form that age to autoid because we're trying to print
that polished outfield of course, so going on to `templates/article_admin/_form.html.twig`, 
and we can just wrap this in an if statement, `{% if articleForm.publishedAt is defined %}`
and we'll print it and if now the field is gone from my new,
which means when we submit the `setPublishedAt()` method will never be called and it
will stay with his default value, which for a new `Article` is just not on the other
page. Still there and with any luck should still be working nice.