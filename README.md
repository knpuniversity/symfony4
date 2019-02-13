# File Uploads in Symfony

Well hi there! This repository holds the code and script
for the [File Uploads in Symfony](https://symfonycasts.com/screencast/symfony-uploads) course on SymfonyCasts.

## Setup

If you've just downloaded the code, congratulations!!

To get it working, follow these steps:

**Download Composer dependencies**

Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
```

You may alternatively need to run `php composer.phar install`, depending
on how you installed Composer.

**Configure the .env (or .env.local) File**

Open the `.env` file and make any adjustments you need - specifically
`DATABASE_URL`. Or, if you want, you can create a `.env.local` file
and *override* any configuration you need there (instead of changing
`.env` directly).

**Setup the Database**

Again, make sure `.env` is setup for your computer. Then, create
the database & tables!

```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

If you get an error that the database exists, that should
be ok. But if you have problems, completely drop the
database (`doctrine:database:drop --force`) and try again.

**Start the built-in web server**

You can use Nginx or Apache, but Symfony's local web server
works even better.

To install the Symfony local web server, follow
"Downloading the Symfony client" instructions found
here: https://symfony.com/download - you only need to do this
once on your system.

Then, to start the web server, open a terminal, move into the
project, and run:

```
symfony serve
```

(If this is your first time using this command, you may see an
error that you need to run `symfony server:ca:install` first).

Now check out the site at `https://localhost:8000`

Have fun!

## Somebody Has To

Somebody has to go polish the stars,
They're looking a little bit dull.
Somebody has to go polish the stars,
For the eagles and starlings and gulls
Have all been complaining they're tarnished and worn,
They say they want new ones we cannot afford.
So please get your rags
And your polishing jars,
Somebody has to go polish the stars.

Shel Silverstein

## A Space Riddle!

> I'm not white and fluffy, but pieces of me *do* orbit the sun. What am I?

**Answer**: The Oort Cloud!

## Have Ideas, Feedback or an Issue?

If you have suggestions or questions, please feel free to
open an issue on this repository or comment on the course
itself. We're watching both :).

## Thanks!

And as always, thanks so much for your support and letting
us do what we love!

<3 Your friends at SymfonyCasts
