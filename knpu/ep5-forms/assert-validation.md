# Validation Constraints with @Assert

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
