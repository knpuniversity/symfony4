# Integration Test

Coming soon...

Let's add in another test method for the other method and mailer, which is test. And
we'll just send author weekly report and message. So down on the bottom here I'm
going to say public function test. And then I'm going to say integration send author
weekly report message. You'll see why I'm using, I'm using the word integration
because as you'll see in a second, this is not going to be a true unit test. This is
going to be an integration test. And then let's see appeared. Let's copy everything
from the initial method except for the asserts and pays those down here. And then
down here we're actually going to call, send author weekly report message. Um, and
this needs actually the user, but it also needs an array of articles. So let's just
create one articles. We'll say article articles in new article. And the way this
article is used is it's actually passed into our template and we print the title in
there. So listen, it doesn't really matter, but let's at least make sure that it has
a title,

black holes, ultimate party duper.

It's like an argument we'll just pass. And right with that one article in it. Great.
Now let's think about this right now we are mocking all of the dependencies and that
is a, that would be a way that we could unit test this. If we did that, we would
actually need to um, mock a couple of things in here. We'd probably need to make sure
that uh, get output from HTML return some PDF string here and we could even assert
that that was passed down here to be attached method. So unit testing, this is um,
possible and would probably be fairly fairly useful.

[inaudible]

another option is to do an integration test, which is like a unit test except instead
of using a mock twig and a mock to PDF, we would actually have used the real twig in
their real PDF. And what we'd be testing then is we'd be testing that the whole
process of rendering the twig template actually works and that using the WK HTML, the
PDF binary actually works. They may get back a real PDF. The downsides to integration
tests are that they are slower, they're a little bit less pure, but sometimes they
can because they're, because they're actually testing the real world, they can be a
bit more useful. So I'm going to transform this into an integration test.

Okay.

Not really. The thing that scares me is that I really want to test here is the
process of rendering the twig template and uh, executing the um, uh, getting the PDF
generated. So the entry point in the mailer I'm okay with. So mocking those, I don't
really care if it's used as a mock entry point look up and I don't really care if
this is used as a real mailer. In fact, I don't want this to use a real mailer
because that would actually send a real email [inaudible].

So we're only going to mock some of our dependencies now to make this class able to
be an integration test we needed to do first we need to, instead of extending test
case, we're going to send something called Colonel test case. This extends the normal
test case, but it gives us the ability to boot Symfony in the background
specifically. It gives us the ability down here and our method to say self, colon,
colon, boot Colonel, this boots Symfonys Colonel, which basically that boots Symfonys
service container in the background and that allows us to fetch real services out of
a container.

Yeah,

so we'll leave Symfony mailer mocked, we'll leave the entry point look up mocked, but
for the PDF, let's get the real PDF instance. The way he can do that is by saying
self, colon, colon container that could do Symfony's container and then you can fetch
things out by using the auto wire, double their auto wire and we'll type in. So we
actually want to fetch out PDF, ::class, and then same thing down here for twig,
we'll say self, colon, colon, container,->get, and we'll use the same environment,
colon, colon, class as an argument so that we now have real arguments. So yes, this
means that you, the downside is you do need to have WK HTML, the PDF installed on
your system and configured correctly because it's really going to use it. All right.
Now down here, we don't have any asserts yet down here. Um, let's just assert
something basic. Let's assert count that one is equal to email,->get attachments and
we can go further. We could actually read off the attachments, look at what's inside
of them and actually verify that it is a PDF inside of there. Um, but we'll just do
that for now.

Alright, so let's try this. Let's spin over or a piece. We've been slashed piece, but
unit you'll notice it's slower and it this time there's two interesting things. One
is there's a lot of more deprecation failures and that's because this is actually
booting up or Symfony application. So any duplications alarm or applications are
getting hit instead of or simply application. This is going to be a little bit
annoying, but we're going to ignore for right now the big deal is though it failed,
at least for me. And check this out. So something about APCU is not enabled. ABCU is
a caching extension which I don't have on my computer. The question is why is it
trying to use APCU and suddenly,

okay,

well the answer is that we actually are booting and using our Symfony application.
For some reason our Symfony application is trying to use APCU. The answer to this is
something we did many that go on this project and it deals with the caching layer, so
if you open up config packages, cache.yaml in a previous tutorial, we actually
configured that. The caching system

in our app, the adapter, which is kind of the way it caches does a cache to the
filesystem, does a cache to reddest as a cache to APCU was going to use a parameter
called percent cache adapter. The reason we did that is because in config services
.yaml, we set cache adapter to cache that adapter that APCU. So we basically told
Symfony, Hey, whenever you need to cache something at runtime, you should use APCU.
Now, the only confusing part is it might be that, Hey, if we don't have that
installed my local computer, then why the heck is the website working? Shouldn't we
be getting the same error here? We don't get the error here because in services_dev
dot. Yammel which is a file that's only loaded in the dev environment. We overrode
the cache adapter to set up the filesystem.

So this is great. And at the end of the development environment, we didn't have to
worry about having anything installed. We can just use the filesystem as a cache
adapter. But then when we deployed to production and the prod environment, it would
use the default cache adapter that APCU so I'm not really, the problem now is that
when we run our tests, we're in the test environment and since the test environment
doesn't load services_depth. Dot. Yammel and we do have a services_test at AMA here,
but there's nothing relevant in it. In fact, you can delete this from your project.
Um, it's using the default cacheed after that. APCU. So probably what we should've
done instead is we should've said let's default to cache dot and after that to
filesystem and then only in the prod environment, change it to ABQ. So what I'm going
to do is I'm going to go to refactor, rename and change this to services_prod at that
emo. And inside of there, here is where we use APCU. So now we should be using
filesystem in our test environment, which is great.

And now we can run over here and actually run that test. And if you ignore the
definition that says it works and this actually generated the PDF in the background.
Um, so it really is making sure things work. If you want to see that real quick, you
could say email dump email, get attachments. When you rerun that VAR dump email, get
attachments.

If you rerun that. Yup, you'll see it's super ugly. You can see it gives you this,
uh, uh, data, part attachment thing and you'd see all the PDF inside of there so you
can assert things on there. But I just wanted to prove that it is actually generating
a PDF in the background. It's using the real stuff. So next, the last type of test we
haven't talked about is a functional test. And this actually gets a bit more
interesting, a questions of how do we assert an email was sent in a functional test,
and also how do we prevent it from actually sending? That's nice.