# Clear that Location Name Data

When we change to the solar system, great! It loads the planets. We can even change
to "Interstellar Space" and it disappears. We're amazing! And when we change it to
"Choose a Location"... uhhh oh! Nothing happened? Ah, the Ajax part of the web debug
toolbar is trying to tell me that there was a 500 error!

By the way, this is one of the *coolest* features of the web debug toolbar: when you
get a 500 error on an AJAX call, you can click this link to jump straight into the
profiler for that request! It takes us straight to the Exception screen so we can
see exactly what we messed up, I mean, what went wrong... that may or may not be
our fault.

## Fixing our Empty Value Bug

Apparently `ArticleFormType` line 125 has an undefined "empty string" index.
Let's go check that out. This is the method that we call to get the correct
`specificLocationName` choices. But, in this case, the `location` is an empty string,
and that's a *super* not valid key.

To fix this, add `?? null`. This says, *if* the location key is set, use it,
else use `null`.

[[[ code('40e4d027c2') ]]]

Let's make sure that worked: on your browser, switch back to the solar system,
and then back to "Choose a Location". Nice! The field disappears and *no* 500
error this time.

## Forcing specificLocationName to null

There's *one* other subtle problem with our setup. To see it, refresh this
page. In the database, this article's location is `star`, `specificLocationName`
is `Rigel` and id is 28. Let's go verify this in the database: find your terminal
and run: 

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM article WHERE id = 28'
```

Yep! All the data looks like we expected! But *now*, change the location to
"Interstellar Space" and hit update. It works... but try that query again:

```terminal-silent
php bin/console doctrine:query:sql 'SELECT * FROM article WHERE id = 28'
```

Ok: the `location` is `interstellar_space`, but ah! The `specific_location_name`
is *still* Rigel! This may or may *not* be a real problem - depending on how you
use this data. But it's *for sure* technically wrong: when we change the location
to `interstellar_space`, the `specific_location_name` should be set *back* to `null`:
we are *not* at Rigel.

The reason this did *not* happen is subtle. When we change the location to
"Interstellar Space" and submit, our `POST_SUBMIT` listener function calls
`setupSpecificLocationNameField()`, which sees that there are no choices for this
location and so removes the field entirely. The end result is that the form makes
*no* changes to the `specificLocationName` property on `Article`: it just *never*
calls `setSpecificLocationName()` at all... because that field isn't part of the
form!

That *is* the correct behavior. But, it means that we need to do a *little* bit more
work to clean things up. There are a few ways to fix this inside the form itself.
But, honestly, they're overly-complex. The solution *I* like lives entirely in
`Article`. Open that class and find the `setLocation()` method. Inside, if there
is *no* location, or if the location equals `interstellar_space`,
call `$this->setSpecificLocationName(null)`.

[[[ code('e692eb4148') ]]]

Simple! Oh, and in a real app, I'd probably add some class constants in `Article`
to represent these special location keys so we could use something like
`Article::INTERSTELLAR_SPACE` instead of just the string `interstellar_space`.

Let's try this people! First change the data back to a planet. Then, change it
to "Interstellar Space" and update. Cool! Spin back over to our terminal and run
that same query:

```terminal-silent
php bin/console doctrine:query:sql 'SELECT * FROM article WHERE id = 28'
```

*Now* it's set to `null`. Awesome. Next: we're pretty much done! There's just
*one* last piece of homework left - and it's related to securing one of our endpoints.
