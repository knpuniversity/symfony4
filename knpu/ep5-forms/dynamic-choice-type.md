# Form Events & Dynamic ChoiceType choices

Let's focus on the edit form first - it'll be a little bit easier to get working.
Go to `/admin/article` and click to edit one of the existing articles. So, based
on the `location`, we need to make this `specificLocationName` field have different
options.

## Determining the specificLocationName Choices

Open `ArticleFormType` and go to the bottom. I'm going to paste in a function I wrote
called `getLocationNameChoices()`. You can copy this function from the code block
on this page. But, it's fairly simple: We pass it the `$location` string, which will
be one of `solar_system`, `star` or `interstellar_space`, and it returns the choices
for the `specificLocationName` field. If we choose "solar system", it returns planets.
If we choose "star", it returns some popular stars. And if we choose "Interstellar space",
it returns `null`, because we actually don't want the drop-down to be displayed at
*all* in that case.

[[[ code('8e9ed70d20') ]]]

Oh, and I'm using `array_combine()` just because I want the display values and the
values set back on my entity to be the same. This is equivalent to saying
`'Mercury' => 'Mercury'`... but saves me some duplication.

## Dynamically Changing the Options

The first step to get this working is not *so* different from something we did
earlier. To start, *forget* about trying to use fancy JavaScript to instantly reload
the `specificLocationName` drop-down when we select a new location. Yes, we *are*
going to do that - but later.

Hit "Update" the save the location to "The Solar System". The first goal is this:
when the form loads, because the `location` field is already set, the
`specificLocationName` should show me the planet list. In other words, we should be
able to use the underlying `Article` data inside the form to figure out which
`choices` to use.

I'll add some inline documentation just to tell my editor that this is an `Article`
object or `null`. Then, `$location = `, if `$article` is an object, then
`$article->getLocation()`, otherwise, `null`.

[[[ code('935f3e17f5') ]]]

Down below, copy the entire `specificLocationName` field and remove it. Then *only*
`if ($location)` is set, add that field. For `choices`, use
`$this->getLocationNameChoices()` and pass that `$location`.

[[[ code('8f89f50b29') ]]]

Cool! Again, no, if we change the `location` field, it will *not* magically update
the `specificLocationName` field... not yet, at least. With this code, we're saying:
when we originally load the form, if there is already a `$location` set on our
`Article` entity, let's add the `specificLocationName` field with the correct choices.
If there is *no* location, let's not load that field at *all*, which means in
`_form.html.twig`, we need to render this field conditionally:
`{% if articleForm.specificLocationName is defined %}`, then call `form_row()`.

[[[ code('191402382a') ]]]

Let's try this! Refresh the page. The Solar System is selected and so... sweet!
There is our list of planets! And we can totally save this. Yep! It saved as Earth.
Open a second tab and go to the new article form. No surprise: there is *no*
`specificLocationName` field here because, of course, the location isn't set yet.

Our system now... sort of works. We can change the data... but we need to do it
little-by-little. We can go to "Near a Star", hit "Update" and *then* change the
`specificLocationName` field and save that. But I can't do it all at once: I need
to fully reload the page... which kinda sucks!

## Can you Hack the Options to Work?

Heck, we can't even be clever! Change location to "The Solar System". Then, inspect
element on the next field and change the "Betelgeuse" option to "Earth". In theory,
that should work, right? Earth *is* a valid option when `location` is set to `solar_system`,
and so this should *at least* be a hacky way to work with the system.

Hit Update. Woh! It does *not* work! We get a validation error: This value is
not valid. Why?

Think about it: when we submit, Symfony *first* builds the form based on the `Article`
data that's stored in the *database*. Because `location` is set to `star` in the
database, it builds the `specificLocationName` field with the *star* options. When
it sees `earth` being submitted for that field, it looks invalid!

Our form needs to be even smarter: when we submit, the form needs to *realize* that
the `location` field changed, and rebuild the `specificLocationName` choices before
processing the data. Woh.

We can do that by leveraging form events.
