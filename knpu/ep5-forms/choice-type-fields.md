# Setup: For Dependent Select Fields

We're going to tackle one of the most *annoying* things in Symfony's form system,
and, I hope, make it as *painless* as possible... because the end result is pretty
cool!

Log in as `admin2@thespacebar.com`, password `engage` and then go to
`/admin/article`. Click to create a new article. Here's the goal: on this form,
I want to add two new drop-down select elements: a location drop-down - so you can
choose where in the galaxy you are - and a second dropdown with more *specific*
location options depending on what you chose for the location. For example, if
you select "Near a star" for your location, the next drop-down would update to
be a list of stars. Or, if you select "The Solar System", the next drop-down will
be a list of planets.

## Adding the First Select Field

This is called a "dependent form field", and, unfortunately, it's one of the trickier
things to do with the form system - which is *exactly* why we're talking about it!
Let's add the first new field. Find your terminal and run

```terminal
php bin/console make:entity
```

Modify the `Article` entity and create a new field called `location`. Make it a
`string` field with "yes" to `nullable` in the database: the location will be
optional. Now run:

```terminal
php bin/console make:migration
```

and open the `Migrations/` directory to check out that new file.

[[[ code('ec8fe8d800') ]]]

No surprises here, so let's go back and run it:

```terminal
php bin/console doctrine:migrations:migrate
```

Perfect!

Next, open the `ArticleFormType` so we can add the new field. Add `location` and
set it to a `ChoiceType` to make it a drop-down. Pass the `choices` option set to
just three choices. `The Solar System` set to `solar_system`, `Near a star` set
to `star` and `Interstellar Space` set to `interstellar_space`.

[[[ code('624480ff78') ]]]

The `choices` on the `ChoiceType` can look confusing at first: the *key* for each
item will be what's actually *displayed* in the drop down. And the *value* will
be what's *set* onto our entity if this option is selected. So, this is the string
that will ultimately be saved to the database.

Let's also add one more option: `required` set to `false`.

[[[ code('0ad2b473d0') ]]]

Remember: as soon as we pass the field type as the second argument, the form field type guessing stops
and does nothing. Lazy! It would normally guess that the `required` option *should*
be false - because this field is not required in the database, but that won't happen.
So, we set it explicitly.

Cool! Let's try it - go refresh the form. Ha! It works... but in a surprising way:
the `location` field shows up... all the way at the bottom of the form.

The reason? We forgot to render it! Open `templates/article_admin/_form.html.twig`. 
When you forget to render a field, `{{ form_end() }}` renders it for you. It's
kind of a nice reminder that I forgot it. Of course, we don't *really* want to render
it all the way at the bottom like this. Instead, add
`{{ form_row(articleForm.location) }}`

[[[ code('ad5a26e45e') ]]]

Oh, and I forgot: we'll want an "empty" choice at the top of the select. In the
form, add one more option: `placeholder` set to `Choose a location`.

[[[ code('b1e3bac2b7') ]]]

Refresh! So much nicer! And if we submitted the form, it *would* save.

## Adding the Second Field

So, let's add the second field! Go back to your terminal and run:

```terminal
php bin/console make:entity
```

Update the `Article` entity again and create a new field called `specificLocationName`,
which will store a string like "Earth" or "Mars". Make this "yes" to `nullable` in
the database - another optional field.

When you're done, make the migration:

```terminal-silent
php bin/console make:migration
```

And... I'm *pretty* confident that migration won't have any surprises, so let's
just run it:

```terminal-silent
php bin/console doctrine:migrations:migrate
```

Sweet! Back in `ArticleFormType`, copy the `location` field, paste, and call it
`specificLocationName`. For the `placeholder`, use `Where exactly?`. And for the
`choices`... hmm - this is where things get interesting. I'll just add a dummy
"TODO" option to start.

[[[ code('26bcfc4b86') ]]]

Back in the form template, copy the `location` render line, paste it right below,
and change it to `specificLocationName`.

[[[ code('b433a61c44') ]]]

When we refresh now... no surprise: it works. Here's our `location` and here's our
`specificLocationName`. But... this is not how we want this to work. When
"solar system" is selected, I want this second drop-down to contain a list of
planets. If "Near a star" is selected, this should be a list of stars. And if
"Interstellar space" is selected, I don't want this field to be in the form at
all. Woh. 

The way to solve this is a combination of form events, JavaScript and luck! Ok,
I hope we won't need too much of that. Let's start jumping into these topics next!
