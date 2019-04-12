# API-Style Uploads

How does a file upload work if you're building an API? Well, you have two options.
First, you can make your API endpoint look *exactly* like what we already built
in `uploadArticleReference()`.

## Using our Current Endpoint with an API Client

Let me show you what I mean. I'm going to use Postman to interact with our endpoint
as if it were truly meant to be an API endpoint used by API clients. For the
URL, copy the URL in the browser, paste, and change `/edit` to `/references`. Yep,
that'll hit our controller. Make this a POST request.

What about the *body* of the request? What should that look like? Well, because
we wrote our endpoint to basically handle a traditional form-submit, the format
will be `form-data`. For the key, remember that we're expecting the file data on
a field called `reference`. Change the field type to "file" and select `earth.jpeg`.

That's it! Before trying this, our site is being served over https thanks to the
Symfony local web server and some certificate magic it does behind the scenes.
But Postman doesn't *know* to use that magic, so the certificate won't work. In
the Postman preferences - I've already done it - turn SSL verification off. Or
you can run the Symfony web server with the `--allow-http` flag if you want to avoid
this.

Ok, send the request! Oh... what's this? Check out the preview. The login page,
of course! Uploading requires a valid user. Just to play around, let's remove
the `@IsGranted()` temporarily.

[[[ code('05bacdc7a2') ]]]

Try it again. Beautiful! It works!

So, the *first* way to build an upload endpoint for an API is... like this! An
endpoint that requires the multipart form data format that we checked out at the
beginning of this tutorial. Any API client will be able to work with this and a lot
of API's are built this way.

## Pure API Endpoint with JSON: base64_decode

But, there's another way. And if you're building an API, this might *feel* a little
bit more natural. To see it, change the body to "raw", or actually, to JSON so
we can set the request body manually, instead of Postman building it for us from
the nice `form-data` GUI.

When we change to use a JSON body, Postman helpfully auto-sets the `Content-Type`
header to `application/json`, which depending on your API, you may or may not need.
But it's always a good practice.

Ok, let's think about this from the perspective of a *user* of our API: if I want
to send a file reference to a server, usually I'd expect the body to look something
like this `{"filename": "space.txt"}` with, maybe a bunch of other fields. Because...
in an API, the request usually contains *JSON*! Not the weird form-data format.

Of course, `space.txt` isn't the *content* of a file, but we *would* still probably
want to be able to send the original filename. For the *data*, hmm, I'm just making
this up, what if we create a `data` key and put the binary data right here? That's
great! Oh, except... you *can't* put binary data in JSON: it's just *not* supported.

API's work around this fact by expecting the client to base64 *encode* the data.
Search for "base64 encode online" to find a site that can base64 encode some stuff
for us really easily. Let's type in some text that we want to encode and... oops!
We're on the *decode* side. Switch to encode and... there we go! We get this simple,
encoded string. By the way, the main downside to this approach is that base64 encoded
data is slightly bigger than the original data. On small or medium files, this makes
very little difference. But if you're uploading *huge* files, using the base64
encoded data will slow things down, because more data needs to be transferred.

Anyways, paste *that* on the `data` key. We know this won't work... because our
controller is *totally* not set up to receive JSON, but pff. Let's try it anyways.
Hit send and... validation error!

> Please select a file to upload

## Deserializer & A Model Class

Love it! Let's get to work. Back in our controller, to see what it looks like,
let's make this endpoint capable of handling *both* ways of uploading files:
form-data *and* JSON.

We can figure out which situation we're in by looking at the `Content-Type` header.
So, if `$request->headers->get('Content-Type') === 'application/json'`, we'll do
our *new* thing, else, run the normal code. And... this is pretty cool... the
*only* part that'll *really* be different is the `$uploadedFile` part. Move that
into the `else.`

[[[ code('e82860b812') ]]]

In the first part of the if, just like a normal API endpoint, we need to decode
the JSON request content into something useful. To do that, let's use the serializer!
Search for "deser", there it is. Earlier, we used `deserialize()` to turn the
JSON into an `ArticleReference` object. That worked because the keys in that JSON
matched the property names in that class.

But in this case, look at the fields: `filename` and `data`. We *do* have an
`originalFilename` field, and we *could* rename the `filename` key to that...
but we definitely do *not* have... and do not *want* a `data` property on
`ArticleReference` that's equal to a base64 encoded version of our file. That makes
no sense.

This is a *classic* case where the data of an endpoint doesn't match the structure
of our entity. And that's cool! Instead of using the entity, we can create a new
*model* class.

Inside `src/`, let's create a new `Api/` directory - just for organization - and
inside, a new class: how about `ArticleReferenceUploadApiModel`. The *whole* point
of this class is to help us deal with the data for this endpoint. So, its properties
should match the data. Add `public $filename` and `public $data`.

[[[ code('b24247546a') ]]]

Yes! Gasp! They're public! Because this class will only be used for this *one*,
*narrow*, purpose, it's ok to make life a bit easier with public properties. If
this makes you want to scream and tackle me, I get it! Just make them private and
add the getter & setter methods. That will work perfectly.

While we're here, don't forget about validation: add `@Assert\NotBlank` above
both of these.

[[[ code('96b12c9d37') ]]]

We're ready! Back in the controller add a new argument at the end:
`SerializerInterface $serializer`. Then, it's beautiful, really
`$uploadApiModel = $serializer->deserialize()`. This takes three arguments: the
raw JSON - `$request->getContent()` - the *type* of object it should be turned
into - `ArticleReferenceUploadApiModel::class` - and the input format, `json`.

[[[ code('c806dc59bb') ]]]

We don't need a context this time, because we're not deserializing into an existing
object and we don't need to use groups.

And because this object has some constraints, we'll need to check validation up
here: `$violations = $validator->validate($uploadApiModel)`. And if
`$violations->count() > 0`, return the normal, `$this->json($violations, 400)`.

[[[ code('a9b82a1888') ]]]

At the bottom, let's `dd($uploadApiModel)` so we can see if this crazy idea is
working.

[[[ code('65c54e695f') ]]]

You ready to try this? Spin back over to Postman, high-five someone near you and...
send! Hey! Check out that *beautiful* dump! The text is still encoded, but that's
a *killer* first step. Leave the `filename` blank to check validation. Looks great.

Let's finish this next: we still need to base64 *decode* that data and push it
into our normal file upload system. Let's do that in a clean way that we can love.
