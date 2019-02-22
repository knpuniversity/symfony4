# Uploading Private Files

Coming soon...

What makes these article references that we're uploading special are that these need
to be private. These, these can't be just be public files. He's a resources that are
meant just for the author. The author can download them later, which means that we
need to actually check security before they, someone can just access them. So the
process for uploading these is going to be a little bit different.

Okay.

But we're going to start in a very similar way, which is that inside of our service
directory, open uploader helper, because this is going to be our service that handles
everything related to uploading.

So I'm gonna create a new function down here called Public Function Upload Article,
reference those, we'll take the file argument and return a string. So it pretty much
the same as our existing method. We're not doing an existing file name because
article references won't be, um, you're not going to update one. You'll, if you want
to change a file, you would just delete the article reference and delete the file and
then make a new article reference and we'll do that later. So no need to pass an
existing file name to delete and it's out here for now. We're just going to dd that
file object back in our controller. Let's actually get to work here. We're going to
do very similar to what we did before. We'll set this variable to upload a file
object. I'll do some inline documentation that this is a file upload object from http
foundation. And then we'll say file name equals, and then we'll use the uploaded a
helper, which of course we need to actually get as an argument. First uploaded
helper.

They're going to say finally m = uploader helper air upload article reference and
we'll pass in the uploaded file. Cool. So that won't work yet. But eventually this
would return the uh, the new file name that we stored on our filesystem. And assuming
that works, which I know it doesn't work yet, the next thing we need to do is
actually create the article reference, um, objects that some data on it and save it
to the database. So actually open that entity class, uh, article reference. Right now
this is very normal. It has just the properties on it and it has get her set instead
of for everything. Um, which is how I like to start. However, sometimes I like to
refactor my object to a, in this case I want to do a little riff factory. I'm
actually going to find these set article method and Rubin article references are
really something that we're going to create once and uh, created public
function_under construct. We're actually going to pass the article right there. It's
just a nice thing. I'm looking orphans as always need to have an article. The article
is not going to change. There's no reason to have a center. So it just, just totally
optional. But this feels really good to me.

Now up in our controller I'll say article reference people's new article reference

acid in the article and then we're going to set some date on there. So obviously the
most important thing we need to set is the file name. That's the file name that we
stored. Uh, the, the final stored file name, unique stored filing, that room put on
there. Um, but remember there's a couple other pieces of information that we added to
our article reference, like set original file name so we can keep track of what it
was originally called on the user's filesystem. It's in this case we can use uploaded
file Arrow, get client original name. Now technically that can sometimes be no, I'm
not actually sure if that's something that happens in any realistic scenario. So I'm
just going to use question, question, file name. So if for some reason client
original name is not set, we will fall back to the file name, which will just be the
ugly file name that we uploaded. And another thing, and we're not actually gonna use
it anywhere, but setting mime type. So same thing we can say upload a file Arrow, get
mine type. I'll try to get the mime type of the apple to bile technically that can
also return. No.

And so if their returns and all, we'll use application /octet stream. That's a
generic, we have no idea what this file is. Mime type. Cool. Then we need the entity
manager interface argument so that we can now call entity manager, Arab persist
article, reference entity manager,->flush, and then we'll just redirect those return
redirect to route and we're going to redirect back to admin article edits. That's the
page that we're on and this takes the articles. Id see that over here on our edits
and buoyant.

Okay.

All right, cool. So it should, if any with any luck, hit our dye statement here. So
let's go back over and we already have simply best practices is selected. Let's hit
upload and perfect there. We got uploaded file coming from our uploader helper. All
right, so here's the tricky thing. We can't just go into upload or helper and use the
fly system filesystem like we did before to write the file cause that writes
everything into the public /uploads directory.

And this file needs to be private so we cannot put it in the public session uploads
directory, need to put it somewhere else and what that means at least when you're
storing things in the filesystem, this will be different later. One we're on the
cloud. But what this means is that we need a second fly system filesystem. So they're
going to go into config packages and open the one up filesystem..yaml file. I'm going
to paste the public apples adapter and make a private uploads adapter. You can store
the files anywhere, but the important thing is that they're not in the public
directory. The Var directory is sort of a made for this type of thing. So we'll say
var /uploads and those I could use the uploads dirt name here. That's really meant
for public uploads. This uploads here, we're not going to need to reference anywhere
else because we're going to have to do something totally different to actually create
the public path, uh, make these downloadable. You'll see that in a second.

Then down here for the filesystems, we will do the same thing. We'll make a private
privates uploads filesystem and we will use the private uploads adapter. Cool. So now
we have two filesystems. Next, because we're going to need to, an uploader helper
were already passing the public upload filesystem. As an argument, we're now going to
need to also get the private upload filesystem. So first go into services.yaml and
remember if you go onto the_defaults and bind, we are configuring the public upload
filesystem as a pub, as a global bind. Um, we're gonna do the same thing for the
private upload filesystem. So we'll say private upload filesystem and we'll change
the service id to be the private uploads filesystem. Now we can copy that argument
name and this is going to allow us to inject it into our upload or help or I'll
actually add it as the second argument. So filesystem interface and then private
upload filesystem.

Okay,

over here I have filesystem. I'm gonna create a new property called private to the
filesystem. And that's what will set down here. This era, private filesystem

e

= private upload filesystem.

Cool. So most of the logic actually in upload article image is the same exact logic,
whether it's going to the public system and the filesystem. We need to figure out the
file name. We want to save it to when you actually need to stream it there. The only
thing that this method has that we don't need is this existing file name stuff down
here. We don't actually need this delete functionality. So I'm gonna copy all of this
code all the way down through the EFC clothes and then down to the bottom of this
file and create a new private function called upload file. This will take in the file
object that we're uploading and we're also going to pass in the directory name.
You'll see what that is in a second. And then a flag for boule is public so that this
method knows whether to store things in the public or the private filesystem. So for
now I'm just gonna paste that exact logic and at the bottom we're going to return the
new file name. Oh, and I should also probably add a return type here.

Now the first thing I'd do is handle this is public. So based on whether that is true
or false, we're going to need to use different filesystem. So we'll say filesystem =
is public and if it is we'll use this->filesystem. Otherwise we'll use this->private
filesystem and instead of using this or our filesystem down here, we'll just use
whichever filesystem was selected. The other thing is we have the article image sub
directory hardcoded in here. That's what that directory argument is. That's basically
what directory inside the filesystems should his file I'll be stored, so we will use
the directory variable there. This all allows us to finally go back up here to our
upload article image and all that code that we just copied. We can delete and replace
it with new filename = this->upload file. We'll pass the file and then we need to
pass it the directory, so that's going to be self article image and then whether or
not it's public, which is true, we do want to use the public filesystem and we can do
basically the same thing down here for upload article reference. The first thing we
needed to do is create another constant up here called article reference.

And then down here I'll say return this Arrow, upload file, past file, self, colon,
colon, article reference, and then false for the is public argument. This time we
want that to go to the private filesystem. Okay, I think that's it. So let's give
this guy a try. Go back over it, refresh and, okay. No aired, no idea if it works
because we're not rendering it anywhere. So let's go check out our Var directory, Var
uploads, article, reference, Symfony best practices. We've got it. We still have no
way to make a public link to this, but we'll work on that next.