# Serializer & API Endpoint

In addition to our login form authentication, I *also* want to allow users to log in
by sending an API token. But, before we get there, let's make a proper API endpoint
first.

## Creating the API Endpoint

I'll close a few files and open `AccountController`. To keep things simple, we'll
create an API endpoint right here. Add a public function at the bottom called
`accountApi()`:

[[[ code('779b8f62dc') ]]]

This new endpoint will return the JSON representation of whoever is logged in.
Above, add `@Route("/api/account")` with `name="api_account"`:

[[[ code('69a14c5cce') ]]]

The code here is simple - excitingly simple! `$user = $this->getUser()` to
find who's logged in:

[[[ code('d946e487c8') ]]]

We can safely do this thanks to the annotation on the class: every method
requires authentication. Then, to transform the `User` object into JSON - this is
pretty cool - `return $this->json()` and pass `$user`:

[[[ code('b3b2a08b61') ]]]

Let's try it! In your browser, head over to `/api/account`. And! Oh! That's
not what I expected! It's JSON... but it's totally empty!

## Installing the Serializer

Why? Hold `Command` or `Control` and click into the `json()` method. This method does
two different things, depending on your setup. First, it checks to see if Symfony's
serializer component is installed. Right now, it is *not*. So, it falls back to
passing the `User` object to the `JsonResponse` class. I won't open that class,
but *all* it does internally is called `json_encode()` on that data we pass in:
the `User` object in this case.

Do you know what happens when you call `json_encode()` on an object in PHP? It only...
sorta works: it encodes only the *public* properties on that class. And because we
have *no* public properties, we get back nothing!

This is actually the *entire* point of Symfony's serializer component! It's a kick
butt way to turn objects into JSON, or any other format. I don't want to talk *too*
much about the serializer right now: we're trying to learn security! But, I *do*
want to use it. Find your terminal and run:

```terminal
composer require serializer
```

This installs the serializer pack, which downloads the serializer and a few other
things. As *soon* as this finishes, the `json()` method will start using the new
`serializer` service. Try it - refresh! Hey! It works! That's awesome!

## Serialization Groups

Except... well... we probably don't want to include *all* of these properties -
especially the encoded password. I know, I said we *weren't* going to talk about
the serializer, and yet, I *do* want to fix this one thing!

Open your `User` class. To control which fields are serialized, above each property,
you can use an annotation to organize into "groups". I won't expose the `id`, but
let's expose `email` by putting it into a group: `@Groups("main")`:

[[[ code('0169ae6d7b') ]]]

When I auto-completed that annotation, the PHP Annotations plugin added the `use`
statement I need to the top of the file:

[[[ code('e6ce226653') ]]]

Oh, and I totally invented the "main" part - that's the group name, and you'll see
how I use it in a minute. Copy the annotation and also add `firstName` and `twitterUsername`
to that same group:

[[[ code('a495e6312c') ]]]

To complete this, in `AccountController`, we just need to tell the `json()` method
to *only* serialize properties that are in the group called "main". To do that, pass
the normal 200 status code as the second argument, we don't need any custom headers,
but we *do* want to pass one item to "context". Set `groups =>` an array with the
string `main`:

[[[ code('670c420f4f') ]]]

You can include just one group name here like this, or tell the serializer to serialize
the properties from multiple groups.

Let's try it! Refresh! Yes! Just these three fields.

Ok, we are *now* ready to take on a big, cool topic: API token authentication.
