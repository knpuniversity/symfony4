# Where & How to Store the File

For now, the form is still submitting to this test endpoint. We'll change that soon
by moving it into the actual article form. But, to finish a successful file upload,
we need *move* the uploaded file from the temporary spot on the filesystem to its
final location.

## Where to Store Uploads?

So... where *should* we store the uploaded article images? The *first* question
to ask is: can these uploaded files be public to everyone? Or do we need to do some
sort of security check before a user can view or download them? For article images,
they can be public. But we'll talk about private files later.

Ok, so *if* someone needs to be able to view these images, it means they need to
live *somewhere* in the `public/` directory. Later, we're going to talk about
storing files in the cloud! Like S3, which honestly, is an awesome idea. But right
now, we're going to keep it simple and store things directly on our server.

So how about storing things in... I don't know... `public/uploads`? Create that
new directory. Then, inside, create an *empty* `.gitignore` file. The *reason*
I'm doing this might be confusing at first. My goal is to *ignore* any files added
to this directory from git... because we don't want to commit uploaded files. But
I would *also* like to make sure that this directory at least *exists* when I clone
the repository.

Find your terminal and add the empty `.gitignore` file:

```terminal
git add public/uploads/.gitignore
```

Next, open up the *real* `.gitignore` file - the one at the root of your app -
and ignore the entire `/public/uploads` directory. It's a bit weird, but thanks
to this, we will ignore *all* files in `public/uploads` *except* for the `.gitignore`
file we already added. 

[[[ code('174c8a3b04') ]]]

Why did we do this? Well, unfortunately, you can't add a
*directory* to git. So by adding this `.gitignore` file, it will guarantee that
the `public/uploads` directory will exist when you clone the repository. Honestly,
the file could be named *anything*, it's just sort of a common practice to use an
empty `.gitignore` file for this.

Check it out: create a new file in `public/uploads` called `foo`. Then, find
your terminal and run:

```terminal
git status
```

We see the new `public/uploads/.gitignore` file but we do *not* see the `foo`
file. That's perfect. Delete that.

## Moving the Uploaded File

Let's get to work inside of our controller to move the file. First, set the uploaded
file to a new `$uploadedFile` variable. And, unfortunately, the phpdoc on this
`get()` method is a bit generic... so it doesn't tell our editor that this will
be an `UploadedFile` object. Because I'm *obsessed* with auto-completion, let's
add inline doc about this: this *will* be an `UploadedFile` object - but not the
one from Guzzle - the one from HttpFoundation in Symfony.

[[[ code ('7926e67b91') ]]]

And guess what? This `UploadedFile` object has a *super* useful method on it:
`move()`! Give *it* the destination directory and it'll take care of the rest.
To get that directory, say `$destination =` and we need to get the path to our
`uploads/` directory. The best way is to read a parameter:
`$this->getParameter('kernel.project_dir')` - to get the absolute path to the
root of the app - then `/public/uploads`. Then add `$uploadedFile->move()` and
pass it `$destination`.

Hold Command or Ctrl and click this method. Ah, it returns a `File` object that represents
the new file. Let's see what this looks like: surround this entire call with `dd()`.

[[[ code('ae49182f23') ]]]

Alright team! Find your browser, refresh and re-post that upload. I... think it
worked! The dumped file object tells me that there *is* a new file in our
`public/uploads/` directory. Let's go check it out! There it is! Well, I *think*
that's it... but sheesh - the filename is *terrible*. Let's check its file size:

```terminal
ls -la public/uploads/
```

Yea... that looks correct - it's about 1.8 megabytes. So... we moved the file...
but that is a *terrible* filename. Let's fix that next.
