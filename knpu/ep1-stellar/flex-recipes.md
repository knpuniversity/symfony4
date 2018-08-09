# Symfony Flex & Aliases

It's time to demystify something *incredible*: tractor beams. Well actually, we
haven't figured those out yet... so let's demystify something *else*, something
that's already been happening behind the scenes. First commit everything, with a
nice message:

***TIP
Wait! Run `git init` first before `git add .`: Symfony no longer creates a Git repo
automatically for you :)
***

```terminal-silent
git init
git add .
git commit -m "making so much good progress"
```

## Installing the Security Checker

Let's install a new feature called the Symfony Security Checker. This is a *great*
tool.... but... full disclosure: we're *mostly* installing it to show of the *recipe*
system. Ooooo. Run:

```terminal
git status
```

Ok, there are *no* changes. Now run:

```terminal
composer require sec-checker
```

***TIP
This package will only be used while developing. So, it would be even better
to run `composer require sec-checker --dev`.
***

## Hello Symfony Flex

Once again, `sec-checker` should *not* be a valid package name! So what's going
on? Move over and open `composer.json`:

[[[ code('5cb39f8b41') ]]]

Our project began with just a *few* dependencies. One of them was `symfony/flex`:
this is *super* important. Flex is a Composer plugin with two superpowers.

## Flex Aliases

The first superpower is the *alias* system. Find your browser and go to
[symfony.sh][symfony_sh].

This is the Symfony "recipe" server: we'll talk about what that means next. Search
for "security". Ah, here's a package called `sensiolabs/security-checker`. And
below, it has aliases: `sec-check`, `sec-checker`, `security-check` and more.

Thanks to Flex, we can say `composer require sec-checker`, or *any* of these aliases,
and it will translate that into the real package name. Yep, it's just a shortcut
system. But the result is *really* cool. Need a logger? `composer require logger`.
Need to send emails? `composer require mailer`. Need a tractor beam? `composer require`,
wait, no, we can't help with that one.

Back in `composer.json`, yep! Composer *actually* added `sensiolabs/security-checker`:

[[[ code('883ddc1e9c') ]]]

That's the *first* superpower of Flex.

## Flex Recipes

The *second* superpower is even better: recipes. Mmmm. Go back to your terminal and...
yes! It *did* install and, check this out: "Symfony operations: 1 recipe". Then,
"Configuring `sensiolabs/security-checker`".

What does that mean? Run:

```terminal
git status
```

Woh! We *expected* `composer.json` and `composer.lock` to be updated. But there
are *also* changes to a `symfony.lock` file and we suddenly have a *brand new*
config file!

First, `symfony.lock`: this file is managed by Flex. It keeps track of which recipes
have been installed. Basically... commit it to git, but don't worry about it.

The second file is `config/packages/dev/security_checker.yaml`:

[[[ code('3a567d7003') ]]]

This was added by the recipe and, cool! It adds a new `bin/console` command to our app!
Don't worry about the code itself: you'll understand and be writing code like this
soon enough!

The point is this: thanks to this file, we can now run:

```terminal
php bin/console security:check
```

Cool! This is the recipe system in action! Whenever you install a package, Flex
will execute the *recipe* for that package, if there is one. Recipes can add configuration
files, create directories, or even modify files like `.gitignore` so that the library
*instantly* works without *any* extra setup. I *love* Flex.

By the way, the *purpose* of the security checker is that it checks to see if there
are any known vulnerabilities for packages used in our project. Right now, we're good!

But the recipe made one *other* change. Run:

```terminal
git diff composer.json
```

Of course, `composer require` added the package. But the *recipe* added a new *script*!

[[[ code('b4ef3d7af5') ]]]

Thanks to that, whenever we run:

```terminal
composer install
```

when it finishes, it runs the security checker automatically. So cool!

Oh, and I won't show it right now, but Flex is even smart enough to *uninstall*
the recipes when you *remove* a package. That makes testing out new packages fast
and easy.

## The Recipes Repository

So you might be wondering... where do these recipes live? Great question! They live...
in the *cloud*. I mean, they live on GitHub. On [symfony.sh][symfony_sh], click "Recipe"
next to the Security checker. Ah, it takes us to the `symfony/recipes` repository.
Here, you can see what files will be added and a few other changes described in
`manifest.json`.

All recipes either live in this repository, or another one called `symfony/recipes-contrib`.
There's no important difference between the two repositories: but the official recipes
are watched more closely for quality.

Next! Let's put the recipe system to work by installing Twig so we can create proper
templates.


[symfony_sh]: https://symfony.sh
