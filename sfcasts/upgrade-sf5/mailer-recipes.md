# Updating the Mailer Recipe(s)

The next recipe on our list is `symfony/mailer`... which is an *especially* interesting
one because, until Symfony 4.4, `symfony/mailer` was marked as "experimental".
That means that there *are* some backwards-compatibility breaks between
Symfony 4.3 and 4.4. The recipe update *might* help us find out about a few
of these.

Run:

```terminal
composer recipes symfony/mailer
```

Then copy the `recipes:install` command and run it:

```terminal-silent
composer recipes:install symfony/mailer --force -v
```

According to the output, this only touched *one* file. Let's see for sure. Run:

```terminal
git status
```

Yep! Only `.env` was changed. Run:

```terminal
git add -p
```

Hmm. It looks like it *removed* two comment lines, which mention that the
`MAILER_DSN` for the `null` transport looks different in Symfony 4.4. And then
it added an example of using the `smtp` transport. The top line is my custom
code.

I don't *really* want these changes. I mean, I *do* still want to define a
`MAILER_DSN` environment variable and I *do* still want to use the `null` transport.
Except... the removed note *did* just remind me about a syntax change in the
`null` transport for Symfony 4.4.

Hit "n" to *not* add this change... for now. Then hit "y" for the `symfony.lock`
update.

## The Updated Null Mailer Transport Syntax

Let's see how things look:

```terminal
git status
```

Undo the changes:

```terminal
git checkout .env
```

Open `.env` in our editor... and find the "mailer" section:

[[[ code('dc00bdb9a5') ]]]

Even though we didn't accept the new recipe changes, we *do* need to update
our syntax. Copy the example and paste. Actually, the `default` part can be
anything - you'll sometimes see `null`:

[[[ code('7d17a17f86') ]]]

And *now* if you wanted to delete the extra comments about Symfony 4.4, you totally
could... and probably should.

So... we basically didn't use *anything* from the updated recipe, but it *did*
remind us of a change we needed to make.

## Checking the CHANGELOG

And because `symfony/mailer` may have *other* backwards-compatibility breaks,
it's not a bad idea to check its CHANGELOG. I'll go to
https://github.com/symfony/mailer... and click to see it. Yep! You can see
info about the `null` change and a few others. We'll see one of these later.

Back at your terminal, run:

```terminal
composer recipes
```

again. There's *one* other recipe that's relevant to symfony/mailer. It's
`symfony/sendgrid-mailer`: a package that helps us send emails through SendGrid.
Let's skip straight to updating this:

```terminal
composer recipes:install symfony/sendgrid-mailer --force -v
```

And then step through the changes with:

```terminal
git add -p
```

The first change is inside `.env`. Oh! Ha! That's the change *we* made, I forgot
to add it. Hit "y" to add it now.

The *other* change is *also* in `.env`: it changed the `MAILER_DSN` example from
something starting with `smtp://` to `sendgrid://`. Similar to the `null` transport
situation, `symfony/mailer` 4.4 *also* changed the syntax for a few *other*
transports.

I'm going to say "y" to accept this change: both the old and new code were just
examples anyway.

*But*, there is one *other* spot you need to check: we need to see if we're using
the old format in the `.env.local` file. Go open that up. In this project, nope!
I'm not overriding that. If we *did* have `smtp://sendgrid` in *any* env files,
or configured as a *real* environment variable, maybe on production, that would
need to be updated.

For the last change - to `symfony.lock` - hit "y" to add it. Run:

```terminal
git status
```

to make sure we're not missing anything. Looks good! Commit!

```terminal
git commit -m "updating symfony/mailer recipe packages"
```

Done! We're down to the *last* few recipe updates. Let's *crush* them.
