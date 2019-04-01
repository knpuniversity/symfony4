# Configuring S3 Bucket & IAM User

Friends, I think it's finally time to store the uploaded files up... in the cloud.
We're going to use Amazon S3. But thanks to Flysystem, we could easily use a different
service - they have a *bunch* of adapters. Google again for OneupFlysystemBundle...
and click into their docs so we can see how to implement the s3 adapter. Search
for S3 and... there it is.

## Configuring the AWS S3 Adapter

The first thing we need is this `aws/aws-sdk-php` package. Copy that, move over
to your terminal and run:

```terminal
composer require aws/aws-sdk-php
```

## Creating the S3 Bucket

While we're waiting for that, let's create the S3 bucket that will store our stuff!
I'm already logged into the S3 section of AWS. Click "Create bucket" and let's
call it `sfcasts-spacebar`. Choose whatever region makes sense for you - but remember
that, because you'll need it later.

On the next screen, if you need encryption or logging or any of these things, check
them. But we'll just click next again to get to permissions. There *are* a few things
we need to do here. First, uncheck the two top boxes for "Block new public ACLs"
and "Remove public access granted through public ACLs". By unchecking these boxes,
we can now have private files *and* public files all in the same bucket. Click
"Next" again and then "Create bucket".

## IAM Permissions

Awesome! Bucket done! To be able to actually *access* this bucket... I'm going
to open an new tab for the IAM service. Click "Users" and add a new user. Let's
call it: `sfcasts-spacebar-s3-access`.

Okay. Check yes for "programmatic access", but don't check console access. This
user will exist *solely* so we can use its credentials in our app to talk to S3.

For permissions, this is *always* the tricky part, at least for me. There are a lot
of existing "policies" that can grant different permissions to different services...
I'm going to open another tab to IAM and click to create a new policy.

There's a builder to help create the policy... or you can click the JSON tab to do
it yourself. So... what do we put here? Fortunately, Flysystem has our back. In its
docs for AWS S3, scroll down and... nice! It gives us the IAM permissions we need!
Copy that, go back, and paste. Tweak the bucket name to be *our* bucket name. Let's
see... it's `sfcasts-spacebar`. Back on the policy, paste that in both spots.

This policy basically gives the new user full access to this specific bucket. Click
"Review policy" and give it a name, how about `sfcasts-spacebar-full-s3-bucket-access`.
Ok, create policy!

With that done, close that tab and go back to the original IAM tab where we're
creating our new user. Click the little refresh button and search for `sfcasts`.
The second policy was from me testing this earlier. Check the first box and hit
"Next". Skip the tags... looks good... and create user!

Congrats! The hardest part is over! This gives us two things we need: a key and
a secret. Next: let's set these as environment variables in our app and configure
Flysystem to talk to S3!
