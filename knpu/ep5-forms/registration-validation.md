# Registration Validation

Coming soon...

Okay, registration form works, but we have a few problems. First it looks terrible,
but we'll fix that in a second. More importantly, it completely lacks validation
except of course for the HTML5 validation that we get for free, but we can't rely
on that. So let's add values, validation constraints and half of this is really easy.
So we have an `email` field. We have a `plainPassword` field so far. The way we know to
add value constraints is to go onto the class that the form is bound to `User`, find
the email field in. Start adding the foundation constraints here. So I'll add 
`@Assert\NotBlank()`, I'm going to hit tab that automatically added the use David on top that we
need and then also say `@Assert\Email()` so that this is going definitely has to be an
email. So if we move over spec element on the form, we can play with this by adding
the no validate html attribute on there and we can just say food can enter and nice.
We get the error message on that and if we want to change that message a little bit,
we already know how these annotations always have options attached to them. There's
always one called `message` and begins almost always and so we can set that to please
enter

and since I know the not blank messages is not that great, I'll change that to please
enter an email. Cool. So that takes care of the `email` field. Now there's one other
type of validation that we have not really thought about yet and that's when somebody
tries to register as an existing user like

Jordy at the enterprise that org. Once again I'll add my know, validate so I can just
leave my `password` empty, get registered and oh that explodes. Integrity constraint.
So fortunately we do have the `email` marked as unique in the database so it doesn't
actually allow us to save it, but probably not what we want to happen. So this is the
first time that we have a validation concern that's not just as simple as looking at
a field and making sure it's value is `NotBlank` or it's a valid `email` address. This
is something where we need to look into the database and makes sure that there are no
existing, uh, users with this email address. This is actually a more complex type of
validation.

You may also remember that when you have more complex validation situations, a lot of
times you can use a `Callback` constraint. Then just put a method inside of your entity
and you can do whatever logic you want inside of here. But the one limitation to the
`Callback` constraint is that because the callback is inside of your entity class, you
don't have access to any services. For example, you don't have access to the entity
manager so you can't make any queries. So ultimately if you need to do something that
you can do and they call back constraint, you're going to need to create a custom
validation constraint, which is actually a service class. It's not something we're
going to talk about in this tutorial because

because it's very similar to other parts of Symfony where you create a service, tell
somebody about the service and it's well documented. The reason we don't have to
worry about it, we don't have to create a custom validation constraint in this case
is that validating for uniqueness is such a common thing. That Symfony has a special
annotation constraint built in, but instead of going above your property, this
constraint actually goes above your class, so had `@UniqueEntity`, and notice this
actually adds a different use statement that's not important, but I want to point
that out. Let's put this in multiple lines and this needs a couple of options. One is
called `fields`, which is the fields that should be unique and for us it's just `email`,
but you can also make something unique across multiple fields and then we'll have of
course the `message="I think you've already registered"`. That was a reminder because
each entity is mapped to a specific class. If you have the PHB annotations plugin
installed, you can hold command or control and click into that and see all the
different options that you can pass to that, but this stuff is also documented.

So now we go back and refresh.

Nice. We get that good air. Okay, so the last thing we need to do is the `password`
field. This one is a little bit different because in our forum class we've set this
to map false. There is no `plainPassword` property inside of our `User` class that we
can add annotations on and that's fine and we'll usually add annotation constraints
or a class. But if you do have a field that's not mapped, you can add them right here
via a `constraints` array. What do you put inside of that? You just initialize the same
annotation objects that you're, that you're accustomed to using. So we'll say 
`new NotBlank()`, and to pass the options here, we'll pass an array. It will say 
`'message' => 'Choose a password!'`.

Yeah,

and how about not only blank, but we'll say `new Length()` validation constraint so that
we can actually make it a certain length and if you want to see what the options are,
length, I'll hold command or control. Click into that and you can see that this has a
`min`, `max`, `MinMessage`, `MaxMessage`, so you can configure it exactly how you want it.
So we'll say men said to you, how about just five and then men message set too.

Come on,

you can think of a password longer than that. And that's it. These will work just
like have the annotations on the Insti class themselves. Now we go back and refresh.
We should also get a password on her plain password field and as we do so, there's
validation. Even when you have an unmapped field.