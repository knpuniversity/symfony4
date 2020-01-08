# Hunting the Final Deprecations

How can we be sure all our deprecated code is gone? The easiest way to catch *most*
things is to surf around your site and see if you can trigger any other deprecation
logs. Except... if a deprecation happens on a form submit where you redirect after...
or if it happens on an AJAX call... you're not going to see it on the web debug
toolbar.

## Checking Deprecated Logs Locally

*Fortunately*, deprecations are also logged to a file. At your terminal, run:

```terminal
tail -f var/log/dev.log
```

Symfony writes a *lot* of stuff to this log file... *including* any deprecation
warnings: "User Deprecated". Hit Ctrl+C to exit the "tail" mode and run this again,
but this time "pipe" it to `grep Deprecated`:

```terminal-silent
tail -f var/log/dev.log | grep Deprecated
```

We're now watching the log file for any lines that contain Deprecated. Unfortunately,
because of that annoying `doctrine/persistence` stuff, it *does* contain some extra
noise, but it should still work. You could filter that stuff out by adding another
`| grep -v persistence`.

Anyways, *now* we can try out the site - like clicking into an article... or doing
anything else you can think of, like going to an admin section `/admin/comments`.
Oh, duh - I'm not logged in as an admin. You get the point: use your site, then
go back and check out the deprecations.

Yikes! I probably *should* have added the `| grep -v persistence` to remove all
the extra stuff. But if you look closely... yea... *every* single one of these
are from `doctrine/persistence`.

So as *best* as we can tell, our site is deprecation free. But! There are a few
more things to check to be sure.

## Command Deprecations

For example, if you have some custom console commands, *they* might trigger some
deprecated code. Open a new terminal tab and run:

```terminal
php bin/console
```

This app has two custom console commands. Let's run this `article:stats` command...
it's just a fake command that prints out a hardcoded table of stats. Run:

```terminal
php bin/console article:stats foo
```

Ok! It worked perfectly. But if you go back to the logs and look closely...
ah! A *real* deprecation warning!

> `ArticleStatsCommand::execute()` should always be of the type `int` since
> Symfony 4.4, NULL returned.

Interesting. Let's go open that command: `src/Command/ArticleStatsCommand.php`.
Since Symfony 4.4, the `execute()` method of every command *must* return an integer.
At the bottom, `return 0`.

This ends up being the "exit code" that the command returns when you run it. Zero
means successful and pretty much anything else - like 1 - means that the command
*failed*.

Copy the return and open the other custom command class. At the bottom of `execute()`,
`return 0`. And... let's make sure that we don't have any other return statements
earlier. It looks good.

## Production Deprecation Log

So we've surfed the site, checked the logs and run some console commands. *Now*
are we sure that all the deprecated code is gone? Maybe? There are 2 more tricks
to be sure.

First, as I mentioned earlier, I would deploy my code to production and then
watch the `prod.deprecations.log` file for any new entries... ignoring any of
the `doctrine/persistence` stuff. If nothing new is being added, it's almost
definitely safe to upgrade.

## Deprecations in Tests

Another easy trick is to... run your tests! You... *do* have tests, right? Run:

```terminal
php bin/phpunit
```

For me, it looks like it needs to download PHPUnit... and then... cool! The
phpunit-bridge that we're using *collects* all the deprecations that were hit
inside our tests, and prints them after the test finishes. There are a *lot*
of `doctrine/persistence` things... but that's it! There are no Symfony deprecations.

I am *now* willing to say that our app is ready for Symfony 5.0. Next... let's
upgrade! Because we've already done all the hard work, upgrading to a new major
version of Symfony is just a Composer trick.
