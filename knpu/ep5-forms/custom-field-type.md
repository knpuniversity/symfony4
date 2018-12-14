# Custom Field Type

Go back to `/admin/article/new` and click to create a new article. Oh, duh! We're
not logged in as an admin anymore. Log out, then log *back* in with
`admin2@thespacebar.com` password `engage`. Cool. Try `/admin/article/new` again.

Now, open `ArticleFormType` so we can take a closer look at the field *types*.
Right now, we're using `TextType`, this is `TextareaType`, this is a `DateTimeType`
and the author drop-down is an `EntityType`. We learned earlier that the purpose
of each field type is really two things. First: it controls how the field is rendered,
like `<input type="text">`, `<textarea>`, `<input type="datetime-local">` or a select
drop down. The *second* purpose of a field type is more important: it determines
how the field's data is transformed.

For example, the `publishedAt` field has a nice date widget that was added by my
browser. But, really, this is just an input text field. What I mean is: the data
from this field is submitted as a raw text string. But ultimately, on my `Article`
entity, the `setPublishedAt` method requires a `DateTime` object! That's the job
of the `DateTimeType`: to convert that specially-formatted date *string* into a
`DateTime` object.

And *just* as important, it *also* transforms the other direction. Go to the list
page and click to edit an existing, published article. Inspect the published at
field. Yep! When the form loaded, the `DateTimeType` took the `DateTime` object
from the `Article` and transformed it *back* into the `string` format that's used
for the `value` attribute.

## Custom Field for Author

Why are we talking about this? Because I want to *completely* replace this author
dropdown, to avoid a future problem. Imagine if we had 10,000 users. Hmm, in that
case, it wouldn't be very easy to find the person we want - that would be a *big*
drop-down! Plus, querying for 10,000 users and rendering them would be *pretty* slow!

So, new plan: I want to convert this into a *text* field where I can type the
author's email. That's... *easy*! We could use `EmailType` for that! But, there's
a catch: when we submit, we need to create a data transformer that's able to take
that email address string and query for the `User` object. Because, ultimately, when
the form calls `setAuthor()`, the value needs to be a `User` object.

## Creating the Custom Form Type

To do all of this, we're going to create our first, custom form field type. Oh, and
it's really cool: it looks almost *identical* to the normal *form* classes that we've
already been building.

Create a new class: let's call it `UserSelectTextType`. Make it extend that same
`AbstractType` that we've been extending in our other form classes. Then, go to
the Code + Generate menu, or Command + N on a Mac, and select override methods.
But this time, instead of overriding `buildForm()`, override `getParent()`. Inside,
`return TextType::class`. Well, actually, `EmailType::class` might be better: it
will make it render as an `<input type="email">`, but either will work fine.

[[[ code('8adf3ca00b') ]]]

Internally, the form fields have an inheritance system. For, not-too-interesting
technical reasons, the form classes don't use *real* class inheritance - we don't
literally extend the `TextType` class. But, it works in a similar way.

By saying that `TextType` is our parent, we're saying that, unless we say otherwise,
we want this field to look and behave like a normal `TextType`.

And... yea! We're basically set up. We're not doing anything *special* yet, but
this should work! Go back over to `ArticleFormType`. Remove *all* of this
`EntityType` stuff and say `UserSelectTextType::class`.

Let's try it! Move over, refresh and... it actually works! It's a text field filled
with the `firstName` of the current author.

But... it only works thanks to some luck. When this field is rendered, the `author`
field is a `User` object. The `<input type="text">` field needs a *string* that
it can use for its `value` attribute. By *chance*, our `User` class has a
`__toString()` method. And so, we get the first name!

But check this out: when we submit! Big, hairy, giant error:

> Expected argument of type `User` or null, `string` given

When that first name string is submitted, the `TextType` has *no* data transformer.
And so, the form system ultimately calls `setAuthor()` and tries to pass it the
*string* first name!

We'll fix this next with a data transformer.
