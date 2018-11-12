# Hooking up the AJAX Autocomplete

Coming soon...

Okay, we now have an endpoint that returns to us all the users in the system as JSON
and we have some custom autocomplete JavaScript which has the ability to set auto
complete stuff for us, so I have a crazy idea. Let's combine these two so that our
autocomplete actually uses that Ajax endpoint arts a. here's how first, instead of
our JavaScript, we're going to need to know the `url` to the endoint. There's
actually a kind of a cool way we can do this in `AdminUtilityController`. Let's give
our new route named `admin_utility_users`. Now, here's the idea. When we render our
input field, what if we added a little data attribute onto the input field which was
set to that url? If we did that, it would be super easy in our JavaScript to read
that data off of there and use that endpoint from inside of our JavaScript. In other
words, in `UserSelectTextType`, we're going to need a. we're going to want to add a
new attribute like `data-autocomplete-url` set to some value, but for this
value we need to generate the url. How do we generate the url from inside of service
by using the `Router` service? So at the top of this class, at a second argument for
`RouterInterface $router`

I'll hit enter to initialize that field to create that field and set it.
Remember all of these type events `RouterInterface`, we can get those by running 

```terminal
php bin/console debug:autowiring
```

. By the way, in the next version of Symfony Symfony
four point two, this will actually look. This list will look a little bit different,
but it still contains the same information, so if I really run that search for the
word route without the e, Yep, we can see we have a couple options, but there's
`RouterInterface` anyways. Now that we have this injected in down here, we can just
say `$this->router->generate()`. Then we'll use that new `admin_utility_users` and point. So
as soon as we do this, when we refresh,

hey,

we do have that new `data-autocomplete-url`. Awesome. Now on our JavaScript, we're
going to read that, but actually to do that, I'm going to wrap us. I'm going to write
this code a little bit different. I'm gonna search for these always items and do an
`each()` function on them,

and then I'm going to in debt. Well then I'm going to indented this inner loop and
then close them down and then it's inside. I'll change this to `this` so it's
effectively doing the same thing as before. Just looping over each of those elements
and then inside it's calling initializing the autocomplete on each of those. The
reason I'm doing this is that it allows me inside the loop to say 
`var autocompleteUrl = $(this).data()`, and then we can use that `autocomplete-url`.
Fetch that off of there. Now we are dangerous in source. Clear this out. We're not
going to make an Ajax request since I have j jquery available. Obviously use it to do
it for you. We're out. We'll send it to the `autocompleteUrl` and that's it. Now when
it's done that, `then()`

our callback will be passed and inside here we need to call the CB function and we
need to pass it to the data that should be used in the auto complete. Now remembering
the controller, I'm returning all the user information on a user's key, so I'm
actually going to return `data.users` that will return that entire array of data.
So you can picture it here. It's going to return this array. Now, by default, the
autocomplete plugin expects her to be a value key here. Obviously ours is called
email. So to control that we can say `displayKey: 'email'`. I'm also going to add a deep
`debounce: 500`, uh, he here, which will make sure that it only requests doesn't every half
a second to our end point. Um, I think we're ready guys. Let's try this. Move back
over. Let's refresh the page and that's cleared out space bar. Yes, we got it

returning all of the users, but notice Jordy at the enterprise that work does not
match space, but of course that's our fault because our endpoint right now always
returns all of the users. There's nothing to actually filter those users yet by
what's typed in that fortunately is really easy to add. Notice on this `source`
function. We're past the `query`. That's whatever is typed into the box at that point
so we can just add a little `+'?query='+query`. So now we're
gonna. Have a question mark, we're = and then whatever it's a typed in Pastoria
endpoint and an `AdminUtilityController`. We can use this at a second argument, the
`Request` object from `HttpFoundation`, and then I'm going to call a new method on a
year or two repository called `findAllMatching()`. I'm going to pass it that question
mark. We're = the way you require your parameters and Symfony is `$request->query`. I
know a little confusing and then `->get()` and then I put `'query'` here again because we're
looking for a question mark. Query = alright, let's copy that method name and then
we'll go to `src/Repository/UserRepository.php`

and we'll add our new method here, so `public function findAllMatching()`, taken a
`string $query` argument and I'm also going to take  `int $limit = 5` arguments and set that
to five. By default, we actually don't want to return all of the matching users.
That's probably not that helpful and the list could be huge. So let's just return
five by default and this will return an array of user objects for the query. It's
pretty simple. `return $this->createQueryBuilder('u')` will give it the alias then 
`->andWhere('u.email LIKE :query')` wildcard and we'll in that :query wildcard to be equal to it's
a little weird `'%'.$query.'%'` and then percent.

Then finally,

we'll set `->setMaxResults()` to be our `$limit`

and then `->getQuery()`,

then `->getResults()` which will just execute that in return the `array`. That's it. Any
luck, we should have it. You don't actually need to refresh anything because we only
changed under. We do. We need to refresh that. Our JavaScript starts sending us that
new query parameter, then tracking space

and

yes, perfect. Only five results. I know the debug two bars on the way here and you
don't see any that shouldn't match. All right, next, let's do something that I can't
remember what it is.
