# Our Micro-App & PhpStorm Setup

Our mission: to boldly go where no one has gone before... by checking out our app!
I already opened the new directory in PhpStorm, so fire up your tricorder and let's
explore!

## The public/ Directory

There are only three directories you need to think about. First, `public/` is the
document root: so it will hold *all* publicly accessible files. And... there's
just one right now! `index.php`. This is the "front controller": a fancy word programmers
invented that means that this is the file that's executed when you go to *any* URL.

But,  *really*, you'll almost never need to worry about it. In fact, now that we've
talked about this directory, *stop* thinking about it!

## src/ and config/

Yea, I lied! There are *truly* only *two* directories you need to think about:
`config/` and `src/`. `config/` holds... um... ya know... config files and `src/`
is where you'll put *all* your PHP code. It's just that simple.

Where is Symfony? As usual, when we created the project, Composer read our `composer.json`
file and downloaded all the third-party libraries - including parts of Symfony -
into the `vendor/` directory.

## Installing the Server

Go back to your terminal and find the original tab. Check this out: at the bottom,
it says that we can get a *better* web server by running `composer require server`.
I like better stuff! So let's try it! Press `Ctrl`+`C` to stop the existing server,
and then run:

```terminal
composer require server
```

If you're familiar with Composer... that package name should look funny! Really,
wrong! *Normally*, every package name is "something" *slash* "something", like
`symfony/console`. So... `server` just should *not* work! But it *does*! This is
part of a cool new system called Flex. More about that soon!

When this finishes, you can now run:

```terminal
./bin/console server:run
```

This does *basically* the same thing as before... but the command is shorter. And
when we refresh, it still works!

By the way, this `bin/console` command is going to be our new robot side-kick. But
it's *not* magic: our project has a `bin/` directory with a `console` file inside.
Windows users should say `php bin/console`... because it's just a PHP file.

So, what amazing things can this `bin/console` robot do? Find your open terminal
tab and just run:

```terminal
./bin/console
```

Yes! This is a list of *all* of the `bin/console` commands. Some of these are debugging
*gold*. We'll talk about them along the way!

## PhpStorm Setup

Ok, we are *almost* ready to start coding! But we *need* talk about our spaceship,
I mean, editor! Look, you can use *whatever* your want... but... I *highly* recommend
PhpStorm! Seriously, it makes developing in Symfony a *dream*! And no, those nice
guys & gals at PhpStorm aren't paying me to say this... but they can if they want
to!

Ahem, If you *do* use it... which would be *awesome* for you... there are 2 secrets
you need to know to trick out your spaceship, ah, *editor*! Clearly I was in hyper-sleep
too long.

Go to Preferences, Plugins, then click "Browse Repositories". There are 3
must-have plugins. Search for "Symfony". First: the "Symfony Plugin". It has over
2 million downloads for a reason: it will give you *tons* of ridiculous
auto-completion. You should also download "PHP Annotations" and "PHP Toolbox". I
already have them installed. If you *don't*, you'll see an "Install" button right
at the top of the description. Install those and restart PHPStorm.

*Then*, come *back* to Preferences, search for "symfony" and find the new "Symfony"
section. Click the "Enable Plugin" checkbox: you need to enable the Symfony plugin
for *each* project. It says you need to restart... but I think that's lie. It's space!
What could go wrong?

So that's PhpStorm trick #1. For the second, search "Composer" and click on the
"Composer" section. Click to browse for the "Path to composer.json" and select the
one in our project. I'm not sure why this isn't automatic... but whatever! Thanks
to this, PhpStorm will make it easier to create classes in `src/`. You'll see this
*really* soon.

Okay! Our project is set up and it's already working. Let's start building some
pages and discovering more cool things about new app.
