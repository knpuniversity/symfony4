# DateTimeType & Data "Transforming"

Let's use our new powers to add another field to the form. Our `Article` class
has a `publishedAt` *DateTime* property. Depending on your app, you might *not* want
this to be a field in your form. You might just want a "Publish" button that sets
this to today's date when you click it.

But, in *our* app, I want to allow whoever is writing the article to specifically
set the publish date. So, add `publishedAt` to the form... but *don't* set the
type.

[[[ code('956f38c0a5') ]]]

So... ah... this is interesting! How will Symfony render a "date" field? Let's
find out! Refresh! Woh... it's a bunch of dropdowns for the year, month, day and
time. That... will *technically* work... but that's not my favorite.

## Which Field Type was Guessed?

Go back to the list of field types. Obviously, this is working because the field
guessing system guessed... *some* field type. But... which one? To find out, go
back to the web debug toolbar, click to open the profiler and select `publishedAt`.
Ha! Right on top: `DateTimeType`. Nice!

Let's click into the `DateTimeType` documentation. Hmm... it has a *bunch* of options,
and *most* of these are special to *this* type. For example, you can't pass a
`with_seconds` option to a `TextType`: it makes no sense, and Symfony will yell
at you.

Anyways, *one* of the options is called `widget`. Ah! This defines how the field
is *rendered*. And if you did a little bit of digging, you would learn that we can
set this to `single_text` to get a more user-friendly field.

## Passing Options but No Type

To set an option on the `publishedAt` field, pass `null` as the second argument and
set up the array as the third. `null` just tells Symfony to continue "guessing" this
field type. Basically, I'm being lazy: we could pass `DateTimeType::class` ... but
we don't need to!

Under the options, set `widget` to `single_text`.

[[[ code('60caae260b') ]]]

Let's see what that did! Find your form, refresh and... cool! It's a text field!
Right click and "Inspect Element" on that. Double cool! It's an
`<input type="datetime-local" ...>`. That's an HTML5 input type that gives us a cool
calendar widget. Unfortunately, while this will work on *most* browsers, it will
not work on *all* browsers. If the user's browser has no idea how to handle a
`datetime-local` input, it will fall back to a normal text field.

If you need a fancy calendar widget for *all* browsers, you'll need to add some
JavaScript to do that. We did that in our Symfony 3 forms tutorial and, later,
we'll talk a bit about JavaScript and forms in Symfony 4.

## Data Transforming

But, the reason I wanted to show you the `DateTimeType` was *not* because of this
HTML5 fanciness. Nope! The *really* important thing I want you to notice is that,
regardless of browser support, when we submit this form, it will send this field
as a simple, date *string*. But... wait! *We* know that, on submit, the form system
will call the `setPublishedAt()` method. And... that requires a `DateTime` *object*,
not a string! Won't this totally explode?

Actually... no! It will work *perfectly*.

In reality, each field type - like `DateTimeType` - has *two* superpowers. First,
it determines *how* the field is rendered. Like, an `input type="text"` field or,
a bunch of drop-downs, or a fancy `datetime-local` input. Second... and this is
the *real* superpower, a field type is able to *transform* the data to and from
your object and the form. This is called "data transformation".

I won't do it now, but when we submit, the `DateTimeType` will *transform* the
submitted date *string* into a `DateTime` object and *then* call `setPublishedAt()`.
Later, when we create a page to edit an *existing* `Article`, the form system will
call `getPublishedAt()` to fetch the `DateTime` object, and then the `DateTimeType`
will transform *that* into a string so it can be rendered as the `value` of the
`input`.

We'll talk more about data transformers later. Heck, we're going to create one!
Right now, I just want you to realize that this is happening behind the scenes. Well,
not *all* fields have transformers: simple fields that hold text, like an input
text field or textarea don't need one.

Next: let's talk about one of Symfony's most important and most versatile field
types: `ChoiceType`. It's the over-achiever in the group: you can use it to create
a `select` drop down, multi-select, radio buttons or checkboxes. Heck, I'm pretty
sure it even knows how to fix a flat tire.

Let's work with it - and its brother the `EntityType` - to create a drop-down
list populated from the database.
