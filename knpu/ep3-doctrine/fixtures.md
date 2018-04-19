# Fixtures: Seeding Dummy Data!

Coming soon...

Yeah, 

right now we're creating dummy content in a really weird way. We have kind of a secret end point and it basically creates the same article every single time. So on the home page we just have a lot of articles that are almost identical. 

Our dummy data sucks in. We can do so much better. It was a great library and the doctrine world called the fixtures library. That's going to help us load much better dummy data. Actually we're going to use this library, but put our own spin on it to make fixtures really fun. So first let's get this library installed. Find your bundle hundred turn on run composer require and use the alias o r, n Dash fixtures, Dash Dash death. We're using dash dash death because this is a tool we only need one. We're developing because it's going to help us load fake data. Perfect. When it finishes, we can generate a migration class. Learning bin Console, make colon fixtures. Let's, let's name ours article fixtures. It's fairly common to have one fixtures, class per entity or sometimes per group of entities, and that's it. 

OK, 

so here's the idea. This created a new article fixtures class thanks to the bundle we just installed. We now have a new bin Console command that will call the load method on all of our fixture classes automatically. The idea's really simple. We just manually create and save objects right inside this load method. So let's go into our article controller and copied all of our dummy code. 

OK. 

Going to article fixtures and pastes and the only thing is make sure you read the article and hit tab to get the use statement for that. And then also down at the bottom, it's not called Elm, it's actually passed down as an argument called manager for entity manager. Then we'll go into our article admin controller and I'll just say Di to do eventually we probably will make this endpoint a form that creates articles. 

OK, 

and that's it. It's pretty boring. It only creates one article, but now we can move over and run bin Console Doctrine, Colon fixtures, Colin, that load, it'll ask you if you want to continue because this actually deletes the database first and then as fresh data, because this is a development tool, so it finishes. We refreshed and we have exactly one article, so not much better than what we have now. So the first thing is we probably want to have multiple articles. So how do we do that? Well, first we're going to do at a really boring way, which is a for loop. So we'll say for I equals zero, I less than 10 I plus, plus we'll do that and we'll go all the way to the bottom and we will do our end curly brace. So we need to call persist in the loop, but we only need to call flush once at the end of the loop. OK, so let's try again. That'll clear out our database loads. It will go over refresh and we now have 10 articles. Awesome. They're not very interesting yet, but we'll get there. 

OK. 

Now, as easy as adding a for loop is I want to make this a little bit cooler. I'm going to create a new class inside of my fixtures directory called base fixture, make it abstract and then make it extend the normal fixture class that are fixtures extent, so extends fixture. The idea is that this is not going to be a fixture class itself, it's just going to contain some helper methods that all of our fixture classes can use. Specifically. I'm going to copy the load method 

and implement that myself, retyping the object manager to get that use statement next, and this won't make sense yet, just followed me through this. I'm going to create a private manager property and then I'm going to set this inside the load method. Some of the fixtures call my load method. It's the first thing I'm want to do is set this on a property, then create an abstract protected function called load data. With that same object manager arguments. By the way, object manager is just a more generic interface. The entity manager interface, so effectively object manager in entity manager interface are the same thing. Now I've done a load, we'll call this arrow load data and we'll pass it the manager. So far this doesn't do anything special. 

Yeah, 

but now in our article class article fixtures class, I'm going to extend my base fixture instead. Then delete that unnecessary you statement and now because I'm extending this, I need to actually implement that low data method. So instead of implementing load, I'm going to intimate implement loaded data and we can make this protected. 

Yeah, 

so again, this does not do anything different than now than what we have now. When we let our fixtures, the load method on a base fixture will be called, then it causes low data on the child fixture and everything is totally normal. So why did we just do this? Go back to the base fixture class and at the bottom I'm going to paste in a little method that I created and actually before we look at that, I'm going to add a little inline documentation above my property so that php storm knows. This is an object manager instance. So the idea is instead of a four loop, I can call create many pass the class I want to create, how many I want to create and then pass it a callback function that will, it will call for each one or we can populate the data internally. It does the for loop and instantiates our object that calls our call back, which allows us to populate data. Then it calls persist 

for us. 

And finally this last line is not going to be important yet, but it's going to be very important in the future tutorial when you have multiple fixture classes. Eventually you need, eventually you're going to need a way for them to work together so that one fixture class can reference objects from other fixture classes. We're going to talk about this in our relationship tutorial by calling references stores are object in memory so that other fixture classes can fetch it out. And what it's doing is it's just doing it by class name and then the, um, number of that. So don't worry about this add reference yet, but it's going to become important in the future. So to use the scrape money, we're going to say this Arrow create many pass it article colon, colon class, you want to create 10 and then we'll pass it that function, it will pass us the article object so we can say article and it will also pass us the count which, which is which the number of the one that's being created right now. So internally we can get rid of article equals new article and all we need to do inside of here is just set on that article and now we can actually use that count here instead of the random number. So we can always be that same slug dash that account. And then at the bottom we actually don't need to call persist anymore. 

That's being called for us. And at the bottom we need to add a little. Does that mean going thing? So it's just a little bit fancier. And like I said, the really important thing is that it's going to save this reference in memory for us, which is going to be really helpful. All right, so let's go back and try it one more time. Hit yes, no errors and one very refresh. Yes, it's still works. So the last problem, and it's pretty obvious, is that we have 10 identical articles still. So in the next chapter, and let's use an awesome library called faker to make this really rich, more realistic data.