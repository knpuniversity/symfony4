# Validation

Coming soon...

Let's talk about form validation. Our form is actually already going through a
validation process. As soon as we post to this endpoint, handle requests, reads in
the data and it runs all the validation, and then if validation fails, if form is not
valid, then immediately renders a template and now the validation errors will be
attached to the form and they will render and we'll run down the page. Of course we
haven't seen this yet because we haven't actually added any html five validation, any
validation, but check this out. There actually is some validation already. If you
haven't completely empty form and hit create, it's stops you. Please fill out this
field. A lot of you probably recognize what this is. This is html five validation.
When Symfony renders the field,

depending on your configuration, a lot of times it adds are `required="required"` key.
This is not real validation. It's just a nice little client side validation that adds
that message and there are other types of validation on other field types, like
sometimes it feels like a `datetime` field or an `<input type="number">`. Your browser
will give you some basic validation if the format is wrong or if it's not required.
This required ass right here, you can actually control in your form type. Every
single field has a an option called required. You can set that to `true` or `false`. When
you bind your form to an `Entity` class, it tries to figure out the correct value based
on your article entity. So for example, if you have knowable = true, like on content,
then it won't make that required. So in fact, if we look at our text area field here,
you will see that there is no `required` attribute on this one. There's just a bunch of
things coming from my grammarly. You can ignore it. So the first step of validation
is html five validation. You basically get it for free, but it doesn't. It's not real
validation. To do real validation, we actually need to install a validator. So find
your terminal and run 

```terminal
composer require validator
```

Validation is a separate component
in Symfony, which is great because it actually means you can use it independent of
the form system, but it works really nicely with the form system.

Perfect. Now there are actually two types of server side validation. There's what I
call sanity validation in what I call business rules validation. First let's talk
about sanity validation. Sanity validation is built into the form fields themselves
and basically the form fields. Make sure that whatever value is submitted is
something that makes sense for `title` and `content`. There is no sanity validation. We
can submit anything to those fields and it makes sense but for, but for the `EntityType`, 
there is sanity validation. Check this out. I'm going to go to inspect element,
find my select element, and let's change one of these values to be $100 something
that's not in the database, so this is space bar zero. If we select this user, I'll
select this user and hit create. Oh, actually, of course HTML5, validation
stops us, so temporarily to work around that to make my life easier. If you go on
your form class and add a no validate attribute that we'll skip HTML5 validation
to nice little trick when you're testing your service, have validation, so when was
when we hit create boom air, our first ever air. This value is not valid, so built
into some of the form fields themselves is validation to make sure that they're
sending a a actual real value. This is not that important of this is for the most
part. This is validation that you don't need to think about. It just works. If you
want to control the message, you can pass an option called `invalid_message` and we
could say Symfony is too smart for your hacking.

She moved back and refresh. Now to repost that. Perfect. You'll see that error. The
real validation I want to talk about is business rules validation. This is where you
tell Symfony that's in the title needs to be a certain length or a certain field
needs to be an email. That's all the specific stuff that you know about in. One
interesting thing about Symfonys validation is that you don't usually apply it to
your form. You apply it to your `Entity` via annotations, so check this out. We want
the `title` field to be required, so let's add a new APP, `NotBlank()` annotation now
because I have the PHB annotations plugin installed when I auto completed that, it
added a use statement on top for a `Symfony\Component\Validator\Constraints as Assert;`,
so as soon as we add that, if we go back and actually I can refresh because my `title`
is already empty. Yes, we get an error. This value should not be blank. To customize
that error, we can add a `message`. Key here will say, get creative and think of a
title, go back, refresh, and perfect in general. Go back to the Symfony forums, go
back to the documentation,

and under guides find the validation guide. Just like with the form fields, there are
a bunch of built in constraints that can help you validate just about everything
seriously. There is a lot of stuff in here and also like with form field types, every
validation constraint has different options. So for example, there's one called
`length` and you can set the min, a Max length and also a min message, a Max message,
and some other things. Another way to see what the options are is remember with every
annotation, there's actually a class name behind that. So because I have a phd
annotations plugin installed, I can hold command or control and click into that class
and every property is basically an option that you can pass the annotation. So we're
not going to talk too much about a foundation constraints because they're honestly
pretty easy and it's just a matter of finding which foundation constraint you need
and the options that you need for it. But there's one really cool one called
`Callback`. This is a really great way just to do super custom validation. What you do
is you just create a method but at a `@Assert\Callback()` on it. In Symfony, we'll call
that method. So let's actually copy this here.

We're going to our article class and let's go all the way at the bottom and I will
need to retype the eon `ExecutionContextInterface` to get that type event. And then
inside, it's awesome, you can do whatever you want. So let's make sure that the `title`
of this `Article` doesn't contain the `string`, the Borg. So `stripos()` of 
`$this->getTitle()` because we're validating this object. The Borg does not equal `false`.
Then we can add a validation error. Use that `$context` variable. You say `->buildViolation()`
 given an error, I'm bored, kind of scare us,

kind of

makes us nervous. And then you can say `->atPath()`, and this is important because you
choose where you want that validation. Error messages show up. So you want to show up
massively `title` property and then we'll say `->addViolation()`. That's it. So now if we
go back talk, how we really want to join the board

and create.

Yes, we got it. Custom validation message on that. Next, let's talk a little bit more
about how we can control the rendering of these fields. Because right now we're just
sort of rendering them all at once. We don't really know how to control the look and
feel.