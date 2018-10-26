# Data Transformer

Coming soon...

Hey, we have a custom field type `UserSelectTextType` two we're using for our
author field. That's cool, except it's pretty much just a `TextType` right now, thanks
to to `getParent()`, which means when you render it, it renders as a text field. Nothing
fancy at all. Now the text field internally, it doesn't have any idea of a data
transformer. It just takes whatever the value is on our object and tries to print it,
so because this is attached to our `author` field on our `Article` and our other field is
a `User` object, the text field basically drives do you take our `User` object and just
print it and it does thanks to our `__toString()` method, so that's why this actually
renders. If we remove the `__toString()` method and refreshed, we'd actually get a huge
error because it can't print our `User` object anymore and even more importantly, even
if we put this back, our forum renders but our form doesn't actually submit yet. When
we try to submit this, we get a huge error because it is taking that `string` input and
form system is called is trying to pass that as an argument as the `author` to `setAuthor()`, 
which doesn't work, so we need a data transformer and the goal really is that
one the field and renders I want it to render and print out the `email` of the `User`,
not the `firstName`, and when we submit

we're going to transform the `email` back into the `User` object. So here's how we do
this. Super Awesome in the `Form/` directory. Create a `DataTransformer/` directory though
the path of these things doesn't matter as usual. Then create a new `EmailToUserTransformer` 
DataTransformers need to implement a `DataTransformerInterface`. Then
I'll go to the code, generate menu or command in Omak. Go to implement methods and
choose the two methods we need in transformers are really cool. Let's `dd()` both of
these methods just to see when these are called and what these values look like. So
`dd('transform', $value)` and `dd('reverse transform', $value)` to make our users select type. 
Use this, go back to the code, generate menu or Command + N on a Mac, go to override
methods and select `buildForm()`. Now you might remember `buildForm()`

because this is the method that we override in our normal `Form` classes to actually
add the fields. It turns out there are a couple other things you can do with this
`$builder` object. One of them is you can say `$builder->addModelTransformer()`, and
then say `new EmailToUserTransformer()`. As soon as we do that, when we see this
field, we'll now use our transformer to see what that means. I'll hit enter on the
url to refresh and rerender the form. As soon as we do that, boom, we hit the
`transform()` method in we're past the `User` object. This is awesome guys. That's the
purpose of `transform()`. This method is called. This method is called when the form is
render, it takes the value that lives on your data. In our case, it's actually a `User`
object that lives on the `author` field and our job is to transform that into a
representation that can be used on the form. In other words, the `email` string, so
check this out. First, let's check to see the value is no just to be safe, and if it
is that of course you just render as an empty `string`. Then next I'm going to do a
little bit of a sanity check here just to make sure that we're not doing something
incorrect on accident. If he, `if (!$value instanceof User)`, then
somebody is using this transformer incorrectly, so we'll `throw new \LogicException()`
that says "The UserSelectTextType can only be used with User objects".

And then finally at the bottom, so easy `return $value`, which we now know is our user
object. `->getEmail()`. Alright, try that out, went back over, refresh and hello
email address. That is the transformed method. All right, so let's do the reverse.
When we actually submit this, we hit `reverseTransform()` and we are past the actual
string data that was submitted. All we need to do is query for a `User` object that has
that `email` address and we are good. That means that instead of `reverseTransform()`,
we're going to need to do a database query. We need our `UserRepository` now, the one
now, so we're going to do our normal dependency injection flow. I'll add a
`__constructor()` method and we'll add `UserRepository $userRepository`. I'll hit alt enter
and select initialize fields to create that property and set.

Now normally that's all we needed to do and down below we can use this immediately,
but this object is actually not created by the Symfonys container, so we don't get
auto wiring magic here. What I literally mean is this object is being instantiated
directly by us, so it is our responsibility to pass it whatever it needs. So we
actually needed to do a little bit of an extra step here where in our field tech we
actually need to add a `__constructor()` and do the exact same thing at a `UserRepository`
argument and hit enter to initialize that field to initialize that field. Our form
fields, our services. So Symfony is going to install the containers. When it's
tainted this object, it's going to pass us the `UserRepository`, so that does work
down here. We just need to pass `$this->userRepository` manually into our `EmailToUserTransformer`,
so that's the only kind of tricky part you need to pass things to direct
two steps. Alright, now in `reverseTransform()`, we do our job `$user = $this->userRepository`, 
and we can use the `findOneBy()` method to look for `email` set to `$value`.
Now, if there is not a user that was found, what we need to do is 
`throw new TransformationFailedException()`. This is actually a class as a use
statement was already added above when we implemented the interface methods and in
here I'll put a little message

that says no user found with email percent s and then we'll pass the value. That
message actually is not going to be shown to the end users. You'll see, you'll see
how that's and talk more about what the user will see in a second. At the bottom
`return $user;` `TransformationFailedException` is important because that's
actually going to signal a effectively basically a validation error, so check this
out, let's go back. I'm going to refresh to resubmit that form and cool, it looks
like it works. It probably save this to the database and then rerender it. So let me
change this to spacebar3@example.com and yes it looks like it's saved.
That can even just hit submit to refresh this form directly from the database and
yes, it's loading it. That is a beautiful data transformer. Now check this out. If we
put something that doesn't exist, spacebar300@example.com of that come we fail
validation. This value is not valid. That's happening because our data transformation
is failing. This transformation failed exception causes a validation error, not the
validation errors that we're used to like when we add constraint messages like at a
certain email and as her blank. This is what I call it, referred to early as sanity
validation built into the form fields themselves. Sometimes a field can actually fail
because it's being passed a ridiculous value.

We actually showed this as an example back when we use the entity type and this was a
user dropdown. If we hacked the options on that drop down and add a `User` that didn't
exist and submitted. We got this sanity validation error message. So next, let's see
how we can customize this, this error message and learn and do a couple of other
fancy things to make our custom field and more flexible.