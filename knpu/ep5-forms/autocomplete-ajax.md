# Hooking up the AJAX Autocomplete

We now have an endpoint that returns all users as JSON. *And* we have some
autocomplete JavaScript that... ya know... autocompletes entries for us. I have
a crazy idea: let's *combine* these two so that our autocomplete uses that Ajax
endpoint!

## Adding a data-autocomplete-url Attribute

First: inside of the JavaScript, we need to know what the URL is to this endpoint.
We *could* hardcode this - I wouldn't judge you for doing that - this is a no-judgment
zone. But, there *is* a simple, clean solution.

In `AdminUtilityController`, let's give our new route a name: `admin_utility_users`.
Now, idea time: when we render the field, what if we added a "data" attribute onto
the input field that pointed to this URL? If we did that, it would be *super* easy
to read that from JavaScript.

[[[ code('dbd264a2ed') ]]]

Let's do it! In `UserSelectTextType`, add another attribute: how about
`data-autocomplete-url` set to... hmm. We need to *generate* the URL to our new
route. How do we generate a URL from inside of a service? Answer: by using the
`router` service. Add a second argument to the constructor: `RouterInterface $router`.
I'll hit Alt+Enter to add that property and set it.

[[[ code('d65aafd005') ]]]

Oh, and if you can't remember the type-hint to use, at least make sure that you
remember that you can run:

```terminal
php bin/console debug:autowiring
```

to see a full list of type-hints. By the way, in Symfony 4.2, this output will look
a little bit different, but contains the same info. If you search for the word "route"
without the e... cool! We have a few different type-hints, but they all return the
same service anyways.

Now that we've injected the router, down below, use `$this->router->generate()`
and pass it the new route name: `admin_utility_users`.

[[[ code('85f4956916') ]]]

Let's check it out! Refresh, inspect that field and ... perfect! We have a shiny new
`data-autocomplete-url` attribute.

## Making the AJAX Call

Let's head to our JavaScript! I'm going to write this a little bit different - though
it would work either way: let's find all of the elements... there will be just one
in this case... and loop over them with `.each()`. Indent the inner code, then
close the extra function. 

[[[ code('4ab0d22024') ]]]

Now we can change the selector to `this` and... yea! We're basically doing the
same thing as before. Inside the loop, fetch the URL with
`var autocompleteUrl = $(this).data()` to read that new attribute.

[[[ code('145e0793b8') ]]]

Finally, clear out the `source` attribute. Since we're using jQuery already, let's
use it to make the AJAX call: `$.ajax()` with a `url` option set to
`autocompleteUrl`. That's it!

To handle the result, chain a `.then()` onto the Promise and pass a callback
with a `data` argument. Let's see: our job is to execute the `cb` callback and
pass it an array of the results.

Remember: in the controller, I'm returning all the user information on a `users`
key. So, let's return `data.users`: that should return this entire array of
data.

[[[ code('b3f8faa454') ]]]

But *also* remember that, by default, the autocomplete library expects each result
to have a `value` key that it uses. Obviously, *our* key is called `email`. To change
that behavior, add `displayKey: 'email'`. I'll also add `debounce: 500` - that
will make sure that we don't make AJAX requests faster than once per half a second.

[[[ code('0fb02f6509') ]]]

Ok... I think we're ready! Let's try this! Move back to your browser, refresh
the page and clear out the author field... "spac"... we got it! Though... it *still*
returns *all* of the users - the `geordi` users should not match.

## Filtering the Users

That's no surprise: our endpoint *always* returns *every* user. No worries - this
is the easiest part! Go back to the JavaScript. The `source` function is passed a
`query` argument: that's equal to whatever is typed into the input box at that moment.
Let's use that! Add a `'?query='+query` to the URL.

[[[ code('f8fd94902e') ]]]

Back in `AdminUtilityController`, to read that, add a second argument, the
`Request` object from `HttpFoundation`. Then, let's call a new method on `UserRepository`,
how about `findAllMatching()`. Pass this the `?query=` GET parameter by calling
`$request->query->get('query')`.

[[[ code('2eb57674c1') ]]]

Nice! Copy the method name and then open `src/Repository/UserRepository.php`.
Add the new `public function findAllMatching()` and give it a `string $query`
argument. Let's also add an optional `int $limit = 5` argument, because we probably
shouldn't return 1000 users if 1000 users match the query. Advertise that this
will return an array of `User` objects.

[[[ code('62bfdc436f') ]]]

Inside, it's pretty simple: `return $this->createQueryBuilder('u')`,
`->andWhere('u.email LIKE :query')` and bind that with `->setParameter('query')`
and, this is a little weird, `'%'.$query.'%'`.

Finish with `->setMaxResults($limit)`, `->getQuery()` and `->getResult()`.

[[[ code('2b3a07cb1e') ]]]

Done! Unless I've *totally* mucked things up, I think we should have a working
autocomplete setup! Refresh to get the new JavaScript, type "spac" and... woohoo!
Only 5 results! Let's get the web debug toolbar out of the way. I love it!

Next: there's one other important method you can override in your custom form field
type class to control how it renders. We'll use it to *absolutely* make sure our
autocomplete field has the HTML attributes it needs, *even* if we override the
`attr` option when using the field.
