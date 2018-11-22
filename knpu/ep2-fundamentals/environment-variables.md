# Environment Variables

Our Slack feature is working... but this kind of sucks! Our "secret" URL is hardcoded
in the middle of a config file!

[[[ code('fc9e3b5341') ]]]

This is a bummer because I don't want to commit this to version control! And what if I
need to use a *different* value on production?

We're going to have this problem a *bunch* more times - for example - with our database
password! We need *some* good way of *isolating* any sensitive or server-specific
config so that they're not stuck in the middle of our code.

## Intro to Environment Variables

One of the *best* ways to do this - and the way that Symfony recommends - is via
*environment variables*. OooOOoo. But... environment variables are still kind of
a mystery to a lot of PHP devs. A *mostly* accurate description is that they're
variables that are set on your *operating* system, that can then be read by your
code. How? Usually with the `getenv()` function or `$_SERVER`.

Actually, open `public/index.php`. Hey! Our code is *already* reading an environment
variable: `APP_ENV`:

[[[ code('b6eb443ace') ]]]

***TIP
If you start a new project today, you won't see this `APP_ENV` logic. It's
been moved to a `config/bootstrap.php` file.
***

But here's the question: how can we *remove* this hardcoded URL, and *instead* tell
the NexySlackBundle to read from some *environment* variable? I mean, it's not like
we can just use the `getenv()` PHP function in the middle of YAML!

Copy the URL - we'll need it later, then empty the value. Symfony has a special syntax
that can be used in config files to read from environment variables. It's a little
weird at first, but stick with me: `%env()%`. Between the parentheses, put the name
of the environment variable. We'll be setting a *new* environment variable, so how
about, `SLACK_WEBHOOK_ENDPOINT`:

[[[ code('c9b5b6281a') ]]]

By convention, environment variables are uppercase. And huh... this *looks* like
a *parameter*: it has the `%` at the beginning and at the end. And... internally,
it is! It's just a *special* parameter that will eventually resolve to this environment
variable.

If we refresh now... error! Perfect!

> Environment variable not found: `SLACK_WEBHOOK_ENDPOINT`.

## Setting Environment Variables in .env

I *love* clear errors. So... how the heck do we *set* environment variables? Well...
unfortunately, it *totally* depends on your setup! The solution is different if
you're using Apache, Nginx, Docker or some Platform-as-a-Service. I'll talk more
about that later.

But since setting environment variables can be a pain, Symfony gives us a *much*
easier way to set them while developing. How? Open the `.env` file at the root of
our project:

```ini
# This file is a "template" of which env vars need to be defined for your application
# Copy this file to .env file for development, create environment variables when deploying to production
# https://symfony.com/doc/current/best_practices/configuration.html#infrastructure-related-configuration

###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=5ea3114a349591bd131296e00f21c20a
#TRUSTED_PROXIES=127.0.0.1,127.0.0.2
#TRUSTED_HOSTS=localhost,example.com
###< symfony/framework-bundle ###
```

This file is loaded inside `index.php`... as long as the `APP_ENV` environment variable
isn't set some other way:

[[[ code('7558c1170a') ]]]

And... it's pretty simple: it reads all of these keys and *sets* each as a new
environment variable. This file was originally added by a recipe and - this is
*really* cool - other recipes will *update* this file: adding new environment variables
for *their* libraries.

But, we're totally free to add our own stuff. Let's invent a new section on top:

```ini
# This file is a "template" of which env vars need to be defined for your application
# Copy this file to .env file for development, create environment variables when deploying to production
# https://symfony.com/doc/current/best_practices/configuration.html#infrastructure-related-configuration

### CUSTOM VARS

### END CUSTOM VARS

###> symfony/framework-bundle ###
# ...
###< symfony/framework-bundle ###
```

The fancy code comments around the framework-bundle section were added by Flex:
it's so that it knows *where* the environment variables live for that library...
basically so that it can *remove* them if we remove that bundle.

*Our* new section is just for clarity.

Add `SLACK_WEBHOOK_ENDPOINT=` and then our URL:


```ini
# This file is a "template" of which env vars need to be defined for your application
# Copy this file to .env file for development, create environment variables when deploying to production
# https://symfony.com/doc/current/best_practices/configuration.html#infrastructure-related-configuration

### CUSTOM VARS
SLACK_WEBHOOK_ENDPOINT=https://hooks.slack.com/services/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
### END CUSTOM VARS

###> symfony/framework-bundle ###
# ...
###< symfony/framework-bundle ###
```

And yep! That's all we need! Refresh! It works!

## Seeing all Environment Variables

If you want to see *all* the environment variables that are currently set, there's
a handy bin/console command for that:

```terminal
php bin/console about
```

This shows your Symfony version, some system info and - hello! - environment variables!

## Updating .env.dist

***TIP
New projects will *not* have a `.env.dist` file. Instead, your `.env` file *is* committed
to your repository and should hold sensible, but not "secret" default values. To override
these defaults with values specific to your machine, create a `.env.local` file. This
file *will* be ignored by git.
***

In addition to the `.env` file, there is *another* file: `.env.dist`. Copy our new
section, open that file, and paste! Remove the sensitive part of the URL:

[[[ code('d9e4cc247c') ]]]

This file is *not* read by Symfony: it's just meant to be a *template* file that
contains all the environment variables our app needs. Why? Well, this file *will* be
committed to the repository... but `.env` will *not*: it's in our `.gitignore`. So,
when a new dev works on the project for the first time, they can copy `.env.dist`
to `.env` and then fill in their custom values.

And... yea! That's basically it! There is one fancy syntax in config files to *read*
environment variables, and a `.env` file to help *set* them during development.

## Environment Variables on Production

But... what about on production? Let's talk a bit more about this.
