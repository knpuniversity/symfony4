# Custom Field Type

Coming soon...

Go back to these `/admin/article/new` section and go to create a new `Article`. Oh, of
course. Since we just registered, you're not logged in as the right person anymore.
No problem. That's logout and log back in and log in with admin2@thespacebar.com. A
password engage. Cool. Try that again. Back to `/admin/article/new`. Alright cool. So
I want to talk more about some low level important details about these fields
themselves. So if you open up our `ArticleFormType` right now we're using the `TextType`. 
This is the `TextareaType`, this is using a `DateTimeType` and this is using
the `EntityType`. And the purpose of the field types is really two things. One, it
translates into how the field renders. This is `<input type="text">`. Second isn't is a
`<textarea>`, the third is an `<input type="datetime-local">` and of course the entity type.
The dropdown here is a `<select>` element. So of course the first and most obvious
purpose of our field types is that's how they render. But the second purpose and
perhaps even more important, is that the field types determine how the data is
processed.

For example, this published at field in my browser, it looks fancy and it has some
nice little date widgets, but ultimately this is just an input field, meaning that
data is submitted as raw text, but ultimately on my article entity, my son published
at method requires a `DateTime` object. That's actually the job of the date time type,
which it says no here, but we know from daytime,

we know from checking our profiler that published that is using the `DateTimeType`.
The real purpose of these types is that they transform data on submit a. They take
that text format and they transform it into a `DateTime` object, and even more than
that, it also transforms the other direction. If we edit an existing article, it
takes the `DateTime` object that exists on our article and city and it transforms that
back into the `string` format that's needed on the page. See, there's actually the raw
`string` format that's ultimately rendered. This process is called the `DataTransformer`

and a lot of times you need to do something special with the field. It's not that you
needed to necessarily render different, it's that you need some data to be
transformed in a different way. So here's my goal right now, we have this author
dropdown box. This eventually is going to be a problem because if we have 10,000
users, it's not gonna be very easy for us to find them when you want. Plus querying
for 10,000 users and running on the page is going to make this page really slow, so
instead I want to convert this into a text field where I can type the email address
of the user end of the text field and then submit, but of course to do that, we're
going to need a data transformer that's able to take that email address inquiry for
the `User` object because ultimately when these author,

okay,

because ultimately when `setAuthor()`, it's called on our `Article`, it needs to be a `User`
object, so we're going to do this by creating a custom form field.

Okay.

I wouldn't do this really cool. It looks almost identical to our forms. We're going
to credit new class, let's call it `UserSelectTextType`. It's going to extend that
same `AbstractType` that we've been extending in our other `Form` classes. Then go to
the code, generate menu or Command + N on a Mac and hit override methods and we have
the same so and then, but this time override, get parents to start and from inside
here, `return TextType::class;`. Internally. The form system has some inheritance in
it and for some technical reasons the form system doesn't use real inheritance, so
you might expect us to do it. Literally extent `TextType`. It doesn't work that way,
but that's how I want you to think of it. So by extending `TextType` who basically say
without doing anything else, I want this new field to basically be a `TextType`.
And that's it. That's already basically set up. So if we go over to the `ArticleFormType`, 
I'm not going to remove all of this `EntityType` stuff and say UserSelectTextType::class`. 
All right, so if we go over now and we refresh,

it actually works, you can say print out the `firstName` of the current author, but it
only works bio luck when the form object tries to render, or the author is a `User`
object to the form, tries to render a `User` object, and by chance our user object
actually has a `__toString()` method so it renders the `firstName`, but check this out
when we submit big giant air expected argument of type `User` or no `string` given when
that `firstName` is submitted, there's no data transformer on it, so ultimately it
calls `setAuthor()` and tries to pass us a `string` argument. So that's what we need to
fix. Next, we need to take our nice new symbol, a custom form field, and add a data
transformer to it to work our magic. We'll do that next.