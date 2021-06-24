# Flysystem: Filesystem Abstraction

I keep talking about how we're going to eventually move our uploads off of our
server and put them onto AWS S3. But right now, our entire upload system is *very*
tied to our local filesystem. For example, `$file->move()`? Yea, that will *always*
move things physically on your filesystem.

One of my *favorite* tools to help with this problem is a library called Flysystem.
It's written by our friend Frank - who co-authored our React tutorial. He also
spoke at SymfonyCon in 2018 about Flysystem and that presentation is
[available right here on SymfonyCasts](https://symfonycasts.com/screencast/symfonycon2018/file-storage-modern-php-apps).

Flysystem gives you a nice service object that you can use to write or read files.
Then, behind the scenes, you can swap out whether you want to use a local filesystem,
S3, Dropbox or pretty much anything else. It gives you an easy way to work with
the filesystem, but that filesystem could be local or in the cloud.

## OneupFlysystemBundle

In Symfony, we have an excellent bundle for this library: Google for OneupFlysystemBundle,
find their GitHub page, then click into the docs. Copy the library name, find your
terminal and run:

***TIP
A newer version of this bundle exists, which uses a newer version of the underlying
`league/flysystem` library. To use the same version as we use in this tutorial, install
version 3 of the bundle. If you install the newer version, we'll do our best to
add notes to guide you through any changes :).
***

```terminal
composer require "oneup/flysystem-bundle:^3"
```

## Adapters & Filesystems

While Jordi is preparing our packages, go back to their docs. Flysystem has two
important concepts, which you can see here in the config example. First, we need
to set up an "adapter", which is a lower-level object. Give it any name - like
`my_adapter`. Then, this key - `local` - is the critical part: this says that
you want to use the `local` adapter - an adapter that stores things on the local
filesystem. Click the `AwsS3` adapter link. If you want to use *this* adapter and
store your files in S3, you'll use the key `awss3v3`. Every adapter also has
different options. We're going to start with the `local` adapter, but move to s3
later.

But the *real* star, is the *filesystem*. Same thing: you give it any nickname,
like `my_filesystem` and then say: this filesystem uses the `my_adapter` adapter.
We'll talk about `visibility` later. The *filesystem* is the object that we'll
work with directly to read, write & delete files.

Ok, go check on Composer. It's done and thanks to the recipe, we have a new
`config/packages/oneup_flysystem.yaml` file with the same config we just saw
in the docs.

[[[ code('86edd83eee') ]]]

## Configuring the Adapter & Filesystem

Let's create 1 adapter and 1 filesystem for our uploads. Call the adapter, how
about, `public_uploads_adapter`. I'm saying "public uploads" because this will
put things into the `public/` directory: they will be publicly accessible. We'll
talk about private uploads soon - those are files where you need to do some
security checks before you allow a user to see them. Change the directory to
`%kernel.project_dir%` and then `/public/uploads`.

***TIP
If you're using version 4 of `oneup/flysystem-bundle`, the `directory` config
is now called `location`.
***

[[[ code('adfa37173f') ]]]

That is the *root* of this filesystem: everything will be stored relative to
this. Give the filesystem a similar name - `public_uploads_filesystem` - and set
`adapter:` to `public_uploads_adapter`.

[[[ code('9284457b91') ]]]

## Filesystem Alias?

What about this `alias` key? Let's see what that does. First, when you configure
a filesystem here, it creates a service. Find your terminal and run:

```terminal
php bin/console debug:container flysystem
```

There it is: `oneup_flysystem.public_uploads_filesystem_filesystem`. *That* service
was created thanks to our config and we'll use it soon in `UploaderHelper`. The
bundle *also* created another service called: `League\Flysystem\Filesystem`. Well,
actually, it's an *alias*: I'll type 61 to view more info about it. Yep! This
points to our `public_uploads_filesystem` service. The *purpose* of this is that
it allows us to type-hint `League\Flysystem\Filesystem` and Symfony will autowire
our filesystem service.

If you only have 1 filesystem, having this alias is great. But if you have multiple,
well, you can only autowire *one* of them. I'm going to remove the alias - I'll
show you another way to access the filesystem service.

Ok, config done! Next, let's start using this shiny new Filesystem service.
