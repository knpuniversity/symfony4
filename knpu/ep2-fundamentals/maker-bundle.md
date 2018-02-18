# MakerBundle

For our last trick, I want to introduce a bundle that's going to make our life
*awesome*. And, for the first time, *we* are going to hook *into* Symfony.

## Installing Maker Bundle

First, find your terminal, and install that bundle:

```terminal
composer require maker --dev
```

Yep! That's a Flex alias for `symfony/maker-bundle`. And, in this case, "make"
means - "make your life easier by *generating* code".

*We* know that the *main* purpose of a bundle is to give us *services*. And,
that's true in this case too... but the *purpose* of these services isn't for us
to use them directly, like in our controller. Nope, the purpose of these services
is that they give us new `bin/console` commands:

```terminal-silent
php bin/console
```

Nice! About 10 new commands, capable of generating all *kinds* of things. And, more
make commands are still being added.

## Generating a new Command

So... let's try one! Let's use the MakerBundle to create our very *own*, custom
`bin/console` command. Use:

```terminal
php bin/console make:command
```

This will ask us for a command name - how about `article:stats` - we'll create a
command that will return some stats about an article. And... it's done! We now have
a shiny new `src/Command/ArticleStatsCommand.php` file. Open it!

[[[ code('83c66571c7') ]]]

Hey! It even added some example code to get us started! Run:

```terminal
php bin/console
```

And on top... yes! Symfony *already* sees our new `article:stats` command. Sweet!
Um... so... let's try it!

```terminal
php bin/console article:stats
```

It doesn't do much... yet - but it's already working.

## Service autoconfigure

But... how does Symfony *already* know about this new command? I mean, is it scanning
all of our files looking for command classes? Actually, no! And that's a good thing -
that would be *super* slow!

Here's the answer. Remember: all of our classes in `src/` are loaded as services:

[[[ code('928e28e89e') ]]]

Notice that our new class extends Symfony's base `Command` class:

[[[ code('73d948193d') ]]]

When the service was registered, Symfony *noticed* this and made sure that it
included it as a command. This nice feature has a name - `autoconfigure`. It's
not too important, but just like autowiring, this is *activated* thanks to
a little bit of config in our `services.yaml` file:

[[[ code('2ce8d7c547') ]]]

It's just *another* way that you can avoid configuration, and keep working!

Next, let's have fun and make our command much more awesome!
