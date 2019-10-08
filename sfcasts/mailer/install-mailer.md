# Hello Symfony Mailer

The year was 1995: internet connection speeds were reaching 56 kbit/s, GeoCities
was turning everyone into an accomplished web designer, and sending *emails* was
*all* the rage.

Quick, fast-forward 25 years! Self-driving cars are becoming more common, you
can download an entire HD video in seconds, we can send rockets into space and
then land them safely back on Earth and... love it or hate it... sending emails
is *still* all the rage... or at least... something nobody can avoid.

Yep, emails are still a *huge* part of our life and pretty much *every* app will
send at least some... if not *a lot* of emails. But sending emails has always been
kind of a pain - it often *feels* like an old process. On top of that, emails
are hard to preview, a pain to debug, there are multiple ways to deliver them -
do I need an SMTP server? - each email has a text *and* HTML component, and don't
even get me started about styling emails and embedding CSS in a way that'll work
in *all* mail clients. Oof.

Enter Symfony Mailer: a fresh & modern library that makes something old - sending
emails - feel... *new*! Seriously, Mailer actually makes sending emails *fun* again
and handles some of the *ugliest* details automatically. Will you love sending
emails after this tutorial? Yea... I think you kinda might!

## Setting up the App

As always, unless you're just "mailing it in", you should *totally* code along
with me. Download the course code from this page using your 56k modem and unzip it
with WinRAR 1.54b. Inside, you'll find a `start/` directory with the same code
that you see here. Open up the `README.md` file to find all the setup instructions.
The *last* step will be to open a terminal, move into the project and use the
Symfony Binary to start a web server:

```terminal
symfony serve
```

If you don't have the Symfony binary, you can grab it at [Symfony.com/download](https://symfony.com/download).
Once that's running, open your favorite browser, like Netscape Navigator, and
goto `https://localhost:8000` to see... The Space Bar! A new site for aliens and
the app that you probably recognize from other Symfony 4 tutorials here on the site.
In this tutorial, we'll be using Symfony 4.3. There *are* a few cool features
that are coming in Symfony 4.4 and 5.0... but don't worry! I'll point those out
along the way - they aren't big changes, just some nice new debugging features.

## Installing Mailer

Like most things in Symfony, the Mailer component is *not* installed by default.
No problem, find your terminal, open a new tab and run:

```terminal
composer require symfony/mailer
```

Notice that I didn't just use `composer require mailer`... using the "mailer"
alias. Remember: Symfony Flex lets us just say things like `composer require forms`
or `composer require templating` and then it maps that to a recommended package.
But at the time of this recording, `composer require mailer` would *not* download
the Mailer component. Nope, it would download Swift Mailer... was was the recommended
library for sending emails with Symfony *before* Symfony 4.3, which is when
the Mailer component was introduced.

And even when you're Googling for documentation about Symfony's Mailer, be careful:
you might end up on the docs about using SwiftMailer inside Symfony. The Mailer
docs might be the second or third result.

Anyways after this installs, yea: we get some nice, post-install instructions.
We'll talk about *all* of this.

The first step... is to create and configure an Email object! Let's do that next...
then send it!
