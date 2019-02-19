# Centralizing Upload Logic

We've got a pretty nice system so far: moving the file, unique filenames and putting
the filename string into the database. But it *is* kind of a lot of logic to put
in the controller... and we *already* need to reuse this code somewhere else:
in the `new()` action.

## Creating the Service

That's why I like to isolate my upload logic into a service class. In the `Service/`
directory - or really anywhere - create a new class: how about `UploaderHelper`?
This class will handle *all* things related to uploading files. Create a
`public function uploadArticleImage()`: it will take the `UploadedFile` as an
argument - remember the one from `HttpFoundation` - and return a `string`. That will
be the string filename that was ultimately saved.

Ok! Let's go steal some code for this. In fact, we're going to steal pretty much
all the logic here... and paste it in. Make sure to retype the `r` on `Urlizer`
to get the `use` statement on top. And at the bottom, `return $newFilename`.

Perfect! Well... not *perfect*, because the `$this->getParameter()` method is a
shortcut that only works in the controller. If you need a parameter - or *any*
configuration - from inside a service, you need to add it via dependency injection.
Add the `public function __construct()` with, how about, a `string $uploadsPath`
argument. Instead of just injecting the `kernel.project_dir` parameter, we'll pass
in the *whole* string to where uploads should be stored.

I'll put my cursor on that argument name, hit `Alt + Enter` and select initialize
fields to create that property and set it. Now, below, we can say
`$this->uploadsPath` and then `/article_image`.

Cool! Let's worry about passing configuring the `$uploadsPath` argument to our service
in a minute. After all, Symfony's service system is *so* awesome, it'll tell me
*exactly* what I need to configure once we try this.

For now, go back into `ArticleAdminController` and use this. Start by adding another
argument: `UploaderHelper $uploaderHelper`. And celebrate by removing *all* of the
logic below and replacing it with
`$newFilename = $uploaderHelper->uploadArticleImage($uploadedFile)`.

Dang - that is nice! There is still a *little* bit of logic here: the form logic
and the logic that sets the filename on the `Article` - but I'm comfortable with
that. And we now have this great new method: pass it an `UploadedFile` object, and
it'll move it into the correct directory and give it a unique filename.

## Binding the $uploadsPath Argument

Let's take it for a test drive! Go back, refresh the form and... it works! Naw,
I'm kidding - we knew this error was coming... but isn't it beautiful?

> Cannot resolve argument `$uploadHelper` of the `edit()` method: Cannot autowire
> service `UploadHelper`: argument `$uploadsPath` of method `__construct()` is
> type-hinted `string`, you should configure its value explicitly.

That's programming poetry people! And it makes sense: autowiring doesn't work
for scalar arguments. We got this: open `config/services.yaml`. We *could* configure
the *specific* argument for this *specific* service. But if you've watched our
Symfony series, you know that *I* like to use the `bind` feature. The argument
name is `$uploadsPath`. So, below `_defaults` and `bind`, add `$uploadsPath` set
to `%kernel.project_dir%/public/uploads`.

This means: *anywhere* that `$uploadsPath` is used as an argument for a method
that's autowired - usually a controller action or the constructor of a service -
pass in this value.

## Exceeding upload_max_filesize

Let's go see if that fixed things - reload. *Now* we see the form. To test this
fully, let's empty out the `article_image/` directory. This time, let's upload
the stars photo. Hit update.

Woh! The file "empty string" does not exist!? What the heck! Let's do some digging.
When we call `guessExtension()`, internally, Symfony looks at the contents of the
temporary uploaded file to determine what's inside. But... that file is missing!
In fact, PHP is telling us that the temporary file name is... an empty string!
It's madness!

Why is this happening? I'll give you a clue: the file we just uploaded is 3mb.
Go to your terminal and run

```terminal
php -i | grep upload
```

There it is: the `upload_max_filesize` in my `php.ini` is *2* megabytes, which
is PHP's default value. I have a *bunch* of things to say about this. First, make
sure you set this value to whatever you *really* want your max to be. You may also
need to bump the `post_max_size` setting - that defaults to 8 mb, and *also* will
cause uploads to fail if they're bigger than this.

Second, if you're getting *super* weird results while uploading, this is probably
the problem. And *third*, once we add validation to our upload field, we'll get
a really nice validation error instead of this crazy fatal error. Symfony has our
back.

So let's try a smaller file - our astronaut - it's 1.9 mb. Hit update and... yes!
It worked!

## Adding the Logic to new() Action

Now that all of our logic is isolated, we can easily repeat this in the `new()`
action. We *do* need to copy these 5 lines or so, but I'm happy with that.

Up in `new()`, add the argument - `UploaderHelper $uploaderHelper` - and inside
the `isValid()` block, paste! This uses the same form, with the same unmapped
field, so it'll all just work.

Next: let's talk about validation.
