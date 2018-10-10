# Impersonation (switch_user)

While we're inside `security.yaml`, I want to talk about another really cool feature
called `switch_user`. Imagine you're an admin user and you're trying to debug an
issue that a customer saw. But, dang it! The feature works perfectly for you! Is
the customer wrong? Or is there something unique to their account? We'll never know!
Time to find a different career! The end is nigh!

Suddenly, a super-hero swoops in to save the day! This hero's name? switch_user.

In `security.yaml`, under your firewall, activate our hero with a new key: `switch_user`
set to `true`:

[[[ code('07f725d4a3') ]]]

As *soon* as you do this, you can go to *any* URL and add `?_switch_user=` and the email
address of a user that you want to impersonate. Let's try `spacebar1@example.com`.

And... access denied! Of course! To prevent *any* user from taking advantage of
this little trick, the `switch_user` feature requires you to have a special role
called `ROLE_ALLOWED_TO_SWITCH`. Go back to `security.yaml` and give `ROLE_ADMIN`
users this new role under `role_hierarchy`:

[[[ code('f52d99074c') ]]]

Ok, watch closely: we still have the magic `?_switch_user=` in the URL. Hit enter.
That's gone, yea! I'm logged in as `spacebar1@example.com`! You can see
this down in the web debug toolbar. Of course, this normal user can't access this
page. But if you go back to the homepage, you can surf around as the `spacebar1`
user.

## User Provider & _switch_user

Oh, by the way, the reason that we use the `email` address with `_switch_user`, and
not some other field like the `id`, is due to the user provider. Remember, this is
the code inside Symfony that helps reload the user from the session at the beginning
of each request. But it is *also* used by a few other features to load the user,
like `remember_me` and `switch_user`. If you're using the Doctrine user provider
like we are, then this `property` key determines which field will be used for all
of this:

[[[ code('50b7837830') ]]]

If you changed this to `id`, we would need to use the `id` with switch user.

## Adding a Banner when you are Impersonating

Anyways, to *exit* and return to your normal identity, find a phone booth, close
the door, and add `?_switch_user=_exit` to any URL. And... we're back to being us!

Switch one more time back to `spacebar1@example.com`. One of the *only* issues with
`_switch_user` is that it's not super obvious that we're switched! Yep, you might
switch to a user, go check Facebook, then come back, forget that you're *still* switched
to them, and start commenting on their behalf. What? No, I've definitely never
done this... I'm just saying it's *possible*.

To prevent these... awkward situations, let's put a big banner on top when we're
switched. Open `base.html.twig` and find the `body` tag. Here's the key: *when*
we are switched to another user, Symfony gives us a special role called
`ROLE_PREVIOUS_ADMIN`. We can use that to our advantage: if
`is_granted('ROLE_PREVIOUS_ADMIN')`, then print an alert block. Inside, say:

> You are currently switched to this user

[[[ code('c9004c41c2') ]]]

And, to maximize our fanciness, let's add a link to exit. Use the `path` function
to point to `app_homepage`. For the second argument, pass an array with the
necessary `_switch_user` set to `_exit`. At the end, say "Exit Impersonation":

[[[ code('3f1321367c') ]]]

## Adding Query Parameters with path()

Let's see how it looks! Move over and refresh! Nice! Even *I* won't forget when
I'm impersonating. And, check out the URL on the link: it's perfect - `?_switch_user=_exit`.
But... wait... the way we just used the `path()` function was a bit weird.

Why? Open `templates/article/homepage.html.twig` and find the article list. You
might remember that the second argument of the `path()` function is *normally* used
to fill in the  "wild card" values for a route:

[[[ code('11724a47a0') ]]]

Hold `Command` or `Control` and click `article_show`. Yep! This route has a `{slug}`
wild card:

[[[ code('31932f1196') ]]]

And so, when we link to it, we need to pass a value for that `slug` wildcard via
the 2nd argument to `path()`.

We *already* knew that. And *this* is the *normal* purpose of the second argument
to `path()`. However, *if* you pass a key to the second argument, and that route
does *not* have a wildcard with that name, Symfony just adds it as a query parameter.

*That* is why we can click this link to exit impersonation.

Next - let's build an API endpoint with Symfony's serializer! That will be our
*first* step towards API authentication.
