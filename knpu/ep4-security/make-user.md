# Security & the User Class

Yeaaaa! You've done it! You've made it to the tutorial where *we* get to build
a security system with Symfony. This stuff is *cool*. Seriously, these days, the
topic of security is gigantic! Just think about authentication: you might need to
build a traditional login form, or a token-based API authentication system, or
two-factor authentication or authentication across an API to a Single Sign-On
server or something I've never even dreamed of before! For authorization, there
are roles, access controls and more.

Woh. So we're going to write some *seriously* fun code in this tutorial. And it will
be *especially* fun, because there are some new cool toys in Symfony's security
system that make it nicer than ever to work with.

## Coding Along!

As always, to become a *true* Symfony security geek... and to obtain the blueprint
to the Deathstar, you should *definitely* code along with me. Download the course code
from this page. When you unzip it, you'll find a `start/` directory that has the
same code that you see here. Follow the `README.md` file for all the important
setup details.

Oh, and if you've been coding along with me in the Symfony series so far, um, you're
amazing! But also, be sure to download the *new* code: I made a few changes since
the last tutorial, including upgrading to Symfony 4.1 and improving our fixture system.
More on that later.

Anyways, the *last* setup step will be to open a terminal, move into the project
and run:

```terminal
php bin/console server:run
```

to start the built in web server. Ok: head back to your browser and open our app
by going to http://localhost:8000.

Hello The SpaceBar! Our awesome intergalactic *real* news site that helps connect
alien species across this side of the Milky Way.

## Installing Security & Upgrading MakerBundle

Our *first* goal in this tutorial is to create an *authentication* system. In other
words: a way for the user to login. No matter *how* you want your users to authenticate -
whether it's a login form, API authentication or something crazier - the first step
is always the same: brew some coffee or tea. The *second* step is *also* always the
same: create a `User` class.

To do this, we're going to use a brand-spanking new feature! Woo! Find your terminal
and run:

```terminal
composer update symfony/maker-bundle
```

Version 1.7 of MakerBundle comes with a new command that will make our life *much*
easier. Yep, there it is: 1.7. The new command is called `make:user` - try it:

```terminal
php bin/console make:user
```

Ah! It explodes! Of course! Remember: in Symfony 4, our project starts *small*.
If you need a feature, you need to install it. Run:

```terminal
composer require security
```

Ah, check it out: this library has a recipe! When Composer finishes... find out
what it did by running:

```terminal
git status
```

A new config file! Check it out: `config/packages/security.yaml`. This file is *super*
important. We'll start talking about it soon.

## Creating the User Class with make:user

Before we run `make:user` again, add all the changed files to git and commit
with a message about upgrading MakerBundle & adding security:

```terminal-silent
git add .
git commit -m "Upgraded MakerBundle and added security"
```

I'm doing this because I want to see *exactly* what the `make:user` command does.

Ok already, let's try it!

```terminal
php bin/console make:user
```

Call the class `User`. Second question:

> Do you want to store user data in the database

For most apps, this is an easy yes... because most apps store user data in a local
database table. But, what if your user data is stored on some *other* server,
like an LDAP server or a single sign-on server? Well, *even* in those cases, if
you want to store *any* extra information about your users in a local database
table, you should still answer yes. Answer "no" *only* if you don't need to store
*any* user information to your database.

So, "yes" for us! Next: choose one property on your user that will be its unique
display name. This can be anything - it's usually an `email` or `username`. We'll
talk about how it's used later. Choose `email`.

And, the last question: is our app responsible for checking the user's password?
In some apps - like a pure API with only token authentication, users might not even
*have* a password. And even if your users *will* be able to login with a password,
only answer yes if *this* app will be responsible for directly checking the user's
password. If you actually send the password to a third-party server and *it* checks
if it's valid, choose no.

Remember when I mentioned how complex & different modern authentication systems
can be? That's why this command exists: to help walk us through *exactly* want we
need.

I'm going to choose "No" for now. We *will* add a password later, but we'll keep
things extra simple to start.

And... we're done! Awesome! This created a `User` entity, a Doctrine `UserRepository`
for it, and updated the `security.yaml` file.

Let's check out these changes next!
