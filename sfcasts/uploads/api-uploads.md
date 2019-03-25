# Api Uploads

Coming soon...

Once I got one last thing and that's how should file uploads. Look, if you're
building an API, the answer is you have two options. Your first option is you can
make your API end point look exactly like what we've been doing already. Like this
`uploadArticleReference()`. This is a valid way to make a upload end point and let me
show you the exact what I mean here. I'm going to use a Postman behind the scenes to
interact with our end point as it's currently written as if it were an API end point.
So we're going to do, the request is going to be requesting you were out, I'll go
copy are you are all here and we'll change that. `/admin/articles/51/references`
is are you were all to our um, upload end point and we're going to make
this a post request and on the body we're going to send it as form data and for the
key, remember we're looking expecting a key called `reference` and this is actually a
file. So I can actually choose a file here, which is our `earth.jpeg`. And that's
it.

So

before you send this, um, because we're using https, we're using Symfony as local
proxy postmen goes, it doesn't go through that local proxy. So you might get an air
bots are the certificate not working. If that's the case, you can go to post man
preferences. I've already done it. And make sure you turn an SSL verification off.
All right, so if we send this now sin, we get, Oh, what did we get here? Let's go to
the preview. Oh well send this to the log in page. Of course, this page requires
login just for testing things temporarily. Let's actually take off the `@isGranted()`
and we'll put that back on in a second. Go back and send it again. Beautiful. It
created it just fine. So sending form and data, uh, is going to, the way you do that
with your API client's going to look different. But the point is you can use an API
client like Postman or you know from the command line or whatever to make multipart,
um, requests to your server. And you will see lots of Api APIs that do it exactly
like this. But there's another way to do it. And if you're used to building API's
might actually feel a lot more familiar. So in this case,

we're actually gonna change the body to raw or actually to do JSON and noticed when I
hit JSON here, it actually automatically set the content type `application/json` had
her forming, which depending on your Api, you may, may or may not need. But that's a
good practice. So let's check this out. So let's think about this. If I want to
create photo upload a reference to a server using an API, usually if we're going to
make a request, we would send some data that looked like this `{"filename": "space.txt"}`

And maybe there's a whole bunch of other fields. Normally Api is
you just send JSON Body and it works. So how does it differ when you want to do a
file upload? Well, okay, we're doing a file upload. I need I in our case I still want
to be able to send what the file name is cause that's something that we store and I
also need to be able to send the actual data, the actual content itself. So how about
let's just, I'm just inventing this. Let's create a `data` key and we'll put the binary
data right here except putting binary data inside quotes is not a good idea because
what if the binary data actually includes quotes or you know, breaks the formatting
somehow. So that's why typically what you see is if an, if you have an API end point
that takes in raw data for an upload, you'll see that data base 64 encoded. So real
quick, I'm a search for "base64encode online" just to find some site that can base
64 to code some stuff for us. We'll type in some text that we want to

in code or actually we want to Ah, we went to encode. When you go to the Incode side
of things, there we go in code and you get this weird string like this, right? So
there is our base 64 encoded data and boom, I'm gonna put it right there.

So if we send this to our server is not going to work. And so we hit send and we get
yes, please select a file to upload. Basically it doesn't think we have an upload a
file field. So when you have an end point that's handling an upload, you can handle
it in two different ways. You can handle it in the traditional way like we're doing.
Or you can handle a JSON body and just to see how they both work. Let's make this end
point handle both. So check this out. Here's what we're gonna do. I'm gonna put an if
statement here says if `$requests->headers->get('Content-Type') === 'application/json'`
then we're going to do one thing else. We're gonna do our norm,
normal code. And the only thing that's really gonna differ is this `$uploadedFile` part
that's going to need to go into our, um, the owl spark.

Inside of here, what we're going to do is run a d decode that, uh, JSON into some
content and then we're going to handle it. And I'm actually gonna use the `Serializer`
for this. I'm a deserialize the object. Now earlier we use the `deserialize()`. If you
search for DC, realize inside of here and our update thing, we actually sent that
JSON Body that looked like our, um, our `ArticleReference object`. It only had one
field, but basically match the fields of the `ArcticReference` object. And we do see
realized directly into our `ArticleReference` a entity. In this case though, if you
look at our fields here, there is a `filename` field on our, uh, `Article` entity. Um,
but there is no data field. So basically this content doesn't really match our entity
and that's okay. So what I'm going to do to kind of simplify things is I'm going to
create a new class that looks just like our end point. So I'm gonna Create a new `api/`
directory and inside of there and new class, how about `ArticleReferenceUploadApiModel`
This is going to be a very simple class and we're just going to create a
`public $filename` property and a `public $data` property. Yes, I'm getting public because
just to make things even simpler, we're gonna only gonna to use this class in this
one spot. So why not make just public functions and I'm going to put some uh,

annotations above them to say that these are both required. So back in our
controller, I'll add a new argument down here, which is going to be 
`SerializerInterface $serializer`. And then we can say 
`$uploadApiModel =` and we use the `deserialize()` method, which takes three arguments. 
The first one is the actual JSON. So that's going to be `$request->getContent()`. 
The second thing is the type of object to it should be turned into, which is an 
`ArticleReferenceUploadApiModel::class`. And the third option is `json`, the format, 
we don't need a context. In this case, we're just going to, because we're not 
deserealizing into an existing object and we don't need to use any API groups and the 
Walmart here because we have added some, uh, and um, validation rules to this.
Let's go ahead and just check for, um, the validators from if they 
`$violations = $validator->validate($uploadApiModel)`. 

Oh, sure. That's violations. And If `$violations->count() > 0`
we're going to return the normal, `$this->json($violations, 400)`. Then down
at the bottom, let's just `dd($uploadApiModel)` so we can see if this guy's
working. Cool. All right. Spin it back over to the post man and sin. Got It. Look at
that. It's got the exact text we have here. Simple little step, but that's really
nice. And let's say, let's leave out the file name he send and great. We get a nice a
thing back that says the file name should not be blank. So this is an excellent
start.

Yeah.

Um, ultimately what I'm going to need is, um, the decoded data, not the encoded data.
So I'm actually gonna make our upload Api model a little bit smart. Check this out.
I'm gonna make this method called this, uh, this property `private`. Now if we only did
that, the validator actually the serialize, that would actually not be able to set
the data on that anymore. If you hit send, you can actually see it. It no longer
basically ignores the data key because there's no center for it and it's not a public
property. And then it fails validation. So your property either needs to be public or
you can create a `public function setData()` we'll have is taken a nullable string in
case the user forgot that field. And inside we'll say `$this->data = $data`. And I'm also
going to create a, another `private $decodedData` and will say 
`$this->decodedData = base64decode($data)`. And because this is private and doesn't have a setter,
it's not something that the user can send it in the API. If they send a Dakota data
string, it's going to ignore it. The only thing they can send his file name because
it's public and data because it has a setter.

Now go back and try

that.

Oh, and now is working perfectly. All right, so let's look at the controller here.
Ultimately, the else part of our thing has an `$uploadedFile` object. If we can create
an upload of file object, then it will go through validation. It will, uh, both of
the upload. Everything should just work. And as you remember from our fixtures, and
we can't actually create an `UploadedFile` object. That's something that's, that's
unique. Um, it has some unique properties of true PHP file uploads, but we can create
a `File` object as reminder of what I'm talking about in the `ArticleFixtures` at the
bottom, we actually created a `new File()` object that's the parent class of the upload a
file object. And we pass it a `$targetPath`, which was actually a file on the
filesystem. So we can do the exact same thing here. So check this out. First, let's
get a `$tempPath` set to `sys_get_temp_dir()` and obviously `'/sf_upload'.uniqueid()`, so
just some unique, unique, temporary found them on the filesystem. We're going to do,
here's extra. Again, we're actually going to write our JSON decoded data into this
file path.

Okay,

so first I need to get, be able to get the Dakota data saw, add 
`public function getDecodedData()`, which is going to be a nullable string. That's a 
`return $this->decodedData`. And
here we'll say `file_put_contents($tempPath, $uploadedApiModel->getDecodedData())`
Oh and exam not getting position on that. That's because seriously, we need to
add some inline documentation above so that Symfony PHP storm knows what the DCR
lives method is returning in this case and it's returning an Article Reference Upload
Api model. Now I can go down here and yes, to get nice auto completion.

Yeah,

and now we can finally say `$uploadedFile`, same variable name. His reasoning down here
`= new File()`. This time we want the one from `HttpFoundation`, but when you hit tab,
notice it gave me the full long class name. Why did I do that? Well, technically this
is fine. The reason it did that is that this is one of those rare cases where we
actually already have a class important in here with that file and namespace. So
technically if you want to use the file here, you have to aliases something. So how
about `FileObject`? I know a little bit, a little bit ugly. So down here we can say
`new FileObject()` and we'll pass it the temporary path. And now let's dd the uploaded
file. Phew. So high back to Bozeman. Hit send on that and yeah, look at that. Looks
like it's working. And I will copy that path name, go over to my terminal and I'll
open that in vim.

Oh my bad.

There's the directory and I'll even copy the full path name. Move over, open that
them. And there is our file content sitting in that file. So yeah, so let's take all
the dd and see what happens. Send it again and oh air attempt to call. Undefined
method. `getClientOriginalName()` on our File object. And this is coming down here on
line 84 I of course. So the `UploadedFile` object actually has a few methods of
`File` object doesn't most notably `getClientOriginalName()` is something that only
lives on the `UploadedFile` object so that's easy to fix. We can just go up here. I'm
going to create an `$originalName` variable on both sides here. I'll set it to
`$uploadedApiModel->filename` cause you don't have the original name to be
whatever the user is sending in our request. And down here I'll say original 
`$originalName =` we'll say `$uploadedFile->getClientOriginalName()`. We copied that
`$originalName` and we'll go down here to `setOriginalFilename()` and we'll just use that
and if it doesn't exist for some reason we'll use `$filename`. All right, let's see if
that was enough. Let's go over send and we got it.

Check this out. Here's the little temporary name. Here's the original name. We can go
back over to our web interface. They're their `space.txt`. We can download that.
And over here in S3, it is actually available

right there

and you can see it's using the SF upload is our temporary name here, but it doesn't
matter. That's just an internal detail. So last thing I want to clean up here is um,
because you're getting a temporary file up here, I do want to delete that temporary
file if it exists.

So the way I'll do that is all the way down here before persist. Um, but after we've
tried to like read the mime type from the file, I'm going to say if 
`is_file($uploadedFile->getPathname()`, then we're going to delete it. And I'm checking 
the if statement here because if this was an actual `UploadedFile`, then when we, 
then the, um, it may already be gone. I don't know. 
`unlink($uploadedFile->getPathname()` and just to
see if this is working. Let's `dd($uploadedFile->getPathname()`, we'll go back over the
post. Man, hit send. Cool. There's our absolute path. I'm a copy of that head back
over our terminal, open up that file. And yes, that is gone. We've got it. So
celebrate, remove that `dd()`.

I'm going to hit send them one more time and it works perfectly. Now that we know
that, don't forget to go back and add your security here  `@isGranted("Manage")` four
article `subject="article"`. Um, in a bigger project when I would actually do is, is
use functional tests for these end points and I would actually authenticate myself on
those. So that's why I would normally do, I guess that's it. Oh man, this is so much
fun. File up. Winning is sweets. You have clouds and thumbnailing in different
systems talking to each other. Honestly, it's not that hard. It's just a lot to keep
organized and a lot of different layers like fly system and leap. Imagine bundle a
lot of layers to talk together. So I hope this was super useful for you. I was fun.
And um, if you have ideas, let us know and, and, and let us know what kind of cool
things you're building on it out there. All right guys. See you next time.
