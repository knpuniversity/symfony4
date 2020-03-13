# Migrate Password Hashing

On our `User` entity, this `$password` field - which is stored in the database -
does *not* contain a plain-text version of the user's password:

[[[ code('d5df8a0aab') ]]]

Next to allowing SQL injection attacks, storing plain-text passwords is *just*
about the worst thing you can do in a web app.

## Hashing Algorithms Over Time

Anyways, what's *actually* stored on this field is a "hash" or kind of "fingerprint"
of the plaintext password and there are multiple hashing algorithms available.
The one *you're* using is configured in `config/packages/security.yaml`:

[[[ code('b91b201db8') ]]]

The `encoders` section says that whenever we encode, or really, "hash" a password -
like when someone registers or when they log in - the `bcrypt` algorithm will be
used. That's great. But... over time, as processing power of computers get better
and better, it becomes more and more possible that *if* your database of passwords
somehow got exposed, someone could use a computer to *crack* them. It probably
*won't* happen, but it's a security best-practice to change your algorithm over
time to one that requires more processing power or memory.

## Changing Algorithms

Comment-out the `bcrypt` algorithm and replace it with `sodium`:

[[[ code('05b0779a22') ]]]

This stuff can be confusing. Sodium is a hashing library that uses the
Argon2 algorithm, which is *currently* considered the best algorithm.

So... great! We just changed from `bcrypt` to Argon2 and increased the security
of our application. We deserve a donut!

Wait a second... put that donut down. You - usually - can't simply change from
one algorithm to another. Why? The problem is that all your *existing* users
already have their passwords hashed with `bcrypt`. If *those* users tried to log in,
suddenly Symfony would use `sodium` to hash the submitted password and it would
not match the hash in the database.

Now, the *full* truth is that, in *this* case - going from `bcrypt` to `sodium` -
nothing would break: Sodium is smart enough to detect that the existing passwords
are hashed with `bcrypt` and use it instead. But in general, you can't change from
one algorithm to another without breaking stuff. And even in this case, shouldn't
we *also* re-hash the passwords of all our existing users with the newer algorithm?

## The migrate_from Encoder Option

Symfony 4.4 comes with a wonderful new feature to help with this - submitted by
the amazing [Nicolas Grekas](https://github.com/nicolas-grekas), who is also
responsible - along with [Jérémy Derussé](https://github.com/jderusse)
for the secrets management system.

Here's how it works: add a new encoder, it can be called anything, how about
`legacy_bcrypt`. Make sure it has the *exact* configuration of your original
encoder:

[[[ code('309f86a182') ]]]

Next, under the *new* encoder - the one that will be used for my `User` class -
add a new option: `migrate_from`. Below that, add a list of all encoders that
existing users might be using - for us, just `legacy_bcrypt`:

[[[ code('14b472f3d1') ]]]

That's it! This says:

> Hey! When somebody logs in, try to use the `sodium` algorithm. If that doesn't
> work, try the `legacy_bcrypt` algorithm. If *that* doesn't work, panic! I mean,
> if that doesn't work, the password is invalid.

Thanks to this, we can have a database where *some* passwords are hashed with
`sodium` and others are hashed with `bcrypt`. Let's try it: log out and try to log
back in: `admin1@thespacebar.com`, password `engage`. Got it!

## Seeing the Hashed Passwords

It's *also* kinda fun to see how this looks in the database. Find your terminal
and run:

```terminal
php bin/console doctrine:query:sql 'SELECT email, password FROM user'
```

Interesting: every hashed password starts with the same `$2y` thing. That's no
accident: that's what the bcrypt hashing format looks like.

Let's see what sodium-encoded passwords look like: go back to your browser, log
out, and register as a new user: `Ryan`, `spacecadet@example.com`, the same
password - `engage`, but that doesn't matter - and register!

Try that query again:

```terminal-silent
php bin/console doctrine:query:sql 'SELECT email, password FROM user'
```

Cool! It's *pretty* obvious the new user's password is hashed with Argon.

## Upgrading old Password

We now have a database mixed with passwords hashed with the older algorithm and
the newer algorithm. That's fine... but in a *perfect* world, we would re-hash
*all* the passwords using the newer algorithm.

But... we can't do that. Boo. In order to hash a password, we need the original
*plain* password, which we don't have. So it's *not* possible to upgrade all
existing users to the new algorithm.

Except, hmm, there is *one* time when we *do* have the plaintext password: at
the moment any old user logs into the site. At that *instant*, in theory, we
*could* re-hash the password using sodium and save it to the database. That
would actually be pretty awesome.

And... that's *precisely* what  `migrate_from` does automatically:

[[[ code('a48996e512') ]]]

Well, *almost* automatically: we need to do two things in our code to enable it.

## Guard PasswordAuthenticatedInterface

First, *if* you're using Guard authentication for your login form, your
authenticator needs a new interface. I'll open up
`src/Security/LoginFormAuthenticator.php` and add
`implements PasswordAuthenticatedInterface`:

[[[ code('32a9a48bcf') ]]]

Basically, we need to tell the system what the plain-text password is. I'll scroll
down and then go to the "Code"->"Generator" menu - or `Command`+`N` on a Mac - to
generate the required `getPassword()` method:

[[[ code('00d9d9f13d') ]]]

Look up at `getCredentials()`:

[[[ code('b6bba095da') ]]]

We return an array with the `email`, `password`, and `csrf_token` keys.
In `getPassword()`, we're passed that array as the `$credentials` argument.
To get the password, `return $credentials['password']`:

[[[ code('6e1df6d1ae') ]]]

## UserRepository PasswordUpgraderInterface

The *second* change we need to make is inside `src/Repository/UserRepository.php`.
Implement a new interface here too called `PasswordUpgraderInterface`:

[[[ code('ef527958d9') ]]]

This requires one new method. Go to the "Code"->"Generate" menu - or `Command`+`N`
on a Mac - select "Implement Methods" and choose `upgradePassword()`:

[[[ code('196821f13c') ]]]

Here's the idea: when we log in, *if* the user's password is hashed with an old
algorithm, the security system will call `getPassword()` on our authenticator
to get the plain-text password and then hash it using the latest algorithm.
To save that newly-hashed string to the `user` table, it will call this
`upgradePassword()` method and pass it to us.

So, our job here is to update the database. I'll add a little PHPDoc above this
method: we know the `$user` variable will be *our* `User` object:

[[[ code('b0fb1c907d') ]]]

Now add `$user->setPassword($newEncodedPassword)` and then
`$this->getEntityManager()->flush($user)`:

[[[ code('be3c0ba982') ]]]

That's it! Test drive time! Find your browser and log out. Log back in with
`admin1@thespacebar.com`, password `engage`. It works. But the *real* test is what
the database looks like! Run that query again:

```terminal-silent
php bin/console doctrine:query:sql 'SELECT email, password FROM user'
```

Scroll up and... there it is! `admin0` still has the `bcrypt` format but `admin1` -
the user *we* just logged in as - has an argon-hashed password!

So that's it! By adding a few lines of config and two simple methods, our existing
users will be upgraded to the latest algorithm safely over time. And we can brag
about this cool feature to our friends.

Next, we're *just* about done with our tour through my favorite new Symfony 5
features. But before we're done, I want to talk about PHP 7.4 preloading *and*
a way to double-check that service wiring across your *entire* app is working
correctly. Because, surprise! We have a hidden bug.
