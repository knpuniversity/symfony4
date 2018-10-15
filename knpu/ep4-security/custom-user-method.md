# Custom User Method

Our fancy new account page is complete! Oh, except for that missing Twitter username
part - aliens freakin' *love* Twitter. The problem is that we don't have this field
in our `User` class yet. No problem, find your terminal and run:

```terminal
php bin/console make:entity
```

to update the `User` entity. Add `twitterUsername`... and make this nullable in
the database: this is an optional field:

[[[ code('32399c7b24') ]]]

Cool! Now run:

```terminal
php bin/console make:migration
```

Let's go check that out: look in the `Migrations/` directory and open the new
file:

[[[ code('60e63b7ef2') ]]]

And... yep! It looks perfect. Move back to your terminal one more time and run:

```terminal
php bin/console doctrine:migrations:migrate
```

Excellent! Now that we have the new field, let's *set* it on our dummy users in the
database. Open `UserFixture`. Inside the first set of users, add if
`$this->faker->boolean`, then `$user->setTwitterUsername($this->faker->userName)`:

[[[ code('de6250b15b') ]]]

The `$faker->boolean` is cool: it will return `true` or `false` randomly. So, about
*half* of our users will have a twitter username.

Go reload! Run:

```terminal
php bin/console doctrine:fixtures:load
```

Finally! Let's get to work in `account/index.html.twig`. Replace the `?` marks with
`app.user.twitterUsername`:

[[[ code('3200b0614b') ]]]

Hmm, but we probably don't want to show this block if they don't have a `twitterUsername`.
No problem: surround this with an `if` statement:

[[[ code('584320e766') ]]]

Perfect! Ok, let's go find a user that has their `twitterUsername` set! Run:

```terminal
php bin/console doctrine:query:sql "SELECT * FROM user"
```

Scroll up and... cool: `spacebar1@example.com`. Move back to your browser and
refresh. Oh! We got logged out! That's because the `id` of the user that we *were*
logged in as was removed from the database when we reloaded the fixtures.

Login as `spacebar1@example.com`, password `engage`. Click sign in and... got it!

## Custom User Method for RoboHash

Oh, and there's one other thing that we can *finally* update! See the user avatar on the
drop-down? That's *totally* hardcoded. Let's roboticize that! Yea, roboticize apparently
*is* a real word.

Copy the `src` for the RoboHash:

[[[ code('c9ab630f99') ]]]

Then, open up `base.html.twig` and, instead of pointing to the astronaut's profile
image, paste it!

[[[ code('b4621c6931') ]]]

Try it! Move over and... refresh!

Nice! But, hmm... there is one small thing that I don't like. Right click on the
image, copy the image address and paste in a new tab. Oh. That's a pretty big image:
300x300. It's not a huge deal, but our users are downloading a *pretty* big image,
*just* to display this teenie-tiny thumbnail.

Fortunately, the fine people who created RoboHash added a feature to help us! By
adding `?size=100x100`, we can get a smaller image. Let's do that on the menu.

But, wait! Instead of just putting `?size=` right here... let's get organized!
I don't like duplicating the RoboHash link everywhere. Open your `User` class.
Let's add a new custom function called `public function getAvatarUrl()`.

We don't actually have an `avatarUrl` property... but that's ok! Give this an `int`
argument that's optional and the method will return a `string`:

[[[ code('459cca80cf') ]]]

Inside, set `$url = ` and paste the RoboHash link. Remove the email but add
`$this->getEmail()`:

[[[ code('d6682c08b8') ]]]

Easy enough! For the size part, if a `$size` is passed in, use `$url .= `
to add `sprintf('?size=%dx%d')`, passing `$size` for both of these wildcards.
At the bottom, `return $url`:

[[[ code('f167708669') ]]]

Now that we're done with our fancy new function, go into `index.html.twig`, remove
the long string, and just print `app.user.avatarUrl`:

[[[ code('7759b3735c') ]]]

We can reference `avatarUrl` like a property, but behind the scenes, we know that
Twig is smart enough to call the `getAvatarUrl()` method.

Copy that, go back into `base.html.twig` and paste. But this time, call it like
a function: pass 100:

[[[ code('3a6a7848d8') ]]]

Let's see if it works! Close a tab then, refresh! Yep! And if we copy the image address
again and load it... nice! A little bit smaller.

Next, let's find out how to fetch the user object from the *one* spot we haven't
talked about yet: services.
