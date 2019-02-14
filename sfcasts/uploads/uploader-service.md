# Uploader Service

Coming soon...

We're starting to get a pretty good system going here. Honestly are moving the file
around. Got The unique file names that in the data on the article, but it is a lot of
logic. It's a lot of logic to having our controller and we need to reuse this
actually in the new action. So I typically like to isolate my upload logic into a
service class. So in the service directory though, this could go anywhere. Let's
create a new class. How about uploader helper? This will be our service class that'll
handle uploading.

Okay,

all things upload. We'll create a public public function upload article image and it
will take the uploaded files and argument. Remember the one from ht foundation and
we're drink return the string. That will be the string file name that was ultimately
saved. All right, so let's go steal some code for this. In fact, we are going to
steal pretty much the entire logic here and then paste it in. Make sure to retype the
are on you are alizer if you're using it. So we get the use statement up there and at
the bottom we will return the new file name. Perfect. Now this obviously won't work
in this era. Get parameter that's a controller shortcut only when you need a
parameter or any configuration or any service inside of another service, you need to
get that via dependency injection. So I'll make a public function construct and I'll
add a string.

Okay,

how about uploads path argument? So instead of just injecting the colonel route, dare
will actually inject this entire string right there. The path to where the uploads in
general should be stored. I'll put my cursor on that argument named gall and alt
enter to initialize that field to create that property and said it. Then down below
we can say this->uploads path and then /article image. Okay, we'll worry about
passing this argument to our servers. And a second we're just going to ignore that.
Pretend like it's going to work and go into article admin controller and use this. So
to get that service led a another argument here called upload or helper.

Okay.

And then down here we can remove all of this logic and we can just say new file name
= Upload or Helper->upload article image and pass the uploaded file. Cool. So there's
still a little bit of logic in here. We have the form logic, we have the lodge
affects you setting the file name on the article, but I'm comfortable with that and I
have a new method that really cool. You pass it any uploaded file, it's going to move
it into the correct directory and give it a unique name. So that is awesome. So let's
try it. Let's go back, refresh the article, edit page and boom, too surprising we get
an air.

Yeah

says can I resolve argument uploader helper in the edit and it says cannot auto wire
upload her helper argument uploads path of method. Construct is type ended string.
You should configure it's value explicitly. So this makes sense when we are using
auto wiring that works for services. It doesn't work for scalar arguments. No
problem. Open your config services.yaml. We could configure the specific argument for
this specific service, but if you've watched our Symfony series, you guys I know know
that I like to use the bind functionality, so since this argument's called uploads
path, I'll go under the_defaults bind and I basically say if any service anywhere
uses up the UN uploads path argument, then we will pass it percent kernel dot project
or percent /public /uploads over refresh.

Okay.

All right, looks good and let's try this to make sure I know the things are working.
I will just a empty out my Article Image Directory That's upload the stars picture
update and boom, the file empty strings does not exist. I made one mistake here.

Okay,

I get this really strange air. We're actually going to fix this later, but I want to
show you so that you can see it says the file empty quotes does not exist. What's
happening is here it was worse than guests get guests extension and internally
Symfony is actually trying to look at the physically uploaded file too to look at its
contents, but that file is missing. The reason is that I just uploaded a three
megabyte file. If I go over to my command line here, I can even Peach Dash I grep
upload, you'll see that my upland max file sizes two megabytes. So this is one thing
you always have to be aware of with PHP by the faulty upload, a Max file size is
really small. So you need to make sure that you bumped that up bigger if you need to
handle larger file sizes. However, in a bit we'll actually see that Symfonys layer is
going to take care of this for us, but we haven't applied any validation yet. So if
you get a really strange air like this, it could be because of your Max upload size.
So let's actually try a different one.

We'll do our astronaut again, 1.9 megabytes,

okay,

update and looks like, yes it worked. And so now that we have all the logic isolated
in here, let's just repeat that same thing instead of our new actions. There's very
little that I have to copy them. And a copy of these five lines are, so what our new
action will need the uploader helper.

Yeah.

And then down here we will just pace that will give that same, um, unmapped field off
of the entity. And then if there's an upload a file, we'll process it and set the
image file name.

Yeah.

So that should work beautifully. All right, next let's talk about validation.