# Env Var Tricks & on Production

When you deploy to production, you're *supposed* to set *all* these environment
variables *correctly*. If you look back at `index.php`:

[[[ code('eb6f88c6af') ]]]

If the `APP_ENV` environment variable is set already, it knows to *skip* loading
the `.env` file.

***TIP
If you start a new project today, you won't see this `APP_ENV` logic. It's
been moved to a `config/bootstrap.php` file.
***

In reality... in a lot of server environments, setting environment variables can
be a *pain*. You can do it in your Apache virtual host or in PHP-FPM. Oh, and you'll
need to make sure it's set at the command line too, so you can run `bin/console`.

If you use a Platform-as-a-Service like Platform.sh or Heroku, then setting environment
variables is *super* easy! Lucky you!

But if setting environment variable is tough in your situation, well, you *could*
still use the `.env` file. I mean if we deployed right now, we could create this
file, put all the real values inside, and Symfony would use that! Well, if you're
planning on doing this, make sure to move the dotenv library from the `require-dev`
section of your `composer.json` to `require` by removing and re-adding it:

```terminal-silent
composer remove symfony/dotenv
composer require symfony/dotenv
```

The reason that using `.env` isn't *recommended* is mostly because the logic to
parse this file isn't optimized: it's not *meant* for production! So, you'll lose
a *small* amount of performance - probably just a couple of milliseconds, but you
can profile it to be sure.

***TIP
The performance cost of `.env` has been shown to be low. It *is* ok to use
a `.env` file in production if that's the most convenient way for you to set
environment variables.
***

## Casting Environment Variables

But... there is *one* other limitation of environment variables that affects
*everyone*: environment variables are *always* strings! But, what if you need an
environment variable that's set to true or false? Well... when you *read* it with
the special syntax, "false" will literally be the *string* "false". Boo!

Don't worry! Environment variables have *one* more trick! You can *cast* values
by prefixing the name with, for example, `string:`:

[[[ code('74992bce66') ]]]

Well, this is *already* a string, but you get the idea!

To show some better examples, Google for Symfony Advanced Environment Variables to
find a [blog post][advanced_env_vars] about this feature. Cooooool. This `DATABASE_PORT`
should be an `int` so... we cast it! You can also use `bool` or `float`.

## Setting Default Environment Variables

This is great... but then, the Symfony devs went *crazy*. First, as you'll see in
this blog post, you can set *default* environment variable values under the
`parameters` key. For example, by adding an `env(SECRET_FILE)` parameter, you've
just defined a *default* `SECRET_FILE` environment value. If a *real* `SECRET_FILE`
environment variable were set, it would override this.

## Custom Processing

More importantly, there are 5 *other* prefixes you can use for special processing:

* First, `resolve:` will resolve parameters - the `%foo%` things - if you have them
  *inside* your environment variable;

* Second, you can use `file:` to return the *contents* of a file, when that file's path
  is stored in an environment variable;

* Third, `base64:` will `base64_decode` a value: that's handy if you have a value that
  contains line breaks or special characters: you can `base64_encode` it to make
  it easier to *set* as an environment variable;

* Fourth, `constant:` allows you to read PHP constants;

* And finally, `json:` will, yep, call your friend Jason on the phone. Hey Jason!
  I mean, it will `json_decode()` a string.

And, ready for the *coolest* part? You can *chain* these: like, open a file, and
then decode its JSON:

```yaml
app.secrets: '%env(json:file:SECRETS_FILE)%'
```

Actually, sorry, there's more! You can even create your *own*, custom prefix - like
`blackhole:` and write your own custom processing logic.

Ok, I'll shut up already about environment variables! They're cool, yadda, yadda, yadda.

Let's move on to a *super* fun, *super* unknown "extra" with autowiring.


[advanced_env_vars]: https://symfony.com/blog/new-in-symfony-3-4-advanced-environment-variables
