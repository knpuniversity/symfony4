# ApiToken Entity

Time to get to work on our API token authentication system! As we just learned,
there are a bunch of different ways to do API auth. We're going to code through *one*
way, which will make you *plenty* dangerous for whatever way *you* ultimately need.

For our API tokens, we're going to create an `ApiToken` entity in the database to
store them. Find your terminal and run:

```terminal
php bin/console make:entity
```

Call the class `ApiToken`. And, we need a few fields: `token`, a string that's not
nullable, `expiresAt` so that we can set an expiration as a `datetime`, and `user`,
which will be a `relation` type to our `User` class. In this situation, we want a
ManyToOne relationship so that each `ApiToken` has one `User` and each `User` can
have many ApiTokens. Make this *not* nullable: every API token must be related to
a `User`. And, though it doesn't matter for authentication, let's map both sides
of the relationship. That will allow us to easily fetch all of the API tokens
for a specific user. For `orphanRemoval`, this is also not important, but choose
`yes`. If we create a page where a user can manage their API tokens, this might
make it easier to delete API tokens.

And... done!

[[[ code('a3217ad246') ]]]

Generate the migration with:

```terminal
php bin/console make:migration
```

Go check it out - in the `Migrations/` directory, open that file:

[[[ code('eadfda32a8') ]]]

Cool! `CREATE TABLE api_token` with `id`, `user_id`, `token` and `expires_at`.
And, it creates the foreign key.

That looks perfect. Move back and run it!

```terminal
php bin/console doctrine:migrations:migrate
```

## How are Tokens Created?

So, the question of *how* these ApiTokens will be created is *not* something we're
going to answer. As we talked about, it's either super easy... or super complicated,
depending on your needs. 

So, for our app, we're just going to create some ApiTokens via the fixtures.

## Making the ApiToken Class Awesome

But before we do that, open the new `ApiToken` entity class. Yep, all the usual
stuff: some properties, annotations and a getter & setter for each method. I want
to change things a bit. The `make:entity` command always generates getter and setter
methods. But, in some cases, there is a better way to design things.

Add a `public function __construct()` method with a `User` argument:

[[[ code('77f0148494') ]]]

Because ever `ApiToken` needs a `User`, why not make it required when the object
is instantiated? Oh, and we can *also* generate the random `token` string here. Use
`$this->token = bin2hex(random_bytes(60))`. Then `$this->user = $user`:

[[[ code('25a9fe5c12') ]]]

Oh, and we can also set the expires time here - `$this->expiresAt = new \DateTime()`
with `+1 hour`:

[[[ code('714b1646fd') ]]]

You can set the expiration time for however long you want.

Now that we are initializing everything in the constructor, we can clean up the class:
remove all the setter methods:

[[[ code('0ea2c3c387') ]]]

Yep, our token class is now *immutable*, which wins us *major* hipster points.
Immutable just means that, once it's instantiated, this object's data can never
be changed. Some developers think that making immutable objects like this is *super*
important. I don't fully agree with that. But, it *definitely* makes sense to be thoughtful
about your entity classes. Sometimes having setter methods makes sense. But sometimes,
it makes more sense to setup some things in the constructor and remove the setter methods
if you don't need them.

Oh, and if, in the future, you want to *update* the data in this entity - maybe you
need to change the `expiresAt`, it's totally OK to add a new public function to
allow that. But, when you do, again, be thoughtful. You *could* add a
`public function setExpiresAt()`. Or, if all you ever do is re-set the `expiresAt`
to one hour from now, you could instead create a `public function renewExpiresAt()`
that handles that logic for you:

```php
public function renewExpiresAt()
{
    $this->expiresAt = new \DateTime('+1 hour');
}
```

That method name is more meaningful, and centralizes more control inside the class.

Ok, I'm done with my rant!

## Adding ApiTokens to the Fixtures

Let's create some ApiTokens in the fixtures already! We *could* create a new
`ApiTokenFixture` class, but, to keep things simple, I'm going to put the logic
right inside `UserFixture`. 

Use `$apiToken1 = new ApiToken()` and pass our `User`. Copy that and create
`$apiToken2`:

[[[ code('b035875e84') ]]]

With our fancy `createMany()` method, you do *not* need to call `persist()` or `flush()`
on the object that you return. That's because our base class calls `persist()` on
the object *for* us:

[[[ code('9e3ae14efd') ]]]

But, if you create some objects manually - like this - you *do* need to call
`persist()`. No big deal: add `use ($manager)` to make the variable available in
the callback. Then,`$manager->persist($apiToken1)` and `$manager->persist($apiToken2)`:

[[[ code('b38a374c62') ]]]

That should be it! Let's reload some fixtures!

```terminal
php bin/console doctrine:fixtures:load
```

When it's done, run:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM api_token'
```

Beautiful, long, random strings. And *each* is related to a User.

Next, let's create an authenticator that's capable of reading, processing &
authenticating these API tokens.
