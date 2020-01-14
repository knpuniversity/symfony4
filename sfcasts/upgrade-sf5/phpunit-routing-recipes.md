# phpunit-bridge & routing Recipes

Let's see what recipes we have left:

```terminal
composer recipes
```

## Updating symfony/phpunit-bridge

Next up is `phpunit-bridge`. Copy its name and run:

```terminal
composer recipes:install symfony/phpunit-bridge --force -v
```

It says it created - probably *updated* - 5 files. You know the process:

```terminal
git add -p
```

## Updating .env.test

The first is `.env.test` with two changes. This fix is a silly typo on the
`APP_SECRET` variable - that's not important - and the second is a new
`PANTHER_APP_ENV` variable. If you're using Symfony Panther - a cool testing tool -
then this variable is used to tell Panther which environment to launch. If you're
not, then you don't technically need this... but it doesn't hurt anything.
You also might install Panther in the future.

As a challenge, let's see if we can *just* add the *second* change. To do that,
type "s", which means "split". The tool will then *try* to ask you about each
change independently. Say "n" to the first and "y" to the second.

## .phpunit.result.cache inside .gitignore

The next update is inside .gitignore: it ignores a new `.phpunit.result.cache`
file. This is why I *love* updating recipes. Since version 7.3, PHPUnit outputs
this file under certain conditions as a way to remember which tests failed. It
should be ignored, and this takes care of that. Enter "y".

## Updating bin/phpunit

Woh! The next change looks bigger - this is `bin/phpunit`: a script that this
packages adds to help execute PHPUnit. It has a number of subtle updates...
and since you've *almost* definitely not made any custom tweaks to this file,
let's add the change.

## Updating phpunit.xml.dist

The *last* update is for `phpunit.xml.dist` - PHPUnit's configuration file. You
*may* have some customizations here that you want to keep - so be careful. The
recipe updates are tiny: these changed from `<env` to `<server` - a mostly
meaningless change to how environment variables are added - and it looks like
something about these two `SYMFONY_PHPUNIT` variables got tweaked a bit.

Hit "y" to accept this patch. The last change is for `symfony.lock` - hit "y"
for this one too.

## Updating symfony/routing

Done! The next recipe on the list is symfony/routing. Let's jump straight to
update it:

```terminal
composer recipes:install symfony/routing --force -v
```

And then get into:

```terminal
git add -p
```

Bah! Duh! I should have committed my changes *before* starting this and then
reverted the stuff we did *not* want - like this. We'll do that in a minute.
Hit "n" to ignore the `.env.test` change.

The first *real* change is in `config/packages/routing.yaml`: `strict_requirements`
is gone and `utf8: true` was added. If you dug into the recipe history, you could
find the reason behind both of these. `utf8` is a new feature in routing. By
setting this to true, you're activating that feature. We may not need it, but
I'm going to say yes to this.

The second change - `strict_requirements` - is thanks to a little reorganization of
the routing config files that, actually, *I* am responsible for. The short story
is that: you want this key to be set to a different value in different environments.
I moved some config around to get that done with less files.

Hit "y" to add these changes. And... yep! This is `symfony.lock`, so accept this
too. Phew! Let's see how things look:

```terminal
git status
```

Woh! A new `routing.yaml` file for the `prod` environment! If you open that -
`config/packages/prod/routing.yaml` - it has `strict_requirements: null`:

[[[ code('4e1dc0fbf4') ]]]

It's part of that reorganization I was just talking about. Add that change:

```terminal
git add config/packages/prod/routing.yaml
```

The *last* change - which we need to do manually - is to delete
`config/packages/test/routing.yaml`:

[[[ code('44734037d1') ]]]

It's *another* file with `strict_requirements` and it is *gone* from the new recipe.
Why? It's just not needed anymore: if you followed the logic, you'd find that
`strict_requirements` is already `true` in the `test` environment. Delete it:

```terminal
git rm config/packages/test/routing.yaml
```

Oh... I apparently modified that file? Whoops! Yep, I added a "g"! Not helpful.
Remove it and... delete the file:

```terminal-silent
git rm config/packages/test/routing.yaml
```

Let's see how things look:

```terminal
git status
```

A *lot* of progress from those two recipe updates. Let's commit:

```terminal
git commit -m "upgrading phpunit & routing recipes"
```

The *one* change left - that we decided we *didn't* care about - is in `.env.test`.
Revert it with:

```terminal
git checkout .env.test
```

Woo! Let's find out what recipes we have left:

```terminal
composer recipes
```

Woh! Only 3 main Symfony repositories left: `security-bundle`, `translation`
and `validator`. Let's do those next.
