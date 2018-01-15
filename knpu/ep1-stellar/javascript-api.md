# JavaScript & API Endpoints

The topic of API's is... well... a *huge* topic and *hugely* important these days.
We're going to dive deep into API's in a future tutorial. But... I want to at *least*
show you the basics right now.

So here's the goal: see this heart icon? I want the user to be able to click it
to "like" this article. That means we'll need to write some JavaScript and send an
AJAX request to an API endpoint. That API endpoint will *return* the *new* number
of likes. Well... all 

this little heart icon where you can. Heart an art a whole. And obviously this
is just a hardcoded value. We're going to hook this up to work. Well kind of
work when we click the heart. The heart will fill it will send an ajax request.
That will return a new number of comments and we'll update this number here. So
let's do this let's creating new JavaScript file.

And by the way you look at the bottom of our base layout we do have J.

Querrey to work with already. So in the public directory let's create a new J S
directory. And inside their new file called article on her score show. J.S. The
idea is that will include this on the article show page and it will take care
of the hot new heart functionality.

Oh and a new document that ready block. Now go now go to show that HDMI. And.
If you scroll down a little bit you'll see where the hardcoded functionality
is. 5. Hearts. So we need to do is we need we need to do something when this
link is clicked and this 5 is actually what we're going to update. So I'm
actually going to add a couple of. Make a couple of changes here. On the. Link
on the link. I'm going to add a new JAST dashed like Dasch article. Class. Add
a spin around the 5. And call that. Give that a JAST dashed like dassent
article. Count class we use those instead of for JavaScript.

First. Copy the Jass like article. And then we'll just write some very
straightforward Jaghori. Find that element in on. Click. Will. Call this
function.

First will call that prevent default. So if the link isn't actually clicked.
And then I'll. Set the link. That was clicked itself. To Dasan overruns that
current target. So link is now the link that was quit. To change the heart. Can
say links that toggle class.

F A Dash hard dash 0 Tombo. Class. F. A dash heart. That will. Make. The heart.

Fill in. Then go empty fill in and then go empty. And then for now to actually
fill in the.

Article. Count value. At the bottom of a say Dyas and open parentheses. Find
that element and say each T.M. will. Test.

Simple enough. So all we need to do is include this on our page. Of course
inside based at each tweet we could include the script tag right down here
manually but we don't want this. Script tagging included on every page. We only
need it in the article show page.

So how do we do that. After all if we put the script tag inside of the body
block.

Then it's actually going to show up instead of our final. Each team all way up
here.

We need to show down at the bottom of the JavaScript's. So how do we do that.
By overriding the block. JavaScript's. Set a new block. JavaScript's. Then say
in the block. Then a lot of script tags with SIRC equals. Article underscore
show. Now there is a problem with this but let's see it. If you go on refresh
now. Doesn't work.

If you inspect the element of the console. It says Dollar is not defined. You
can source code you'll see why. You scroll down towards the bottom you'll see
that there is literally only one script tag on the page. Ah that makes sense
when we overwrite a block. We completely replace the block. So we completely
replaced all of these blocked tags. What we want to do is add to the block not
entirely replace it. To do that. Say Curly Curly parents. And that's it. Now go
get the parent JavaScript's block content print it and then we add ours at the
bottom. That's why we put our CSSA and a stylesheets block in our JavaScript in
a JavaScript's block. Now. Refresh. How.

It works. Okay so next we need to make an API endpoint that we can.

That we can send this to.

And that API endpoint when we return a new number of hearts that should show on
the page. So if we think about the API endpoint. It's going to need to include
the article slogs so that we know which article should be like it.

It's a fine article controller. Let's make a new public function called the
toggle. Article. Part.

And above that we add our app route. Let's say slash news slash curly brace
slug just like our previous you will slash. Heart. And give this. A name. Of.
Article. Tavel. Part. Because we have the curly brace slug in the route of a
dollar sign slug arguments. I'll start with a to do. To actually hearts slash
on heart. The article. That's something we'll do in the future when we have a
database. Sybel fake doing the heart slash on heart. Now we want this endpoint
to return Jaison.

And remember to remember the only rule but a symphony control or is it must
return a symphony in response object. So there's nothing stopping us from
saying return. The new response. And then using Jaison code to pass some values
back. That's perfect. But because Jason such. But if you want to return Jaison
there's an easier way. You can say return new Jaison response. That's a
subclass of the normal response object and you can just pass it in array of
parts equal Arrow. Rand. Five hundred. And that will do the Jaison and code for
you and it will also set the content type content type header to applications
last Jaison which is very important. For your. Javascript to understand things.

Alright so let's try this in the browser first. If you go back and add slash
part on the end. And there it is. That is our first.

API endpoint and Symphonie and it could not be easier.

But does it best practice since this modifies is going to eventually modify
something on the server.

We shouldn't be able to make a good request to it. What I mean is at the end
we're going to add another option called methods equals and then double quotes
we're going to say post. As soon as we do that we can no longer make a get
request to that route. It doesn't match the route. Now if you run then consul
diva router.

You'll see a new route but you'll see that it only responds to post request
which is pretty cool. All right so our API endpoint is ready. Let's pick up the
javascript. Copy the route name. In article showed that J.S..

We can't use twig code to generate a you were added to that route. There
actually is a way to do this. A really cool bundle called F West jazz robing
bundle which you should check out but there's a different way to do it.

Inside of our show template. I'll go back up to the heart heart section. I'm
actually going to fill in the URL. Right on our. Link. And for the. Slog. I'll
pass that and set it to a slug variable. We actually don't have a slug variable
yet. If you look back an artifact controller. Were only passing two variables
into our show template. So let's also passed and. The Sloggett.

Snout. This link will fill out. If you make no other changes. And go back to
the original you well. You'll see that the. You see the you were Al.

Is now correct. We did this because now we can read this is out of our
javascript are really easily. So I'll say Dasan that Ajax. Method the post. You
are all set to the link. That 80 tr h ref. And that's it. Then Dighton done. So
we get have a callback function done this will pass as our JS Jaison data. And
I'll move our article count into there. And we can set the each team L2 data
daat hearts. And yeah. That should be set. Go back. Refresh. Now. Try it.
Buman. It works perfectly but there's a bonus. See this little icon down here.
This showed up as soon as we made the first ajax request on the page. Every
time we make an ajax request. The AJAX request. Is added to this list on top.
That's awesome because remember the profiler. If you open this in and you can
click this link in the new tab. It will actually show the profiler for that
ajax request which means you can see what the performance is for that ajax
request.

If there's an error you can see that here you see all the logs all the twig
everything for that ajax request. That's kind of a hidden easter egg that not
everyone knows about. So yeah there's your first.

Path into doing JavaScript and Symphonie API is.
