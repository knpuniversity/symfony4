# Types Options

Coming soon...

I feel there's a `text` field, a `textarea`, which is interesting because in our forum
class, all we've done is just create the field names. We haven't actually specify the
type, but we now know because we've found our form class to our entity form system is
smart enough to notice that the content field is a big text field and so it uses form
field guessing to default us to a textarea, but obviously form field guessing as
nice as it is is only going to get us so far. It turns out that the `add()` method has to
other arguments the form type in some options to configure that form type for `title`
pass, `TextType::class`. The one from `Form\Extensions`. If you refresh now, you would
see absolutely no changes because that's what it was being guests already for this
field type, but now google for Symfony forms and click into the Symfony form
documentation and find a section called built in fuel types. Yah. It turns out that
there are a ton of built informed types. There's something for every HTML5, a
field type available, and if as well as a few other special ones, click into `TextType`. 
In addition to knowing which type you need, every type is highly configurable.
Many of these options are global, meaning you can, for example, the label option is
available for any form field type, but some of these options are specific to the
type. We'll see that in a second

to show this off. Check out this `help` option here allows you to define a help message
for the form field. That sounds awesome. Let's go back at a third argument, which is
an array. This is where our options go and we'll pass `help` set to "Choose something
catchy". All right, let's go check out how that looks back to our site refresh and
nice little help text on the bottom. Now what are the best things about using
Symfony's form system is the debugging tools. On your web debug toolbar, you'll have
a little clipboard icon. Click that.

Yeah, we can see our entire form and the individual fields and if you click on the
`title` field, you can get all of the information about it. We're going to look at this
several more times to look at several different pieces of it, but the most important
part, but you can already see under past options you can see the `help` option that we
passed, but what's really cool is these resolved options. This is actually a list of
all of the options that go into controlling this field. Some of them actually don't
apply to. A lot of these are low level and some of them like to CSRF stuff actually
don't apply to this specific field and so in some always looking at the options on
the documentation is the best way, but this is a great way to see some low level
things. You can see label a html attributes that go on the label and also an option
called required, which doesn't add validation and just add the HTML5 required
attributes. We'll talk a little bit more about that. We'll see that later while we're
here. Let's add one other field. In our article entity, we have a `$publishedAt`
property and depending on your site you might want in our forum class, we're going to
add a new

`publishedAt` field. Now, depending on your in your APP, you might not want this
actually be a field. You might maybe don't want to have a button that says publish,
and then this date is set for you automatically depends on your app. In our APP,
we're going to allow people, the perverse creating the article to actually set this
specifically. If you go back to your form now, Whoa, it works, but it's a bunch of
dropdowns. Okay. That will work, but that is a little bit weird. So let's go back to
our list of form field types. Obviously this is working because a form field guessing
question is which field type is it using? You can probably guess, but also if you go
back to your web debug toolbar for that field and click on publish that. You can see
it as a `DateTimeType`. Nice. So let's click into the `DateTimeType` and one of the
options that it has specific. It has many options. Most of these are specific to this
type. They won't work on other types. One of them is called widget, which defines
which we look in. If you dig a little bit, you'll find

that this controls how field looks, so to pass an option to `publishAt` that we can
actually pass `null` as the second argument if you want. When we do that, it tells the
system to continue, just guessing that as a `DateTimeType` because it was right, and
then we can set `widget` to `single_text`. All right, move over, go find our form again
and nice. Now it is actually a text field. I'll right click and inspect element on
that

and you can see it as an `<input type ="datetime-local"...`. It's actually kind of cool
because you get this little calendar icon here and you'll get lots of little tools to
set the date. Now this doesn't work in every single browser works in most browsers,
but not all of them. If it doesn't work in a browser, this will fall back to just a
text type and the user will need to type in which format they need. So if you need
support for all browsers, you can actually get to keep this as a text field and
convert it into a fancy date time widget. I'm with JavaScript. That's something we
did in our last forums tutorial, or you can add a little help message down here that
shows which format to use. The really important thing I want to point out here is
that regardless of this field is now just a simple text field. So when we submit
this, it's actually going to be a text string that is sent to the server. But of
course when the server processes this, it ultimately calls

the `setPublishedAt()` method, which requires a `DateTime`, objects. So there are two
super powers of form field types. The `DateTimeType`, it's first superpower is that it
helps it render the way that you want it to, but the real superpower of all these
built in field types is something called data transformation. It's the magic behind
the scenes that transforms a `DateTime` object into a `string` that can be displayed. And
most importantly, when you submit this `string`, it transforms it back into a `DateTime`
object. And that daytime abject is ultimately set a pass to set published ads. So
we'll talk more about data transformers lately. I just want you to think about that
is happening behind the scenes. All right, next, let's create. Let's talk about one
of the most important fields. I'm in Symfony and that is the `ChoiceType`, which
greats things like drop down fields and option fields and specifically how we can
create a dropdown. It's populated from the database.