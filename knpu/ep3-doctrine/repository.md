# Repository: Custom Query HQ

Coming soon...

Ok, 

our articles show page loading dynamically, but this homepage, these are still hard-coded articles. Time to change that, open up article controller and find the home page action. Perfect. So just like before we need to query for the articles, this means that we need an entity manager interface arguments. Then 

OK, 

then we always get the repository for the class. We want sort of postural equals em Arrow get repository article, colon, colon class, and then we can say articles equals repository and making use that handy. Find all method. 

Yeah. 

Now that we have an array of article objects, we can pass those into the template as a new articles variable that's opened up the homepage. And if you scroll down just a little bit, you'll see the article list here. There's a main article, but I'm going to ignore that for now. Down here, let's do four article in articles that I'll find the end of my. 

Yeah, 

article right here and say [inaudible]. 

OK, 

we'll just start making things dynamics. So these slug is not going to be article that slug 

you. Hard-Coded title is going to be article title and then for the three hours ago we'll say curly curly article that published at and if that exists we'll do our article published that and we'll pipe that to the ago filter and if it's not published we'll do nothing. Awesome. So thanks to this, we can delete our other two big articles and with any luck when we go and refresh. Nice. It works. You can see we have a mixture of published articles and unpublished articles and you can also notice that everything is just printed out in whatever order it was created. So the first obvious change is that I'd like to show the newest articles first. So let's go back to our article controller and this is interesting because the final method just gives us everything but it's pretty limited. In fact, you can see it takes no arguments. So the find the methods, the built and find methods are limited, but you can do a couple of things with them. For example, if you want to find everything but in a certain order you can instead use the find to buy method. Normally you would pass us an array of where were criteria, but we can pass it an empty array, defined everything, and then we can pass it published at assigned to descending. 

This lets us create a slightly more custom queries refreshed. This time mess that looks much better. 

OK, 

except now it probably doesn't make sense to show the published articles on the homepage yet, and this is where things get more complex than they can handle. Sure. We can pass a simple where clauses and the find by, but in this case we want to exclude articles. Where they published at is not something we can do inside this little array, so for the first time we're going to need a custom queries. Now, here's the really cool thing. When we originally generated our entity, a created the article entity, but it also created an article repository in the repository directory. Now here's the real kicker. If you dump the repository object and refresh, guess what? That is an article repository instance. 

OK? 

There's a connection between the article and article repositor classes. In fact, that connection is spelled out right at the top of our article class. This says, when we asked for the articles repository, it should actually give us this article, repository method class, be fine to bond the builds and find methods are actually coming from one parent classes on our article repository. So why am I telling you this? I'm telling you this because if you want to create custom queries, you can do that by building custom methods inside this class and you can actually see a couple of examples. I'm going to comment out the first example and then rename it to find all published ordered by newest. Nice descriptive name. 

All right, so we need to talk a little bit about how you do custom queries and doctrine. Everybody's familiar with sql and in theory it is possible to write raw sql queries with doctrine, but you'll almost never do this. Instead, doctrine has been, has its own a query language called doctrine query language or de que. It looks almost exactly like sql stop. Instead of tables and columns in your queries, you use the class name and the property names on your entity. Again, you need to pretend like there is no database and there are no tables and what you're actually queering for here. Our classes. 

OK, 

so when it goes to you can either write custom, Dq, l or you can do what I do in use the query builder, which is just an object oriented way to build that Dq l strings and you can see a pretty good example right here. You can add where statements order by limits and pretty much anything else. One quick thing I mention is you'll always see me use and where instead of just where you can use, where better recommend always using and where the thing is. You can see why because and where it is legal to say first doctrine is smart enough to not include the word and the queries if it's not necessary. The second thing is if you use where it actually replaces any previous where clauses you have, so you might accidentally remove part of your query unnecessarily. So I always use an where second thing to notice is it uses prepared statements and placeholders. So whenever you have a dynamic value being passed in, instead of hacking it into the string, you should say colon, some placeholder, and then the next line say set parameter that placeholder in that value. 

OK, 

OK. So for us, we actually don't mean to pass any arguments and I'm going to simplify things a little bit here and say and where then say a.is published. 

Yeah, 

published at is not known. 

OK, 

so you can see how this looks like normal sql Wilco. One important thing is here is this. Hey, what the heck does that mean? Well this is just. This is basically the table alias that's using the square. So it's similar to saying select a select star from article a. It could be anything here. If you said article for example, then you would just change everywhere else to refer to that alias. Let's also add our order by order, by and here we'll say again, a dot published that 

and then descending 

and published. That is also a good example of how we're referencing the property name on the entity, not the column name of the database and the database. It's actually published underscore at, but since the property is written in camel case, that's how we refer to it. Then I'll remove these set max results. 

Yeah. 

After the querybuilder, you always called get queries and then getting results and returns the array of results, so the array of article objects that were returned below this method. There's another example of finding just one object, so if you need to find just one article object or no, you write the query builder the same way you call get and then you call get one or no results. You always build a query. You always call get querie and then you call get results. If you want many rows and you've caught get one or no result. If you just want one article, object back. Got It. All right. Now that we have this nice new find all published ordered by newest, we can go into our controller and we can just use that repositor Arrow find all published ordered by newest. 

Yeah. 

Now, let me flip over and refresh. Yes. The orders there in the unpublished articles are gone. 

OK. 

To make this even cooler, let me show you a trick. Instead of getting the entity manager and then call and get repository to get the article or pository, you can shortcut it and just type article repository, repository. 

Yeah. 

That goes to the entity manager for you and returns that. This is how I actually set up my controller is there's no reason to do that extra step and when you refresh, no surprise, it works exactly the same as before. All right, next I'm going to show you one more trick in some degree, repository class and in extra shortcut for querying.