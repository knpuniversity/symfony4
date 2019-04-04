# Flysystem & S3

With our key & secret in hand, and this *unescapable* feeling of power that they're
giving us, let's hook up Flysystem to use an S3 adapter. Oh, first, go check on that
library we were installing. Done! This is a PHP library for interacting with any AWS
service, and it has *nothing* to do with Symfony or Flysystem. Copy the example
configuration. Our *first* job is to register a service for this `S3Client` class
that comes from that library.

## Registering the S3Client Service

Let's close *all* these tabs so we can concentrate. Open `config/services.yaml`
and, at the bottom, paste that config! But I'm going to simplify this: copy the class
name, remove it, and paste *that* as the service id. Why? First, because, when
possible, it's just easier to use the class name as the service id instead of inventing
a string id. And second, this will allow us to *autowire* the `S3Client` service
into any of our services or controllers. We won't need that for what we're doing,
but it's nice.

[[[ code('3391cc3afb') ]]]

This takes just one argument: a big array of config. This *old* looking API version
is actually still the most recent. For region, this depends on what region
you chose for your bucket. Mine is `us-east-1` because I selected Virginia. If
you selected a different region, it won't work. Kidding! Just do some Googling
to find the right region id.

What about the `key` and `secret`? *These* are the values IAM gave us after creating
the user. But, we probably don't want to put their values right here and commit them
to the repository. Instead, open the `.env` file and, inside of the custom vars
section we created in a previous tutorial, let's invent two new environment variables
`AWS_S3_ACCESS_ID` and `AWS_S3_ACCESS_SECRET`.

[[[ code('872a493da5') ]]]

If you want, you *could* copy the values and put them directly into this file. But
remember, the `.env` file *is* committed to your git repository... and you really
*don't* want any secret value to be committed. Instead, create a new file at the
root of your app called `.env.local`. This file is *also* read by Symfony and any
values will *override* the ones in `.env`. It's also *ignored* from git via our
`.gitignore` file.

Copy the two keys from `.env` and paste them here. And *now* we can grab the
real values. Copy the id, paste, then show the secret, copy, and paste that.

Environment variables, set! To use them, head back to `services.yaml`. Replace
the key with the special environment variable syntax: `%env()%` and inside,
`AW`... go copy the name - `AWS_S3_ACCESS_ID`. Re-use that syntax for the secret:
`AWS_S3_ACCESS_SECRET`.

[[[ code('bc1969f4b9') ]]]

If you forget about Flysystem for a minute, we now have a *fully* functional `S3Client`
service that we an autowire and use to do anything with our new bucket! The question
*now* is: how can we tell Flysystem to use this?

## The Flysystem AWS-S3-V3 Adapter

Go back to the OneupFlysystemBundle docs. Ok, so once the service is set up, we
apparently need to go into the actual config for *this* bundle and change to
a new adapter: `awss3v3`.

But to use *that*... hmm... it's not too obvious on this page. Go back to the
Flysystem docs about S3 and scroll up. Here we go: the Flysystem S3 adapter
is its own separate package. Copy this line, find your terminal and paste:

```terminal-silent
composer require league/flysystem-aws-s3-v3
```

Once that finishes... there. *Now* we can use this `awss3v3` adapter. Open up
`config/packages/oneup_flysystem.yaml`. Remove *all* that `local` config. Replace
it with `awss3v3:`. The first sub-key this needs is: `client`, which points to the
*service* id for the `S3Client`.

[[[ code('673898c385') ]]]

Add `client:`, copy the service id, and paste. 

[[[ code('c3484bb448') ]]]

The adapter also needs to know what S3 bucket it should be talking to. 
This is *also* something that you might *not* want to commit to your repository, 
because production will probably use a different bucket than when you're 
developing locally. So, back in our trusty `.env` file, add a third environment 
variable `AWS_S3_ACCESS_BUCKET`... well, I could just call this `AWS_S3_BUCKET`... 
I didn't *really* mean to keep that `ACCESS` part in there. But, no problem.

[[[ code('a1deda7969') ]]]

Just like before, copy that, duplicate it in `.env.local` and give it a real
value, which... if you go back to S3, is `sfcasts-spacebar`. Paste that.

Finally, copy the new variable's name, open `oneup_flysystem.yaml`, and set
`bucket` to `%env(AWS_S3_ACCESS_BUCKET)%`.

[[[ code('8c187d3f55') ]]]

That's it! What about the `private_uploads_adapter`? Well, temporarily, copy
the config from the public adapter and paste it *exactly* down there. We're
actually *not* going to need two filesystems anymore... but we'll talk about
that soon.

[[[ code('8c187d3f55') ]]]

Oh, and don't forget the `%` sign at the end of the `%env()%` syntax! I *did*
do that correctly in `services.yaml`.

Ok, I think we're ready! Both filesystems will use an `awss3v3` adapter and each
of *those* knows to us the `S3Client` service that's reading our key and secret.
So... it should... just kinda work! The easiest way to find out is to reload
the fixtures:

```terminal
php bin/console doctrine:fixtures:load
```

And yes, I *do* recommend using S3 when developing locally if that's what you're
using on production. You *could* change the adapter to be the `local` adapter,
but the less differences you have between your local environment & production, the
better.

Fixtures done! Go and refresh the S3 page. Hey! We have an `article_image` directory
and it's *full* of images! I think it worked! Go the homepage and... nothing
works. That's because our paths are all still pointing at the *local* server - not
at S3. Let's fix that next!
