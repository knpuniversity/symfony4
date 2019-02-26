# Fixtures Uploading

Coming soon...

Check on our `DataFixtures/ArticleFixtures.php`. So here's how this worked. We
actually down here when we randomly load create 10 articles when we let our fixtures
and one of the things we do is,

this is the file that actually loads our article fixtures. So this runs when we run
`bin/console doctrine:fixtures:load` to give us a nice test data from is that this
doesn't really make any sense anymore. So we load 10 random images. This gives us a
nice create many function that we created in our Symfony tutorial, but that's not
important. It's basically the looping over and creating 10 new article objects. The
key thing for us is down here the `->setImageFilename()`. So we know that the image file
name needs to be just the name of the image inside of our uploads article is
directory. So `astronaut-` this string `.jpeg` and our our local fixtures,
we're using faker to select a random element of his `$articleImages`. So this is a
private property up here. So it's sending them all to `asteroid.jpeg`, `mercury.jpeg`
 or `lightspeed.png` and the way that worked for, because those are
just images that we hard coded right inside of our `public/images/` directory. But this
doesn't make any sense anymore because sure when we run `doctrine:fixtures:load`, it's
going to create 10 article objects and it's going to set these correctly in the
database. But when we go to render those on the homepage, for example, they're all
going to be broken.

There is no `upload/article_image/lightspeed.png` file. So we need to rethink how
we're doing our fixtures and I have a much better idea we need to do and our fixtures
class is actually fake. The process of uploading. Check us out. Inside of our article
fixtures, I'm going to create a `public function __construct()` and we're going
to use dependency injection to uh, get the `UploaderHelper` here. I'll hit `ALT + Enter` and
go to initialize fields to create that property and set up excellent.

The next thing I'm gonna do is actually go these three files. Here, I am going to go
and cut those and I'm going to move them into just into a different spot and you'll
see why in a second. But they're not going to need to be publicly assessment in more.
So I'm actually going to go into my `DataFixtures/` and create a new folder in here
called `images/` and I'm going to pace them. They're perfect and now they're no longer
inside of my `public/images/` directory. And for a reason that will become obvious in a,
I'm actually going to can make a commit that change. Okay, great. So the idea here is
that we can use the upload or helper down here and we can do is actually grab one of
those articles, images and actually just fake upload it. So check this out. I'm going
to say `$randomImage =` and I'm going to take this and copy it here

and I'll leave that there for now. So this is not one of those three image file
names. And then we can use the `UploaderHelper` and I basically am, I do is called the
upload function. Pass it the path to one of these images and basically say, hey,
pretend like `asteroid.jpeg` was uploaded, you know, and then do all the normal things
moving into the `upload/` directory. Give me a random file name and, and we're done. The
problem is, so we basically want to do is create a fake `UploadedFile` object. So in
our fixtures class we can do something like image file name that will be the 
`$imageFilename = $this->uploaderHelper->uploadArticleImage()`.

And then

we want to say kind of like `new UploadedFile()`, right. And somehow maybe point that
uploaded file to the, uh, one of our images. Problem is you can't really create a
fake uploaded file and uploaded file is really bound to the PHP uploading process. So
it just doesn't kind of work all that well,

I think. Yeah.

But here's the cool thing.

If you're actually go into our `UploaderHelper`, I'm the hold command or control and
click into this `UploadedFile` object. You can see this lives in `HttpFoundation/File`
and it actually extends a base class called file that lives in the same directory.
This is just, it's also lives in `HttpFoundation`. This is just a file. This is an
object that is represents a file. It doesn't matter if it's an `UploadedFile` or
anything else. And actually the vast majority of the functionality we're using comes
from this `File` class, not from our `UploadedFile` class. And we can just randomly
create file objects and point them to anywhere on the filesystem. And that works
fine. So check this out in `ArticleFixtures`. Instead of creating `new UploadedFile()`,
I'm going to say a `new File()` from `HttpFoundation`. And here I'm gonna pass it the
path to the random image. So `__DIR__.'/images/'` and then
I'll pass it a, the `$randomImage`. So it's going to be one of our images right here.
And then we take `$imageFilename` whenever the new filename is and that's actually
what we're going to pass him the database. Cool. So now an `UploaderHelper`. We need
to make this work not with an `UploadedFile` object, but with the parent class with
`File`. It's going to change the type in here to `File`. Again, make sure you get the one
from `HttpFoundation` and then I'm going to go to refactor rename.

Okay,

and just rename this to `$file`. Now if you look in here, everything looks happy except
for `->getClientOriginalName()` that doesn't exist on the `File` object that only exists
in the `UploadedFile` objects. We just didn't do a little bit of work here. We can do
is say if `$file` is an `instanceof UploadedFile`, then I'm going to say 
`$originalFilename = $file->getClientOriginalName()`. I'm going to do basically the same thing
as we're doing down here. We'll take care of this path info stuff in a second else.
The `$originalFilename` is actually just `$file->getFilename()`, so that would give
you the name of the file on the filesystem. Now down below, I'm actually going to
copy this path info and delete the `$originalFilename` whenever it's going to put
everything on this one line. So I'll say `$newFilename = Urlizer::utilize()`. And here
I'm going to say `pathinfo()` to put that part back. And then that unique any dash to
`$file->guessExtension()`. So we're going to get the `$originalFilename` and one of two
different ways. We'll still do the `pathinfo` trick and that's it.

This should work. So let's actually clear out the `uploads/` directory. Then I move over
and we'll run 

```terminal
php bin/console doctrine:fixtures:load
```

. And so yes and oh the file `src/DataFixtures/images/asteroid.jpeg` does not exist. 
Ha actually check this out. It uploaded two files perfectly. Look here, they're missing. 
Of course we're using `$file->move()`. So it actually worked for the first two articles, 
but instead of copying them into `article_image/`, it's actually moving them. So it's 
actually removing them from the old spot. So I'm going to go back and say 

```terminal
git status
```
 
and I'm going to make sure that I 

```terminal
git checkout src/DataFixtures/images
```

There we go. So I can get those two images back in the original spot. That's why I
committed them and I'll delete the two new guys. So we do want to use file move here
because we do want to move the uploaded file in normal circumstances. So to get
around this word is going to do a little bit of trick in our fixtures. We're just
going to take the original file name and copied to attempt directory before we pass
it in. So I'm going to say `$fs = new Filesystem()`. This is the filesystem from Symfony.
It's got a couple of Nice utility methods on it and they'll say 
`$targetPath = sys_get_temp_dir().'/'.$randomImage;` So that's where we're going to copy 
the path to and I'll say `$fs->copy()`.

Okay,

a copy of this path down here, we're going to copy whatever the random images,

okay

to target path. And I'll even say `true` shouldn't be a problem, but this is the
overwrite if there's already one there. Then down here for the `File()`, we're actually
going to pass it the `$targetPath`, the temporary path that the file of debt. All
right, try it one more time. 

```terminal
php bin/console doctrine:fixtures:load
```

Cool. Nowhere is that time.
And he had checked that out. We didn't delete our original ones, but now we have all
these great looking uploaded files. So if we go back and refresh.

Beautiful. This is awesome cause we're really living by our upload process and our
fixtures. We're not doing something fancy. We're literally using the upload system.
Oh and you can see all the thumbnails just popped in after that too. Now this is kind
of a lot of logic to have, um, inside of our written side of the middle of this
function. It's not super obvious what this does. So I'm actually going to copy this
and just refactor it down here to a private function. Let's say 
`private function fakeUploadImage()`. This is going to return a `string` and I'm going to paste all that
stuff in there and we'll just `return this->uploaderHelper()`. So it will select a random image
itself. It will fake upload that in. Return the path. So now up here we can just say,
you can believe all this stuff and say, `$imageFilename = $this->fakeUploadImage();`.
And with any luck, that should work with a nicely, what are fixtures? One more time.

```terminal-silent
php bin/console doctrine:load:fixtures
```

Yeah,

go back. It looks good. Refresh. Beautiful. All right. Fixtures are solid.