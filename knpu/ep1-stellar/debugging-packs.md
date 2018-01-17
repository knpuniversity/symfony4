# Debugging & Packs

Symfony has even *more* debugging tools. The easiest way to get *all* of them
is to find your terminal and run:

```terminal
composer require debug --dev
```

Find your browser, surf back to [symfony.sh](https://symfony.sh) and search for
"debug". Ah, so the `debug` alias will actually install a package called `symfony/debug-pack`.
So... what's a pack?

Click to look at the package details, and then go to its GitHub repository.

Whoa! It's just a single file: `composer.json`! Inside, *it* requires six other
libraries!

Sometimes, you're going to want to install *several* packages at once related to
one feature. To make that easy, Symfony has a number of "packs", and their *whole*
purpose is give you *one* easy package that *actually* installs several *other*
libraries.

In this case, `composer require debug` will install Monolog - a logging library,
`phpunit-bridge` - for testing, and even the `profiler-pack` that we already installed
earlier.

If you go back to the terminal... yep! It downloaded all those libraries and configured
a few recipes.

And... check this out! Refresh! Hey! Our Twig `dump()` got prettier! The `debug-pack`
integrated everything together even better.

## Unpacking the Pack!

Go back to your Twig template and remove that dump. Then, open `composer.json`.
We just installed two packs: the `debug-pack` and the `profiler-pack`:

[[[ code('72dcebf651') ]]]

And we *now* know that the `debug-pack` is actually a collection of about 6 libraries.

But, packs have a *disadvantage*... a "dark side". What if you wanted to control
the version of just *one* of these libraries? Or what if you wanted *most* of these
libraries, but you didn't want, for example, the `phpunit-bridge`. Well... right
now, there's no way to do that: all we have is this *one* `debug-pack` line.

Don't worry brave space traveler! Just... unpack the pack! Yep, at your terminal,
run:

```terminal
composer unpack debug
```

The `unpack` command comes from Symfony flex. And... interesting! All it says is
"removing symfony/debug-pack". But if you look at your `composer.json`:

[[[ code('5f748553f1') ]]]

Ah! It *did* remove `symfony/debug-pack`, but it *replaced* it with the 6 libraries
from that pack! We can *now* control the versions or even *remove* individual libraries
if we don't want them.

That is the power of packs!
