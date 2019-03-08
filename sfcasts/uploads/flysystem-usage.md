# Using the Filesystem

Config done! Let's get to work in `UploaderHelper`. Instead of passing the
`$uploadsPath`, which we *were* using to store things, change this to
`FilesystemInterface` - the one from `Flysystem` - `$filesystem`. Use that below,
and rename the property to `$filesystem`.

[[[ code('3350732bb2') ]]]

Now, in the method, instead of `$file->move()`, we can say
`$this->filesystem->write()`, which is used to create new files. Pass this
`self::ARTICLE_IMAGE.'/'.$newFilename` and then the *contents* of the file:
`file_get_contents()` with `$file->getPathname()`.

[[[ code('c9e64aa742') ]]]

That's it! This `File` object has a *ton* of different methods for getting the
filename, the full path, the file without the extension and more. Honestly, I get
them all confused and have to Google them. `getPathname()` gives us the absolute
file path on the filesystem.

Above, we can get rid of the unused `$destination` variable. Because the
filesystem's root is `public/uploads/`, the only thing we need to pass to `write()`
is the path *relative* to that: `article_image/` and then `$newFilename`.

I think we're ready! Let's clear out the `uploads/` directory again. And then
try our fixtures:

```terminal
php bin/console doctrine:fixtures:load
```

Oh! It does *not* work!

## Binding the Filesystem for Autowiring

> Unused binding `$uploadsPath` in service `UniqueUserValidator`.

This is a *bad* error message from Symfony, at least the *second* half of the message.
A minute ago, we had an argument here called `$uploadsPath`. Open up
`config/services.yaml`. Ah, that worked because we have `$uploadsPath` configured
as a global bind. And when you configure a bind, it must be used in at least
*one* place in your app. If it's not used anywhere, you get this error. It's kinda nice: Symfony is saying:

> Hey! You configured this bind... but you're not using it - are you maybe...
> messing something up on accident?

The `UniqueUserValidator` part of the message is really a bug in the error message,
which makes this a bit confusing.

Anyways, remove that bind and try the fixtures again:

```terminal-silent
php bin/console doctrine:fixtures:load
```

*This* is the error I was waiting for.

> Cannot autowire service `UploaderHelper` argument `$filesystem` of
> `__construct()` references `FilesystemInterface` but no such service exists.

There are two ways to fix this. First, we could re-add the `alias` option and
point it at this `FilesytemInterface`. *Or*, we can create a new bind. I'll do
the second, because it works better if you have multiple filesystem services, which
we will soon. First, rename the argument to be more descriptive, how about
`$publicUploadFilesystem`.

[[[ code('43bca769f7') ]]]

Then, under bind, set `$publicUploadFilesystem` to the filesystem service id -
you can see it in the error. It suggests *two* services that implement the
`FilesystemInterface` type-hint - we want the second one. Type `@` then paste.

[[[ code('93a22bf77e') ]]]

One more time for the fixtures!

```terminal-silent
php bin/console doctrine:fixtures:load
```

Ok, no error! Check out the `public/uploads/` directory. Yes! We have files!
Refresh the homepage. We are good! We still need to tweak a few more details,
but our app is now *way* more ready to work locally or in the cloud.
