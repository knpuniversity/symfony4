# Using the Filesystem

Config done! Let's get to work in `UploaderHelper`. Instead of passing the
`$uploadsPath`, which we *were* using to store things, change this to
`FilesystemInterface` - the one from `Flysystem` - `$filesystem`. Use that below,
and rename the property to `$filesystem`.

Below in the method, instead of `$file->move()`, we can say
`$this->filesystem->write()`, which is used to create new files. Pass this
`self::ARTICLE_IMAGE.'/'.$newFilename` and then the *contents* of the file:
`file_get_contents()` with `$file->getPathname()`.

That's it! This `File` object has a *ton* of different methods for getting the
filename, the full path, the file without the extension and more. Honestly, I get
them all confused and have to Google them. `getPathname()` gives us the absolute
file path on the filesystem.

Above, we can get rid of the unused `$destination` variable. Because the
filesystem's root is `public/uploads/`, the only thing we need to pass is the path
*relative* to that - `article_image/` and then the `$newFilename`.

I think we're ready! Let's clear out the `uploads/` directory again. And then
try our fixtures:

```terminal
php bin/console doctrine:fixtures:load
```

Oh! It does *not* work!

## Binding the Filesystem for Autowiring

> Unused binding `$uploadsPath` in service `UniqueUserValidator`.

This is actually a bad error message, at least the *second* half of the message.
A minute ago, we had an argument here called `$uploadsPath`. Open up
`config/services.yaml`. Ah, that worked because we have it configured as a global
bind. And when you have a bind, it must be used in at least *one* place in your
app. If it's not used anywhere, you get this error. It's kinda nice: Symfony is
saying:

> Hey! You've configured this bind... but you're not using it - it could be a bug!

The `UniqueUserValidator` part of the message is actually a bug in the error message,
which makes this a bit confusing.

Anyways, remove that bind and try the fixtures again:

```terminal-silent
php bin/console doctrine:fixtures:load
```

*This* is the error I was waiting for.

> Cannot autowire service `UploaderHelper` argument `$filesystem` of
> `__construct()` references `FilesystemInterface` but no such service

There are two ways to fix this. First, we could re-add the `alias` option. Or,
we can create a new bind. I'll do this because it works better if you have
multiple filesystem services. Let's rename the argument to be more descriptive,
how about `$publicUploadFilesystem`.

Then, under bind, set `$publicUploadFilesystem` to the the filesystem service id -
you can see it in the error. It suggests *two* services that implement the
`FilesystemInterface` type-hint - we want the second one. Type `@` then paste.

One more time for the fixtures!

```terminal-silent
php bin/console doctrine:fixtures:load
```

Ok, no error! Go check out the `public/uploads/` directory. Yes! We have files!
Refresh the homepage. We are good! We still need to tweak a few more details,
but our app is now *way* more ready to work locally or in the cloud.
