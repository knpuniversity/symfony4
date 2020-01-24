# Migrate Password Hashing

Coming soon...

We all know that on our `User` entity, this `$password` field is a hash to pastor because
we never want to store plain text passwords. There are multiple algorithms that you
can use to hash passwords and this is controlled in `config/packages/security.yaml`
So this section up here says that whenever someone registers, when you're
going to use it and `bcrypt` to hash their password and when they log in, we will
use `bcrypt` to compare the passwords as a security best practice over time, better
and better algorithms come out. And as a security best practice, you typically want
to make sure that you're always using the latest algorithm. Otherwise, if somehow
your hash passwords got exposed, it's possible that someone could crack them. It's
probably not going to happen to your application. Um, but if we can use the latest
algorithm, like why wouldn't we?

So right now I want to change `bcrypt`, I'm gonna come out the `bcrypt` algorithm and
change it to `sodium`, which is a algorithm that is generally considered to be a little
bit more robust than `bcrypt`. So done. Right. Well, the problem is that you can't
just change from one algorithm to another one usually. Normally that would mean that
any existing users whose passwords are in are hashed in the database. Using
`bcrypt` wouldn't be able to log in because Symfony would now be expecting those
to be hashed with `sodium`. Now in the case of `bcrypt` and `sodium`, they're actually
compatible with each other. So you would still be able to login with many algorithms.
You can't change from one to another, one without, uh, existing users being affected
badly.

In Symfony 4.4 there's a new wonderful way to handle this where you can have great
security without any work. Here's how it works. Add a new encoder up here. It can be
called anything. I'll call it `legacy_bcrypt` and I'm going to copy whatever config
I had on my original encoder. I'm not copying down here now to my real
encoder, the one that will be used for app and the user. I'll add a `migrate_from`. And
then down here I'll just list `legacy_bcrypt`. And if you had multiple old and
coders, you could put that one below. This basically says when somebody logs in, try
to use the `sodium` algorithm. If that doesn't work, try to use the `legacy_bcrypt` and
coder. So now we could have, so now we can have multiple uh, hash. It's in the
database. I'm actually going to log out and let's make sure this works. I'll log in
as `admin1@thespacebar.com` password `engage` and it works. All right, so checkouts
spit over to your terminal and run 

```terminal
php bin/console doctrine:query:sql 'SELECT email, password FROM user'
```

So as you can see, all the passwords start with this dollar sign
to Y thing. This is the `bcrypt` format and it shouldn't be a surprise. All these
users were created when `bcrypt` was the algorithm we were using in our application.
So now I'm going to go back over to my browser, log out and register a new user.

Well registers, `Ryan` `spacecadet@example.com` and I'll use the same password,
`engage`, but that doesn't matter and I'll register. Okay, go back and run that query
again. 

```terminal-silent
php bin/console doctrine:query:sql 'SELECT email, password FROM user'
```

Awesome. So you can see that the new user uses the new `argon` algorithm. So the
old users use the old algorithm, which isn't as good and any new users use the new
algorithm. So this is great, but it would be even better if we could upgrade the old
password out, uh, hashes to the, to use the new, uh, `sodium`.

Owl, uh, Hasher due to the nature of hash and these passwords, we can't, we can't
decrypt them. We can't do that unless we had the original plain text password for all
of these old users, which obviously we can't get. So it's not possible to just
upgrade all of your existing users to use the new algorithm. Except there is one time
when we do have the plaintext password. At the moment, any of these old users log in,
we have the plain text password and we could add that moment, use the new hash
algorithm and, and update the database using it. In fact, that's exactly what 
`migrate_from` does automatically. We just need to do two extra things in our code to enable it
to finish its work. The first one is because we're using the guard off vacation, I'm
going to put up `Security/LoginFormAuthenticator`. This is the authenticator that
handles the log in form and we need to implement a new interface here. So I'll say
`implements PasswordAuthenticatedInterface`. Basically we need to tell the system
what the plain text password is. So I'll go down here and I'll go to command generate
or Command + N on a Mac and `getPassword`. And if you look up here and 
`getCredentials()`, we return an array with the `email`, `password`, `csrf_token` down here.
We're past that same `$credentials`. So we'll `return $credentials['password']`

The other thing we need to do is, because we're using doctrine, go into the
`Repository/UserRepository` and implement a new interface here as well called
`PasswordUpgraderInterface`. This will also require one new method. So I'll put on
here, go to code generate or Command + N on a Mac, "Implement Methods" per 
`upgradePassword()`. So the idea is when we login,

the security system will call and `getPassword()` to get the plain text password and
then it will encode it using the new encoder and pass it to this `upgradePassword()`
method. In this method, we need to set that on the `User` object and save it to the
database. By the way, this rehashing is only done when it needed. It's only done one
Symfony did techs that the user is using the old algorithm and use it. The new ones.
There is no extra performance hit for normal users. So actually let me add a little
PHP doc up above this. It says that the `$user` variable is actually going to be a `User`
object because that's, we know that will be our `User` object and then I can say
`$user->setPassword($newEncodedPassword)`. And then `$this->getEntityManager()->flush($user)`

All right, let's check this out. So I'm gonna go back over here. Let's log out.

Long back in, we use my `admin1@spacebar.com` pass for an `engage` sign in.
It works. And here's the real test. Spin over here and run our query again, 

```terminal-silent
php bin/console doctrine:query:sql 'SELECT email, password FROM user'
```

and let's
scroll all the way up. Let's see here. Avenues or is, there we go. Boom. Okay. Admin
zero still has the old `bcrypt`, but admin one, the person who just logged in is now
using our `argon`. So with a little bit of config and those two extra methods, you can
now guarantee that new users, users will use the new algorithm. All of you as users
will be able to log in and old users will have their hashed password upgraded over
time. So nice and secure without any headaches.