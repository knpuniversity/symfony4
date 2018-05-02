# Awesome Random Fixtures

Coming soon...

Ok, 

having just one fixture class and putting articles in there and comments in there and eventually more entities is not a bad way to do it, but it's going to get a big a bit disorganized. So I'm going to create one fixture class that's responsible for each individual entity. So let's delete the comment code from our article fixture. Then we'll run bin console. Make con fixture. Must create a new class called comment the fixture. 

OK, 

then I'll flip back to my code and we'll open that up. It's very simple except in the last tutorial we made a base fixture class of our own, so let's extend that instead and when you do, instead of having a load method will have a loaded data method, it should be protected and then we don't need to have the use statement for the fixture class anymore. Now the reason we extend this base fixture classes, they gave us a really cool method called create many so we can say this Arrow create many past that comment. Colon, colon class. Let's create 100 comments across the system and then we pass that a callback that will be called for each of those 100 comments in here. We're going to rely on faker, which we set up last time, big time to give us really cool data. So let's start by saying comment error set content. I use multiple lines here in. What we can do is let's say this Arrow Arrow, boolean, that'll give us a random true or false so that we can either randomly generate a random paragraph, this Arrow, faker, arrow paragraph, or we can randomly generate 

a couple of sentences, will say a two sentences and we'll say true because we actually want that as text as an array of sentences, so that'll give us some really nice fake content. Let's say comment Arrow set author, so the author name, this Arrow faker, arrow name. It gives you a random name by the way. You can look up all of these fake or things on the faker documentation and then comment Arrow set created that. We created that because it's generated for us, but to get more interesting data. Let's say this Arrow faker Arrow date time between we'll say minus one and minus one 

second. Perfect. 

At this point, this is a valid comment object. We're just missing that link from the comment of the article and now we know that it's just as simple as comment aerostat article. The problem is that all of the articles are created in a totally different fixtures class, so how do we get access to them? Well, one thing you could do is we're past the entity manager so you could just use that to get the article repository and run some queries to fetch out the articles, but there's an easier way. If you look at our base fixture class, when you call that create many. It's a fairly simple function, but one of the things that it does is it causes this Arrow at a reference. It creates a little key here, which is the class name underscore, and then an integer that starts at zero and count up each time and it stores the entity object there. 

This reference systems, they little system built in that into doctrine fixtures where it keeps a dictionary of all the objects is it's loaded and then you can fetch things out from that dictionary later. So for example, inside the comment Arrow set article, we can say this Arrow get reference and we'll pass it the article class because that's the first part of the key and then we'll say underscore zero, we'll get the first article that was added. It's not going to be happy because it only knows this returns an object and we're expecting an article object, but this will return in article object. 

Yeah. 

All right, so let's try to move over and run bin Console doctrine fixtures, load 

OK no errors. That's a good sign. And we'll run bin Console doctrine, Colin queries on sql, select star from comments and yes, 100 comments and you can sit there all related to the exact same article. So successful. Just not very interesting yet. So how can we make it more interesting? Well, if you look at the article fixtures, we created 10 articles, which means that there are references in the system starting at zero all the way up to nine, so a common fixture. We can make this a little bit more interesting. I take the zero and instead saying this Arrow faker Arrow number between zero and nine with any luck probably fixtures. Again, no airs, run the query again and so 100 comments and now they are related to all kinds of different articles in the database. I really liked this type of a system where I can randomly fetch out things to relate things to. I like it so much that I want to formalize it and make it easier. So go to your base fixture class and on top I'm going to add a new private property called references index. Set that to an empty array. 

I'm adding that because at the bottom of this function, I'm just going to paste in a method that I prepared before. It's a little bit ugly, but it's a protective function called get random reference. What you can do is pass that a class, like the article class in it. We'll figure out all of the references that have been stored for that and give you a random one, so when common fixture we can now just say common error set article and then this Arrow get random reference in past that article colon, colon class, and that should do the same thing that we had before. So let's reload the fixtures. No Airs, run the query and looks awesome. So this is going to be a really nice tool going forward to give us fake data. Now there is one problem with our fixtures that's hiding right now. To see the problem. I'm going to right click on the class name, comment fixture. I'm then going to go to re factor rename and call this a zero comment fixture will ask me if I want to rename the file, which I do. Some of you might already know what I'm after here. Now go back, reload the fixtures. 

An explosion cannot find any references for class [inaudible] entity slash article. 

So this is the air that comes from a base fixture class where he basically says, look, I don't see any articles that have been loaded the data to the database yet, and the problem of course is that, and you can see it over here in my tree, I've now made this fixed your class alphabetically before article fixtures. We haven't been thinking at all yet about which order fixtures are loaded in and by default it's just alphabetical. But now this is a problem because our common fixture is being loaded before our article fixture and we need the them to happen in the other direction. In fact, you can see this in our terminal, you can see that the first class at loaded was the a zero comment fixture class. So to solve this problem is a really cool solution. As soon as you have a fixture class that is dependent on one or more other fixture classes, implement a new interface called dependent fixture interface. This will require you to have one method. So I'll go to the bottom, I'll go to the code, generate menu or command n on a Mac, go to implement methods and implement, get dependencies their way, public function on there, and here you're just going to return an array of the fixture classes that this class is dependent on sonar case, we're dependent on article, article fixtures, colon, colon class, and that's it. 

Now going back and reloading your fixtures again. 

OK, 

and it works. So you can see the article. Your class is loaded first, and then our common fixture below doc, the fixtures library. It looks at all of these get dependency methods across all of your fixture classes and it figures out the proper way to order them so that everything is done correctly. Have this working. Let's re. Let's go back and right click on the class name, refectory rename and take out the zero because that's a terrible name for that class. 

Perfect 

to celebrate. Let's move over, refresh and awesome. There's 10 comments, random amount of comments, [inaudible] comments.