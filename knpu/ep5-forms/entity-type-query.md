# EntityType: Custom Query

Right click and "Inspect Element". Look at the *value* of each option: it's the
`id` of that user in the database. So, when we choose an author, *this* is the
value that will be submitted to the server: this *number*. Just remember that.

Time to author another award-winning article:

> Pluto: I didn't want to be a Planet Anyways

Set the publish date to today at any time, select an author and... create! Yes!
The author is `spacebar3@example.com` and it *is* published.

This is *way* more amazing than it might look at first! Sure, the `EntityType` is
cool because it makes it easy to create a drop-down that's populated from the database.
Blah, blah, blah. That's fine. But the *truly* amazing part of `EntityType` is its
*data transformer*. It's the fact that, when we submit a *number* to the server - like
17 - it queries the database and *transforms* that into a `User` object. That's
important because the form system will *eventually* call `setAuthor()`. And this
method *requires* a `User` object as an argument - not the number 17. The data transformer
is the magic that makes that happen.

## Creating a Custom Query

We can use this new knowledge to our advantage! Go back to the create form. What
if we don't want to show *all* of the users in this drop-down? Or, what if we want
to control their *order*. How can we do that?

Normally, when you use the `EntityType`, you don't need to pass the `choices` option.
Remember, if you look at `ChoiceType`, the `choices` option is how you specify which,
ah,  *choices* you want to show in the drop-down. But `EntityType` queries for the
choices and basically sets this option *for* us.

To control that query, there's an option called `query_builder`. *Or*, you can do
what I do: be less fancy and simply override the `choices` option entirely. Yep,
you basically say:

> Hey `EntityType`! Thanks... but I can handle querying for the choices myself. But,
> have a *super* day.

## Injecting Dependencies

To do this, we need to execute a *query* from inside of our form class. And to do
*that*, we need the `UserRepository`. But... great news! Form types are services!
So we can use our favorite pattern: dependency injection.

Create an `__construct()` method with an `UserRepository` argument. I'll hit alt+enter,
and select "Initialize Fields" to create that property and set it. Down below,
pass `choices` set to `$this->userRepository` and I'll call a new method
`->findAllEmailAlphabetical()`.

[[[ code('d8d3e21dc2') ]]]

Copy that name, go to `src/Repository/`, open `UserRepository`, and create that method.
Use the query builder: `return $this->createQueryBuilder('u')` and then
`->orderBy('u.email', 'ASC')`. Finish with `->getQuery()` and `->execute()`.

Above the method, *we* know that this will return an array of `User` objects. So,
let's advertise that!

[[[ code('341ee4679c') ]]]

I love it! This makes our `ArticleFormType` class happy. I think we should try it!
Refresh! Cool! The admin users are first, then the others.

## So... is EntityType Still Needed?

But... wait. Now that we're *manually* setting the `choices` option... do we even
need to use `EntityType` anymore? Couldn't we switch to `ChoiceType` instead?

Actually... no! There is one *super* critical thing that `EntityType` is still giving
us: data transformation. When we submit the form, we *still* need the submitted
*id* to be transformed back into the correct `User` object. So, even though we're
querying for the options manually, it is *still* doing this very important job for
us. Remember: the *true* power of a field type is this data transformation ability.

Next: let's add some form validation! It might work a little differently than you
expect.
