# Setting up with the Symfony Local Web Server

Yo friends! It's file upload time! Woo! We are going to absolutely *crush* this
topic... yea know... because file uploads are a *critical* part of the Internet.
Where would we be if we couldn't upload selfies... or videos of Victor's
cat... or SPAM our friends with memes!?!?! That's not a world I want to live in.

But... is uploading a file really *that* hard: add a file input to a form, submit,
move the file onto your filesystem and... done! Meme unlocked! Well... that's
true... until you start thinking about storing files in the cloud, like S3. Oh, and
don't forget to add validation to make sure a user can't upload *any* file type -
like an executable or PHP script! And you'll need to make sure the filename is unique
so it doesn't overwrite other files... but also... it's kind of nice to *keep* the
original filename... so it's not just some random hash if the user downloads it
later. Oh, and once it's uploaded, we'll need a way to link to that file... except
if you need to do a security check before letting the user download the file. Then
you'll need to handle things in a totally different way.

Um... so wow! Things got complex! That's awesome! Because we're going to attack
*all* of this... and more.

## Downloading the Course Code

If you want to upload the *maximum* knowledge into your brain... you should
*definitely* download the course code from this page and code along with me. After
unzipping the file, you'll find a `start/` directory that has the same code you
see here. Open the `README.md` file for all the setup details... and a few extras.

The *last* setup step in our tutorials is *usually* to open a terminal, move into
the project and run:

```terminal
php bin/console server:run
```

to start the built in web server. You *can* totally do this. But, but, but! I want
to show you a *new* tool that I'm loving: the Symfony local web server.

## Downloading the Symfony Local Web Server

Find your browser and go to https://symfony.com/download. The Symfony local web
server - or Symfony "client" - is a single, standalone file that is *full* of
superpowers. At the top, you'll see instructions about how to download it. These
steps are different depending on your operating system - but it should auto-select
the right one.

For me, I'll copy this curl command, find my terminal, paste and enter! This downloaded
a single executable file called `symfony`. To make sure I can type that command from
anywhere, I'll move this into a global `bin` directory. By the way, you only need
to do these steps *once* on your computer... so you're done forever!

Unless we've mucked things up, we should *now* be able to run this from anywhere:
try it!

```terminal
symfony
```

Say hello to the Symfony CLI! It lists the most popular commands, but there are
a *lot* more - run:

```terminal
symfony help
```

Woh. We'll talk more about this tool in another tutorial. But, to start a local
web server, just say:

```terminal
symfony serve
```

Ah. The *first* time you run this, you'll get an error about running:
`symfony server:ca:install`. Let's do that:

```terminal-silent
symfony server:ca:install
```

You'll probably need to type in your admin password. This command installs a local
SSL certificate authority... which is *awesome* because when we run `symfony serve`,
it creates a local web server that supports https! Woh! We get *free* https locally!
Sweet!

Find your browser and go to `http://127.0.0.1:8000` - or localhost, it's the same
thing. Say hello to The SpaceBar! This is the app we've been building in our
Symfony 4 series: a news site for space-traveling friends from across the galaxy.

Try logging in with `admin1@thespacebar.com` and password `engage`. Then go to
`/admin/article`.

This is the admin section for the articles on the site. Each article has an image...
but until now, that image has basically been hardcoded. Click to edit one of the
articles. Our first goal is clear: add a file upload field to this form so we can
upload the article image, and then render that on the frontend.

But we're going to keep things simple to start... and take a deep and wonderful
look into the fundamentals of how files are uploaded on the web and how that looks
inside Symfony. Let's go!
