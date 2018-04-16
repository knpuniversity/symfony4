# Basic Queries

Coming soon...

Now that we have some articles inserted into the database, we should be able to actually update our show page to not show a hard-coded article, but actually load queries the database, load this article in print real dynamic data. To do that, let's go into our article controller and find show which is responsible for rendering of this page. All right, so as I mentioned earlier, the doctor and bundled brings in one object that does everything save and mcquery. It's the entity manager, so we needed it. Again, here was add another argument called entity manager interface. Let the auto complete to get the use statement dollar sign. Now we saw a second ago that to save something, a database, you just call a couple of methods, he called em, persistent em flush to queries from the database. The first step is you always need to get a repository object, repository equals e em, arrow get repository, and then the class name, so article colon, colon class. This repository object knows everything about how to query from the article table so we can say article equals repository Arrow and it already has a couple of really cool methods and like find what you can you can where you can pass it, the ID, find all to find everything, find a buy where you can pass it in array of things defined by or find one by which is the same, but just finds one item. 

So let's say find one by I will say we want to find by the slug, calm what matches the slug. Now, of course we're going to need to write more complex queries in the future and we'll talk about how to do that, but out of the box you can do a bunch of basic queries without, without doing anything above this. Just to help my editor, I'm going to tell piece storm. This is an article object, 

OK, 

that this is an article object and that's a really important thing when you queried from the database doctrine gives you back in object, not an array with the data. It's a full blown object. Of course, if the. If there is no matching slug on the database, this isn't gonna Return. Nope. So we need to account for that. So say if not article, 

throw this Arrow. Create not found exception. And I'm going to pass a message here like no article for slug percent s and pass in the slug. OK, here's the deal. This will result in a four or four. I'm actually gonna hold command and click into this method. And you see this comes from um, a trait used by our base controller. This just throws an exception. So it turns out the way to cause a, for foreign happen in symphony is to throw this very special exception class, which is why we throw this great, not found exception. This message here, you can be as descriptive as possible because this is only going to be shown to the developer, right? And at the bottom, for now, let's just dump the article and die. 

Yeah. 

OK, cool. So switch back and first refresh. OK, here is the four four page. There's nothing in the database that matches this slug because ours have a number on the end of them. You can see here it says [inaudible] and it gives us the big description message. This is what the developers will see, and of course when you're in production, you'll see a four, four pages that you can customize. We're not going to talk about how to customize error pages, just google for a symphony customize air page. It's super simple, but you can make your four four pages look exactly how you want. 

Yeah, 

to get this, to go to a real page. Let's go back to slash admin article new. Create a new article. Then we'll go back, paste that slug up there and yes it works. You can see the article controller is actually, it's returning to us an article object, so with this now we're dangerous. Working with objects is easy, so I'm gonna. Get rid of that dump there will keep the comments hardcoded for now, but we don't need the article content anymore. We're also going to delete them. Markdown parsing. We'll do that in a template. You'll see in a second. So up here I can get rid of the markdown helpers since we're not using it. And then down here, instead of passing title, article content and slug, let's just pass the article object. 

Yeah. 

All right. Now in the template you can hold command and click this to open it, but it's also just in tests. Article showed a aged and lots wig, a perfect. So instead of title now we'll say article that title and you'll actually see auto-completion on that notice as auto completing get title sees that when I hit tab, it just says article. That title that is thanks to some twig magic when we say article title tweak actually looks in the article class notices that the title property is private and so then it looks for a good title method and calls that. This is really cool because in your templates you can just code really simply article that title and twig figures out what to do. This also works with a race, so if article we're an associative array with entitled key. This would totally work aright. Let's go down and change a couple other places. Article title down here, article that slug and finally down for the content will say article thought content, and then we're going to pipe this through the markdown filter. The campy markdown bundle actually comes to the markdown filters so you can just pass the content through there and it should work. 

Alright, ready? Let's move over and refresh. It works. This is now dynamic content and I dynamic title. 

Yeah, 

and check this out down on the bottom of the web. Debug toolbar and now has a new database icon which tells us that we had one database queries and how long the database query took and if you click into this, it will show you all of the database queries that were made on that page. You can run. Explain on them. You can also view a runnable Querie, which I use a lot of times to copy in and debug and more complex query. So this is an awesome feature. All right, next, let's do something.