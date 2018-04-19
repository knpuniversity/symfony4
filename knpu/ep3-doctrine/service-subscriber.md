# Service Subscriber: Lazy Performance

Coming soon...

Ok, 

there's a subtle performance problem with our twig extension. Here's the deal. Normally if you have a service like markdown helper, symphony's container does not instantiate this service unless you actually use it during that request. So for example, if we try to use the markdown hopper and a controller, it will be instantiated, but if we don't try to use it, it won't be instantiated. Well, twig extension, or a little bit special. If you're a page renders a twig template, then the APP extension will always be instantiated even if we don't use any of the custom functions or filters made by that extension. And the problem is that if app extension, in order to create, in order to instantiate app extension, the container also needs to instantiate the markdown helper. So for example, the homepage does not render anything through markdown, but thanks to the twig extension, our app extension is instantiated and so is marked on hopper. That's sounds subtle, but it's wasteful because APP extension is instantiated on every single request. We need a way. This problem is specific to twig extensions. You don't have this problem in almost any other part of symphony. There are a couple of responses that have this event, subscribers and voters, but I'll point those out as we go along. So somehow in twig extensions to be most performance, we somehow want to have symphony lazy, pass us our services. 

OK, 

and there's a great way to do this. It's a little bit confusing at first until you see the whole thing put together. So follow closely. First, make your class implement a service subscriber interface. This is going to force us to have one method, one new method. I'll go to the bottom of the class, go to the code, generate menu or command and on a Mac and it's like get subscribed services. I'm going to return an array on here, but leave it blank for a moment. When you have a service that implements service subscriber interface, it means that simply is actually going to, 

yeah, 

when you have a service that implements service subscriber interface, symphony will now pass your service a container. So remove the first argument and replace it with container interface, one from PSR container container. I'm going to rename the property to contain it, and actually you can have multiple arguments to your constructor when you've met services for our service interface, simply looks for an argument type, hinted with container interface, and it passes the container there. Now, one important thing, this is not the symphony content and we talk about which holds hundreds of services at this point. This is an empty container to tell symphony to put things into that container. You go down and get subscribed services here. The one service we need is our markdown helper, so we'll say markdown helper, colon, colon class. When we do that symphony I this auto wiring to find these service in a container that's identified by this type, it then puts it into this container that's passed to our twig extension. So now we have a small container with just one service in it, but the key thing is that we do this because that container has the markdown helper. 

By doing this, when the APP extension is instantiated, the markdown help helper has not been instantiated yet. It won't be instantiated until we actually reference it inside this class. So for example, down in process markdown we can say this air container Arrow get marked down helper, colon, colon class, and then parse value. 

Yeah. 

The kind of confusing thing is in gets subscribed events, you can actually pass us a key value pair. If we said food equal arrows marked on harper collins law class without meaning is it would go and fetch the service from the main container that corresponds with the markdown helper. And then inside this class we would reference it as food. But if you just pass markdown helper class as the value, then it will go fetch that service from the container. And that's also the key that we would use inside of this class to reference it in our mini container. 

Yeah. 

So the end result is exactly the same as before, but now are marked on helper is lazy. And to prove it, let's put a dye statement at the top of the markdown helper construct function. So if we go back to our article page and refresh, not surprising, you can see it dies right in the middle of the page, but if you go back to the homepage, the whole thing prints the markdown helper is never instantiated. Go back and remove that die statements. So the takeaway here is use normal dependency injection everywhere, but then in just a couple of places that I'll point out like a twig extension 

you'll need, 

you'll want to use this service subscriber interface so that you can create a small container and Leslie Pass in your dependencies. If you don't do this, it's not the end of the world. But as this APP extension requires more and more services, it could slow down your application on unnecessarily. Yeah.