# Symfony 4: Let's Launch!

Hey guys! Yes! It's Symfony 4 time! I am *so* excited. Why? Because *nothing*
makes me happier than sitting down to work inside a framework where coding is actually
*fun*, and where I can build features fast, but *without* sacrificing quality. Well,
maybe I'd be *even* happier doing all of that on a beach... with, maybe a cold drink?

Anyways, Symfony 4 completely re-imagined the developer experience: you're going
to create better features, faster than ever. *And*, Symfony has a *new*, unique
super-power: it starts as a microframework, then automatically scales in size as
your project grows. How? Stay tuned...

Oh, and did I mention that Symfony 4 is the fastest version ever? And the fastest
PHP framework? Honestly, *all* frameworks are fast enough anyways, but the point
is this: you're building on a seriously awesome foundation.

***TIP
See http://www.phpbenchmarks.com for the third-party benchmark stats!
***

## Prep: Download & Update Composer

Ok, let's get started already! Open a new terminal and move into whatever directory
you want. Make sure that you already have [Composer](https://getcomposer.org/) installed
globally so that you can just say `composer`. If you have any questions, ask us
in the comments!

And *also* make sure you have the latest version:

```terminal-silent
composer self-update
```

That's important: Composer had a recent bug fix to help Symfony.

## Install Symfony!

To download your new Symfony project, run `composer create-project symfony/skeleton`
and put this into a new directory called `the_spacebar`.

```terminal-silent
composer create-project symfony/skeleton the_spacebar
```

That's the name of our project! "The Spacebar" will be *the* place for people from
*across* the galaxy to communicate, share news and argue about celebrities and
BitCoin. It's going to be amazing!

This command clones the `symfony/skeleton` project and then runs `composer install`
to download its dependencies.

Further down, there's something *special*: something about "recipes". OooOOO.
Recipes are a new and very important concept. We'll talk about them in a few minutes.

## Starting the Web Server

And at the bottom, cool! Symfony gives us clear instructions about what to do
next. Move into the new directory:

```terminal-silent
cd the_spacebar
```

Apparently, we can run our app immediately by executing:

```terminal
php -S 127.0.0.1:8000 -t public
```

This starts the built-in PHP web server, which is *great* for development. `public/`
is the document root of the project - but more on that soon!

***TIP
If you want to use Nginx or Apache for local development, you can! See
http://bit.ly/symfony-web-servers.
***

Time to blast off! Move to your browser and go to `http://localhost:8000`. Say
hello to your new Symfony app!

## Our Tiny Project

***TIP
Symfony no longer creates a Git repository automatically for you. But, no problem!
Just type `git init` once to initialize your repository.
***

Back in the terminal, I'll create a new terminal tab. Symfony *already* inititalized
a new git repository for us *and* gave us a perfect `.gitignore` file. Thanks Symfony!

***TIP
If you're using PhpStorm, you'll want to ignore the `.idea` directory from git. I already have it
ignored in my global .gitignore file: https://help.github.com/articles/ignoring-files/
***

That means we can create our *first* commit just by saying:

```terminal
git init
git add .
git commit
```

Create a calm and well-thought-out commit message.

```terminal-silent
# Woohoo! OMG WE ARE USING SYMFONY4
```

Woh! Check this out: the entire project - *including* Composer and `.gitignore`
stuff - is only 16 files! Our app is teenie-tiny!

Let's learn more about our project next *and* setup our editor to make Symfony
development *amazing*!
