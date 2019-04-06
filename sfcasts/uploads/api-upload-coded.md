# Coding the API Upload Endpoint

Our controller is reading this JSON and decoding it into a nice
`ArticleReferenceUploadApiModel` object. But the `data` property on that is still
*base64* encoded.

## base64_decode from the Model Class

Decoding is easy enough. But let's make our new model class a bit smarter to help
with this. First, change the `data` property to be *private*. If we *only* did
this, the serializer would *no* longer be able to set that onto our object. Hit
"Send" to see this. Yep! the `data` key is ignored: it's not a field the client
can send, because there's no setter for it and it's not public. Then, validation
fails because that field is still empty.

So, because I've mysteriously said that we should set the property to private,
add a `public function setData()` with a nullable string argument... because the
user could forget to send that field. Inside, `$this->data = $data`.

*Now*, create another property: `private $decodedData`. And inside the setter,
`$this->decodedData = base64_decode($data)`. And because this is private and
does *not* have a setter method, if a smart user tried to send a `decodedData`
key on the JSON, it would be ignored. The only valid fields are `filename` - because
it's public - and `data` - because it has a setter.

Try it again. It's working *and* the decoded data is ready! It's a simple string
in our case, but this would work equally well if you base64 encoded a PDF, for example.

## Saving a Temporary File

Let's look at the controller. We know the "else" part, that's the "traditional"
upload part, is working by simply setting an `$uploadedFile` object and letting
the rest of the controller do its magic. So, if we can create an `UploadedFile`
object up here, we're in business! It should go through validation... and process.

If you remember from our fixtures, we can't *actually* create `UploadedFile`
objects - it's tied to the PHP upload process. But we *can* create `File` objects.
Open up `ArticleFixtures`. At the bottom, yep! We create a `new File()` - that's
the *parent* class of `UploadedFile` and pass it `$targetPath`, which is the path
to a file on the filesystem. `UploaderHelper` can already handle this.

In the controller, we can do the same thing. Start by setting
`$tmpPath` to `sys_get_temp_dir()` plus `'/sf_upload'.uniqueid()` to guarantee
a unique, temporary file path. Yep, we're literally going to *save* the file to
disk so our upload system can process it. We *could* also enhance `UploaderHelper`
to be able to handle the content as a *string*, but this way will re-use more logic.

To get the raw content, go back to the model class. We need a getter. Add
`public function getDecodedData()` with a nullable string return type. Then,
`return $this->decodedData`.

*Now* we can say: `file_put_contents($tmpPath, $uploadedApiModel->getDecodedData())`.
Oh, I'm not getting any auto-completion on that because PhpStorm doesn't know what
the `$uploadedApiModel` object is. Add some inline doc to help it. Now, `$this->`,
got it - `getDecodedData()`.

*Finally*, set `$uploadedFile` to a `new File()` - the one from `HttpFoundation`.
Woh! That was weird - it put the full, long class name here. Technically, that's
fine... but why? Undo that, then go check out the `use` statements. Ah: this is one
of those rare cases where we already have *another* class imported with the
same name: `File`. Let's add our `use` statement manually, then alias is to, how
about, `FileObject`. I know, a bit ugly, but necessary.

Below, `new FileObject()` and pass it the temporary path. Let's `dd()` that.

Phew! Back on Postman, hit send. Hey! That looks great! Copy that filename, then,
wait! That was just the directory - copy the *actual* filename - called `pathname`,
find your terminal and I'll open that in `vim`.

## Getting the "Client Original Name"

Yes! The contents are *perfect*! So... are we done? Let's find out! Take off the
`dd()`, move over and... this is our moment of glory... send! Oh, boo! No glory,
just errors. Life of a programmer.

> Undefined method `getClientOriginalName()` on File.

This comes from down here on line 84. Ah yes, the `UploadedFile` object has a few
methods that its parent `File` does not. Notably `getClientOriginalName()`.

No problem, back up, create an `$originalName` variable on both sides of the if.
For the API style, set it to `$uploadApiModel->filename`: the API client will
send this manually. For the `else`, set `$originalName` to
`$uploadedFile->getClientOriginalName()`. Now, copy `$originalName`, head back
down to `setOriginalFilename()` and paste! And if for some reason it's not set,
we can still use `$filename` as a backup. But that's definitely impossible for our
API-style thanks to the validation rules.

Deep breath. Let's try it again. Woh! Did that just work? It looks right. Go refresh
the browser. Ha! We have a `space.txt` file! And we can even download it! Go check
out S3 - the `article_reference` directory.

Oh, interesting! The files are prefixed with `sf-uploads` - that's the temporary
filename we created on the server. That's because `UploaderHelper` uses that to
create the unique filename. And really, that's fine! These filenames are 100%
internal. But if it bothers you, you could use the original filename to help make
the temporary file.

Anyways... we did it! A fully JSON-driven API upload endpoint. Fun, right?

## Removing the Temporary File

Before we finish... and ride off into the sunset, as champions of uploading in Symfony,
let's make sure we delete that temporary file after we finish.

All the way down here, before persist, but *after* we've tried to read the mime
type from the file, add, if `is_file($uploadedFile->getPathname())`, then delete it:
`unlink($uploadedFile->getPathname())`.

The `if` is sorta unnecessary, but I like it. To double-check that this works,
let's `dd($uploadedFile->getPathname())`, go find Postman and send. Copy the
path, find your terminal, and try to open that file. It's gone!

Celebrate by removing that `dd()` and sending one last time. I'm *so* happy.

Oh, and don't forget to put security back: `@IsGranted("MANAGE", subject="article")`.
In a real project, wherever I test my API endpoints - like Postman or via functional
tests, I would actually *authenticate* myself properly so they worked, instead of
temporarily hacking out security. Generally speaking, removing security is, uh,
not a *great* idea.

Hey! That's it! We did it! Woh! I had a *ton* of a fun making this tutorial - we
got to play with uploads, a bunch of cool libraries and... the *cloud*. Uploading
is *fairly* simple, but there *can* be a lot of layers to keep track of, like
Flysystem and LiipImagineBundle.

As always, let us know what you're building and if you have questions, ask them
in the comments. Alright friends, seeya next time!
