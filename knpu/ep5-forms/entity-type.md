# EntityType: Drop-downs from the Database

On submit, we set the `author` to *whoever* is currently logged in. I want to change
that: sometimes the person who *creates* the article, isn't the author! They need
to be able to *select* the author.

## ChoiceType: Maker of select, radio & checkboxes

Go to the documentation and click back to see the list of form field types. One
of the most *important* types in *all* of Symfony is the `ChoiceType`. It's kind
of the loud, confident, over-achiever in the group: it's able to create a select
drop-down, a multi-select list, radio buttons *or* checkboxes. It even works on
weekends! Phew!

If you think about it, that makes sense: those are all different ways to *choose*
one or more items. You pass this type a `choices` option - like "Yes" and "No" -
and, by default, it will give you a select drop-down. Want radio buttons instead?
Brave choice! Just set the `expanded` option to true. Need to be able to select
"multiple" items instead of just one? Totally cool! Set `multiple` to `true` to
get checkboxes. The `ChoiceType` is awesome!

But... we have a special case. Yes, we *do* want a select drop-down, but we want
to *populate* that drop-down from a table in the database. We *could* use
`ChoiceType`, but a much easier, ah, choice, is `EntityType`.

## Hello EntityType

`EntityType` is kind of a "sub-type" of choice - you can see that right here:
parent type `ChoiceType`. That means it basically works the same way, but it makes
it easy to get the `choices` from the database and has a few different options.

Head over to `ArticleFormType` and add the new `author` field. I'm calling this
`author` because that's the name of the property in the `Article` class. Well,
actually, that doesn't matter. I'm calling this `author` because this class has
`setAuthor()` and `getAuthor()` methods: *they* are what the form system will call
behind the scenes.

[[[ code('5c27126426') ]]]

As *soon* as we add this field, go try it! Refresh! Hello drop-down! It *is*
populated with all the users from the database... but... it might look a little
weird. By default, the `EntityType` queries for all of the `User` objects and then
uses the `__toString()` method that we have on that class to figure out what display
value to use. So, `firstName`. If we did *not* have a `__toString()` method, we
would get a huge error because `EntityType` wouldn't know what to do. Anyways,
we'll see in a minute how we can control what's displayed here.

## Set the Type, Options are Not Guessed

So... great first step! It looks like the form guessing system correctly sees the
Doctrine relation to the `User` entity and configured the `EntityType` for us. Go
team!

But now, pass the type manually: `EntityType::class`. That should make no difference,
right? After all, the guessing system was *already* setting that behind the scenes!

[[[ code('6440150b8a') ]]]

Well... we're programmers. And so, we know to expect the unexpected. Try it!
Surprise! A huge error!

> The required option `class` is missing

But, why? First, the `EntityType` has *one* required option: `class`. That makes
sense: it needs to know which entity to query for. Second, the form type guessing
system does *more* than just guess the form *type*: it can also guess certain
field *options*. Until now, it was guessing `EntityType` *and* the `class` option!

But, as *soon* as you pass the field type explicitly, it stops guessing *anything*.
That means that *we* need to manually set `class` to `User::class`. This is why
I often *omit* the 2nd argument if it's being guessed correctly. And, we *could*
do that here.

[[[ code('310b2b4826') ]]]

Try it again. Got it!

## Controlling the Option Display Value

Let's go see what *else* we can do with this field type. Because EntityType's parent
is `ChoiceType`, they share a lot of options. One example is `choice_label`. If you're
not happy with using the `__toString()` method as the display value for each `option`...
too bad! I mean, you can *totally* control it with this option!

Add `choice_label` and set it to `email`, which means it should call `getEmail()`
on each `User` object. Try this. I like it! Much more obvious.

[[[ code('1ec4460b50') ]]]

Want to get fancier? I thought you would. You can *also* pass this option a callback,
which Symfony will call for each item and pass it the data for that option - a `User`
object in this case. Inside, we can return whatever we want. How about
`return sprintf('(%d) %s')` passing `$user->getId()` and `$user->getEmail()`.

[[[ code('c5e5d5a5f1') ]]]

Cool! Refresh that! Got it!

## The "Choose an Option" Empty Value

Another useful option that `EntityType` shares with `ChoiceType`  is `placeholder`.
This is how you can add that "empty" option on top - the one that says something
like "Choose your favorite color". It's... a little weird that we *don't* have
this now, and so the first author is auto-selected.

Back on the form, set `placeholder` to `Choose an author`. Try that: refresh.
Perfecto!

[[[ code('3cb6411d04') ]]]

With all of this set up, go back to our controller. And... remove that `setAuthor()`
call! Woo! We don't need it anymore because the form will call that method
*for* us and pass the selected `User` object.

We just learned how to use the `EntityType`. But... well... we haven't talked
about the most *important* thing that it does for us! Data transforming. Let's
talk about that next and learn how to create a custom query to select and order
the users in a custom way.
