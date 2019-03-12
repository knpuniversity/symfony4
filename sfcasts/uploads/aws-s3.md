# Aws S3

Coming soon...

Friends, I think it's time to store our files in the cloud. So I'm already logged
into my S3 for Amazon though. Flysystem supports a lot of other adapters as
you can see. So S3 is just one of them. Um, so I'm gonna look at their
documentation. I'm also going to go back up to the OneupFlysystemBundle and look
at their documentation so I can see how to implement this stuff. Um, so click into
their documentation. Search for S3. If you keep looking, there we go. There is
there documentation on their adapters? So the first thing you're going to need is
actually this `aws-sdk-php` package. You don't technically need this because it'll get
installed in a second. So I'm gonna run over and composer require that package. 

```terminal
composer require aws/aws-sdk-php
```

While
we're waiting for that, let's create a bucket. So I'm over here in s three. I'm going
to create a new bucket, let's call it `sfcasts-spacebar`,

um, choose whatever region makes sense for you. You will need that information later.
So remember what you picked. And then we'll go to next. Uh, there are a few things.
Check anything that you want. I'm here. There's encryption, there's logging, I'm
going to skip all of that. And then I'm permissions. There are a couple things you
need to do here. One is that you don't uncheck these two boxes here. Uh, these two
boxes basically prevent, so one of the cool things about S3 is that you can
upload files there and you can decide on a file by file basic whether basis whether
or not those files should be public or private. And we are going to actually upload a
mixture of those. So by unchecking these boxes, it allows us to have public files as
well as private files. All right, so then we'll hit next and create bucket. Great.
Now to get access to this bucket, we need to go back to our services and I'm going to
open a new tab to go into I am. And here I will go to users. I'm going to add a new
user

and we'll call it, um,

as `sfcasts-spacebar-s3-access`.

Okay.

And I'm gonna check programmatic access. Uh, but not counsel access. This is going to
be our sort of user that uses the API to talk to S3.

Then for permissions,

I'm going to go do attach existing policies directly, but here I actually need to
create a new policy, these policy things or like one of the most confusing parts of
this whole thing. So I'm actually going to go to services here, open another IAM,
we're not a policies and create a new policy. And so what you can do here as you can,
uh, this basically we're actually gonna skip this and go to JSON. So the policy
thing, it's very S3 specific thing. It tells you it's a way for you to say
exactly what permissions somebody should have. Fortunately, if you go over to the
flies to some docs for s three, they actually have the IAM permissions that we need.
So I'm gonna copy this and paste this in here. And the other thing we need to change
here is our bucket name so we can go over there. Let's go copy our brand new bucket
name, `sfcasts-spacebar`, never here. We'll paste that in. So this is a policy
that gives you basically full access to [inaudible] specific bucket saw high review
policy. We'll give it a name, a lot `sfcasts-spacebar-full-s3-bucket-access`.

And then I'll say create policy.

Now that we have the policy created, I can go back to my other tab here. Get the
sound of the way. Refresh this policy. I'm search for `sfcasts`. Okay. This is
actually what I had earlier. A here's our full bucket access one. Oh, I want to click
that but actually check it and I'm good. And then we can say next, uh, skip the tags
review. This user will have access to this policy and life is good. Create user. All
right, cool. So this is going to give us an access d access id and a secret key that
we're going to need. I'm just going to let those sit here for a second. We're going
to need those very soon.

So in order to use, um, the S3 adapter through Flysystem, the first thing
you're actually going to do is use this live or that we just installed this library.
Just install. It has nothing to do Flysystem. It's just a PHP library for
interacting with AWS. So you can do is we're actually going to copy, you were going
to create a service that uses an `S3Client` class from that particular
library. So this has nothing to do with Flysystem at all yet, and close a few files
here. Then we'll go out to `config/services.yaml` and at the bottom we will paste that.
I'm more new a tick off the class and make that the service ID.

MMM.

And that's gonna allow us to autowire that `S3Client` service if we need to. Now
for the particular data on here at this date, 20 this version is the latest version
of s three so we'll just use that. The region Id for me is `us-east-1` dance,
because I selected Virginia. If you select it in another region, and I'll need to
look up what that region, id is it. Down here we have the secret, the key and the
secret.

Okay.

Which is what we just have right here. So these are things that we don't want to
commit to our repository. So instead what we're gonna do is go down to our `.env`
file. Inside of our custom vars section, I'm going to say `AWS_S3_ACCESS_ID=`
and I'm going to create another one called `AWS_S3_ACCESS_SECRET=`. Now if
you want to, you can actually go and copy the direct values and put them in this
file. But remember that `.env` is actually a committed filed a repository. So you
will be committing those two repository. What you're really supposed to do is create
a new file and of your project called `.env.local`. This, this file's not
committed to the repository. So we can copy these two keys here, paste them in there.
And now I'm going to put in my real value for these things. So here's my access id.
Here's my access secret.

Um, perfect. Okay,

now, so you use those, we can go into our `service.yaml` and for the key we'll use
that as kind of special a percent syntax that you see in Symfony. Um, we'll use
single quotes here because I liked them better `%env()%` we'll say `AWS_S3_ACCESS_ID`,

That's the key. P and id kind of mean the same thing and then
we'll copy that for the secret is secret.

So now forget about Flysystem. We now have a fully functional S3 clients that
you could use if you want it to, to talk to, um, to talk to S3 and this is what
Flysystem is going to use behind the scenes. So you can see once you set up that
service, now we're going to need it to go into our actual uh, configuration for this
bundle and tell it to use this AWS s three. So in order to do that, we're actually
going to need to install one other library, which I'm not sure why this doesn't tell
you that you need that, which is the composer league fly system library. 

```terminal
composer require league/flysystem-aws-s3-v3
```

This is
actually the small layer that is the integration between Flysystem and this AWS SDK
PHP that we just installed. This one will allow us to go in and use this `awss3v3`
adapter. So open up your `config/packages/oneup_flysystem.yaml` file. And
we're done with this local stuff local. We're going to use that `awss3v3`

and then as you can see here, you're just going to put the name of these. So client
and then the name of the service, the name of the services. This key for us, it's
actually me, the class name. So I'll say `client:`. And then I'll go copy the
service ID, also the plas name here and paste. And then the other thing we need to do
is actually specified the bucket that we're in. This is something that also, uh, we
probably don't want to commit to the repository because production might use
different bucket than we're using locally. So I'm gonna go back into my `.env` file
again and create another

environment variable called `AWS_S3_ACCESS_BUCKET=` and same thing. I'll duplicate that
in `.env.local` and actually give that my real value. So let me go grab that from my s
three tab, paste that in there. And now a copy of that environment, variable name.
And here we can say `%env()%`. And for now I'm
actually going to duplicate this down on my privates adapter as well. But to talk
about that in a second, I am actually, we're actually not going to need a public and
a private adapter. We're only gonna need one adapter with s three. But for now let's
just have them use the same exact bucket. Oh, and don't forget to put the percent on
the end of this and I make sure I did that. Yup. Did that perfectly over here.

All right, so that should be it. So to talk to you through the pieces are adapters
not going to use `awss3v3`, which is going to help talk to the S3 via
our `S3Client` service, which we used here. And it's reading all of our, um,
access ID and secret stuff to get that done. So basically it should work. Easiest way
to check it out is let's reload the fixtures. 

```terminal
php bin/console doctrine:fixtures:load
```

and yes,
I do recommend using S3 when I'm developing locally. If you're using SDN
production, you want to use S3 locally so they don't get surprises when you're
using s three on print, on production. Maybe you were doing something weird. You can
see the article fixtures are a little bit slower to load. Let's still pretty fast,
and when we refresh S3 hello `article_image/`, they are all there. Of course, if
you go to the homepage, none of the images work anymore because they're all pointing
at our local server and they don't exist on our local server anymore, so that's what
we're going to need to fix next.