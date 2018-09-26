# Adding & Checking the User's Password

Until now, we've allowed users to login without *any* password. As *much* fun as
it would be to deploy this to production... I think we should *probably* fix that.
If you look at your `User` class, our users actually don't have a password field
at *all*:

[[[ code('f0bfc0554e') ]]]

When you originally use the `make:user` command, you *can* tell it to create
this field for you. We told it to *not* do this... just to keep things simpler
as we were learning. So, we'll do it now.

## Adding the password Field to User

Find your terminal and run:

```terminal
php bin/console make:entity
```

Update the `User` class to add a new field called `password`. Make it a string
with length 255. It doesn't need to be *quite* that long, but that's fine. Can
it be null? Say no: in our system, each user will *always* have a password.

And... done! It updated the `User.php` file, but it did *not* generate the normal
`getPassword()` method because we already had that method before. We'll check that
out in a minute.

Before that, run:

```terminal
php bin/console make:migration
```

Move over and check out the `Migrations` directory. Open the new file and... yes!
It looks perfect: `ALTER TABLE user ADD password`:

[[[ code('1c1676c2ae') ]]]

Close that, go back to your terminal, and migrate:

```terminal
php bin/console doctrine:migrations:migrate
```

Awesome!

## Updating the User Class

Go open the `User` class. Yep - we *now* have a password field:

[[[ code('0fca63257f') ]]]

And *all* the way at the bottom, a `setPassword()` method:

[[[ code('9379c2924f') ]]]

Scroll up to find `getPassword()`:

[[[ code('fb34b06ebd') ]]]

This already existed from back when our user had no password. Now that it does, return
`$this->password`:

[[[ code('18918ccffd') ]]]

Oh, and just to be clear, this password will *not* be a plain-text password. No,
no, no! The string that we store in the database will always be properly salted
& encoded. In fact, look at the method below this: `getSalt()`:

[[[ code('b79d7f1af7') ]]]

In reality, there are *two* things you need to store in the database: the encoded
password and the random *salt* value that was *used* to encode the password.

But, great news! Most modern encoders - including the one we will use - store the
salt value as *part* of the encoded password string. In other words, we *only* need
this one field. *And*, the `getSalt()` method can stay blank. I'll update the
comment to explain why:

[[[ code('37f6860709') ]]]

I love doing no work!

## Configuring the Encoder

Symfony will take care of *all* of this password encoding stuff *for* us. Nice!
We just need to tell it which encoder algorithm to use. Go back to `security.yaml`.
Add one new key: `encoders`. Below that, put the class name for your `User` class:
`App\Entity\User`. And below *that*, set `algorithm` to `bcrypt`:

[[[ code('a4b78cdbb5') ]]]

There are at least two good algorithm options here: `bcrypt` and `argon2i`. The
`argon2i` encoder is actually a bit more secure. But, it's only available on PHP 7.2
or by installing an extension called Sodium. 

If you and your production server have this available, awesome! Use it. If not, use
`bcrypt`. Just know that once you start encoding passwords, *changing* algorithms in
the future is a *pain*.

Oh, and for both encoders, there is one other option you can configure: `cost`.
A higher `cost` makes passwords harder to crack... but will take more CPU. If
security is really important for your app, check out this setting.

*Anyways*, thanks to this config, Symfony can *now* encrypt plaintext passwords
*and* check whether a submitted password is valid.

## Encoding Passwords

Open the `UserFixture` class because *first*, we need to populate the new `password`
field in the database for our dummy users.

To encode a password - surprise! - Symfony has a service! Find your terminal and
run our favorite:

```terminal
php bin/console debug:autowiring
```

Search for "password". There it is! `UserPasswordEncoderInterface`. This service
can encode *and* check passwords. Back in `UserFixture`, add a constructor with one
argument: `UserPasswordEncoderInterface`. I'll re-type the "e" and hit tab to
autocomplete and get the `use` statement I need on top. Call it `$passwordEncoder`:

[[[ code('9e84bbdf15') ]]]

Press `Alt`+`Enter` and select initialize fields to create that property and set it:

[[[ code('45733dd8b8') ]]]

Now... the fun part: `$user->setPassword()`. But, instead of setting the plain password
here - which would be *super* uncool... - say `$this->passwordEncoder->encodePassword()`:

[[[ code('02216038fa') ]]]

This needs two arguments: the `$user` object and the plain-text password we want
to use. To make life easier for my brain, we'll use the same for everyone: `engage`:

[[[ code('f717637c74') ]]]

That's it! The reason we need to pass the `User` object as the first argument is
so that the password encoder knows which encoder algorithm to use. Let's try it:
find your terminal and reload the fixtures:

```terminal
php bin/console doctrine:fixtures:load
```

You might notice that this is a bit slower now. By design, password encoding is
CPU-intensive. Ok, check out the database!

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM user'
```

Awesome! Beautiful, encoded passwords. The bcrypt algorithm generated a unique
salt for each user, which lives right inside this string.

## Checking the Password

Ok, just *one* more step - and it's an easy one! We need to *check* the submitted
password in `LoginFormAuthenticator`. *This* is the job of `checkCredentials()`:

[[[ code('6d65ab8fe8') ]]]

We already know which service can do this. Add one more argument to your constructor:
`UserPasswordEncoderInterface $passwordEncoder`. Hit `Alt`+`Enter` to initialize that
field:

[[[ code('765b287450') ]]]

Then down in `checkCredentials()`, return `$this->passwordEncoder->isPasswordValid()`
and pass this the `User` object and the raw, submitted password... which we're storing
inside the `password` key of `$credentials`:

[[[ code('91cb3e411e') ]]]

And.. we're done! Time to celebrate by trying it! Move over, but this time put "foo"
as a password. Login fails! Try `engage`. Yes!

Next: it's finally time to start talking about how we deny access to certain parts
of our app. We'll start off that topic with a fun feature called `access_control`.
