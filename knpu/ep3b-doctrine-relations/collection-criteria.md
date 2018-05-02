# Collection Criteria

Coming soon...

Ok, 

we're gonna. Look at a feature, a very powerful feature in doctrines collection system that is not well known about. Here's the setup, go to your terminal and run make entity. We're going to add a new field of comment entity called is deleted. This is going to be a boolean field type and we'll, I'll make it not know and the database. Once that finishes run, make migration. 

Yeah. 

As always, check that migration. By the way, you can see that even though you have a boolean type and doctrines, so it'll be true. False is actually stored as a tiny into my sql. A zero or a one. 

Yeah, 

and then we'll migrate with bin Console Doctrine. Migrations migrate. 

Yeah. 

Perfect. Alright, so we're not going to create it, the admin interface yet to delete articles to, to delete comments, but we are going to set up some comments to be already deleted in the fixtures. First in the content class fund, the new is the leader in default this to false, so any new comments, if we don't set this field, they will not be deleted. The next in comment fixtures, let's say comment Arrow set is deleted and we'll say this Arrow faker or a boolean and we'll say 20, so all the 100 articles, approximately 20 percent of them will be marked as deleted. Then to make this a little bit obvious on the front end, just for now, go to the show template and right after the date let's add an if statement that says if comment that is deleted and death, let's just add a little icon here and we'll say deleted. All right, let's find a terminal and run bin Console doctrine. Colin fixtures, colon load. That load of the fixture is just fine. If we go back and refresh. 

Yeah, 

scroll down. 

Yeah. 

Well, let's see here. Here's one. This article has one deleted comment on it, so we printed off this deleted mostly just for our own benefit while we're developing this, because what we really want to do is we do not want to show deleted comments at all. Let me print this, but of course the problem as you already know is that when we loop over, the comments were just calling article dot comments were calling article, get comments which just returns us all of the comments related to this article, but now we don't want it to return all of the comments. We only want it to return the non deleted comments, so how can we do that? Well, one option is always remember we could, instead of using article about comments, we could go into article controller, find show action. In here, we could create a custom query for the common objects that we want to pass those comments into the template and then use that comments variable in the template instead. In other words, these shortcuts are nice, but if they don't work for you, then don't use them. No big deal. The second way we can do this is that we can be a little fancy, go into article copy, get comments, paste it, and rename it to get non deleted comments. But for now just return all of the comments. 

Then in the show template, let's start using this new field. So when we loop, say article, got that non deleted comments and then further up when we count them, we also want to make sure we count, be non deleting comments there. 

All right, 

we go and refresh out. Of course we don't know any difference because we're still using that field. So back in article, how can we make this method return? Only the non deleted comments? Well the most obvious way is to, for example, a loop over this Arrow comments and then create maybe a new array 

where 

or clarity I'll use get comments were not deleted. We added to the comments array and then return at the bottom. And actually that would totally work fact. I'll go over and refresh and. Oh it exploded. 

Yeah. 

Yeah. And that would totally work. 

OK. 

But it would also mean that we're still querying for all of the comments and then just removing a few. And this is going to be a problem because what if we have five comments in 200 of them are deleted. Then we're doing all this extra work just to get those comments and then not use them because of this reason. There's a really cool but strange system called the criteria system, so instead of looping over all the data, say this criteria equals criteria one from doctrine, colon, colon, create. Then you can chain off of this. That criteria is somewhat like the querybuilder we're used to, but it has a different syntax, actually a more confusing syntax in my opinion, but somewhat similar serena use and where, but here instead of passing the nice simple string, we're going to need to say criteria. 

Yeah, 

Colin, Colin expression, and then off of this there's a bunch of different methods you can use for equal, greater than, greater than or equal is no less than. It's basically a little object oriented, built or for an expression. So in this case we want the equal cause we want to say equal is deleted, equal to false and then we'll also make sure that we maintain our order by saying created that equal Arrow descending. Put aside the colon that create criteria object on its own doesn't do anything, but now we can say return this Arrow comments, arrow matching and pass that the criteria. Now we think of comments as an array, but you'll remember it's not actually an array, it's actually in, it's actually a collections collection, has an interface that comes from doctrine and in addition to having looking and feeling like an array, it actually has a couple of extra methods on it and one of them is called matching where you can pass it that criteria. So now move over and refresh and if you look down eight comments, went to seven comments and are deleted. Comment is gone. But the really cool part of this is when you look inside of the profiler for the queries, if you look at the last querie, check this out. You can actually see it does an efficient querie. It is not great for all of the comments and queries for all the comments that match this article and where is deleted is equal to false or zero. It even did it with a count query here 

I did a count query for all of the articles that were not deleted. So by using the criteria, we can get super, uh, efficient queries by filtering our. It's not always necessary, but especially if you have a big collection, it's going to save, you know, one thing I don't like about that criteria system is I don't like having any of my query logic in my entity. We already have a place for our query logic and that is our repository class article repository, so for that reason I choose to put my criteria logic into my article repository to do that. Create a public static function called create non deleted criteria and this will return a criteria object article, copy the criteria, paste it here, and we'll return to. These are the only methods that you should have in your repository class that are static. They need to be static because that's the only way that we can call them from our article article class, so now their criteria is simple, simplifies to criteria equals article repository. Colin Colin create non deleted criteria. If you go back close, the profiler refresh, it still works just fine as an added bonus in your article repository. If you were ever working with a querybuilder and you wanted to reuse this logic, this non deleted logic, you actually can do that after you've created a cre querybuilder, there's a method called add criteria and you can use that to actually merge into your existing querybuilder, so the criteria logic can still be reused in some of your queries while they're all right, but now that we have this working, 

yeah, 

if you go back to your homepage, these comment numbers here are still including deleted comments. So in our homepage, that html twig where we have article that comments change that as well to article that non deleted comments. Let's see here. We have [inaudible] those change to five nine. Perfect.