# Comment Entity

Coming soon...

Hey guys, 

welcome to part two of our doctrines tutorial because even though we learned a lot of really fun stuff in the first tutorial, we sorta avoided the elephant in the room. We said nothing about database relationships and it turns out database relationships are pretty darn important if you want to create a real application, so we're back to not only learn, but absolutely dominate doctrine relations, database relations in doctrine. The trickiest thing, as you'll see, will be that you'll need to start thinking about things differently. Instead of thinking about tables and foreign key columns, you're instead going to think about objects relating to objects. You'll see what I mean. 

Yeah. 

As always, 

yeah, 

to become my best friend and therefore have. 

Yeah, 

the best possible relationship. You should totally code along with me. Download the code from this page. After you will unzip the file, you'll find a start directory which will have these same stuff, same project that you see here opened up the read me.md file. For all the details you need on how to get set up. The last step will be to open a terminal, move into the project and run bin Console server, run to start the built in web server. That'll let let us go to local host Colon, 8,000 to open up the space bar are inter galactic news site that now actually loads real articles from the database, but the one thing that it's not loading from the database yet are these comments. These are three coded comments at the bottom so you can already see the relationship. Every article is able to have many comments below it. Bright right now, if you look at me, source entity directory, the only entity we have his article. So the first thing we need to do is create a separate entity, a comment entity. We could just create this by hand, but you guys know that I like to use the maker Bundle, so open up a new terminal 

and run bin Console, make colon entity. Let's generate a new entity called comment. Then for the fields, you see that basically right now, to keep things simple, we have an author and we have the actual comment, so let's say author name and we'll have that be a string field. 

Yeah, 

and we'll make it available in the database. In the future, if we had users in our system, this might actually be a relation to the. To a specific user. We don't have users yet, and database relationships are actually what we're about to talk about anyways, so for now, keep that as a simple stringfield. Let's also add the content field content of the comment, well, this Ba textiles, and also don't allow that to be known in the database. Then hit enter to finish it up. Now before you generate the migration. 

Yeah, 

open up that comment class and you'll see no surprises. We have the ID column off her name content, and then of course the getters and setters below that at the top of the class, use timestamp bubble entity a trait that comes from the doctrine extensions library that will automatically give us the creative at an updated that fields. Now that we're ready to go back and run bin Console, make colon migration, that generates the migration file, then I'll move back to my code and as always we just want to make sure that this doesn't contain any extra changes. Great table comment, and it looks just fine. At this point, we're going to have two tables in the database article in comments, but they're not actually related to each other in any way. We'll handle actually creating that database. Will go back and run bin Console doctrine migrations, migrate and perfect. OK, next let's actually talk about how you relate to tables together.