# Sluggable & other Wonderful Behaviors

Coming soon...

Ok, 

we're using faker to set some fake data, including the slug. Thanks to this. 

Yeah, 

go to the homepage and look at the UW. Where else? They're just really random slugs, but they're not actually anything related to the title. Really. The slug should be automatically generated from the title, but I mean is if I set the articles title something else, it should automatically convert that to a slug and make sure that slug is unique in the database. 

Yeah. 

Fortunately there was a really cool library that can do this and a lot of other magic google for it stuffed doctrine extensions bundle then click into its documentation, so here's the deal. There's a library called doctrine extensions, which adds lots of behaviors to your entities like slugable where you can automatically have a field set from another field. 

Yeah, 

or other things like log bold for tracking changes or blame. Able to figure out which user created or updated insti really cool stuff. These stuff, doctrine extensions bundle helps you integrate that into a symphony project, so let's copy the composer require line, move over and paste. While that's working, I want to go look at the documentation. This is a wonderful library, but it's documentation is very confusing, so I'm going to walk you through it a little bit. I'll scroll down here finally to a section called activate the extensions you want, so you saw the one that gives you a lot. There's lots of different possible behaviors, but for performance reasons, when you install this bundle, you need to explicitly say which behaviors you want, like timestamp a bull set to true. 

Let's move over and Oh, interesting. You'll notice that the install stopped and it's asking us if we want to install a recipe from stuff. Doctrine extensions bundle. That's a little weird because we've been installing recipes. We've been assigned tons of recipes without this message, but it says the recipe for this package comes from the contrib repository, which is open to come. Community contributions. Symphony has to recipe repositories for recipes. A main one that's watched very closely in all of the recipes so far have been installed from that. There's also a contributor repository where the community can contribute recipes, but when you download a package that installs a recipe from that repository, it asks you to make sure that you want it and even asks, giving gives you a link so you can review the recipe. I'm going to say that we can. Yes. Permanently. And then that installs the recipe. 

Yeah. 

One of the things the recipe did is add a new config packages stuff, doctrine extensions, dot yammel file. This is where we need to enable the extensions we want. So we want slugable. So if you look at the example here we're going to do is say the faults slugable true. The configuration is a little ugly. Default is replying is referring to the default entity manager. Some projects can have multiple ads, multiple entity managers, but mostly just have the default one. 

Yeah. 

So as soon as we do this, nothing happens yet, but now the library is looking in our project for entities that want to have slumps on them to actually activate this. Go into your article entity. 

Yeah. 

And find your slug field. 

Great. 

And actually I'm going to go show you the documentation for this. So another confusing thing about this library is the documentation for this bundle shows you just the configuration, but if you want to actually know how to more information about the individual features, you need to open another link to the actual doctrine extensions library. So this is the library that gives you those features and it has more documentation about all the features. So let's click slugable. It's tells us down here how to actually use the feature by adding this slug annotation above our slug field aren't slug field, I'll say at slug it tapped out of complaints that and we'll say fields equals curly brace, title, title, and that's it. Back in article fixtures. We no longer need to set the slug right now. Let's go over and let's reload our fixtures. Bin Console doctrine, fixtures, load. 

OK, 

go back to our homepage, refreshed and perfect. Look, now that you were a really, really clean based off of the title, that as an added benefit, you'll notice that sometimes as a number on the end, and that's that comes from, that's the slugable behavior. Guaranteeing that there's a unique slug, so in this case there's already that same slug, dash one dash two deaths, three desks for dash five in other places. By the way, the way this isn't magic works behind the scenes is from doctors, event system for Google, for symphony doctrine, event subscriber. You'll find a page on the symphony documentation that talks about this. We're not going to create our own event subscriber, but it's a really, really powerful idea. So in this example, they talk about maybe you have a search system and every time you save an entity you need to update these search index. 

Yeah. 

To do that. And you create something called a subscriber and then you can actually listen on different events like post persistent post update. So in this case, this would allow you to execute code after he entity in the system is either created or updated behind the scenes. This is how slugable works to do its magic. Yeah.