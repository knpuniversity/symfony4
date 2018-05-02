# Many To One Relation

Coming soon...

Let's think about how we want this database to look, this relation to look in the database first. Every comment is going to belong to exactly one article in every article is going to have many comments to set up this. To set up this type of relationship. The comment table will need a new article ID column that will hold the ID of the related article. It's actually a very traditional database relationship. Well, so far in doctrine. Whenever we wanted a column in the database, we've added a property in our entity and database relationships are no different. If we want that article ID column in the database, then we're going to need a new property inside of our entity. 

Yeah, 

to add that new property. We're once again going to use the generator, so let's go to bin Console, make client entity, and we of course want to edit the comment entity. Here is a very important moment. It's asking us for the new property name. You might think that you want to do something like that article Id, but here is where doctrine is different. It wants you to pretend like ideas don't even exist. What I mean is instead of calling this property article Id, we're going to call it article. You refer to the class 

that you want this to relate to what I asked for the field type. You can actually tie up a fake field type here called relation that's going to bring up a wizard that's gonna, guide you through the exact relation that you need. So first asked, what class should this entity be related to? So obviously we want to relate it to the article entity, right, and now it takes us through the four different types of relationships that exists in doctrine, many to one, one to many, many to many and one to one. And if you're not sure the relationship your need, you can actually read through this description to find which one matches best with you. If you look at many to one says that each comment relates to or has one article that sounds perfect and then it says each article can relate to or have many comment objects. 

Yeah. 

In that is also true. The relationship that we need in this case is many to one and it's actually the king of the relationships. Vast majority of the relationship you'll set up will be many to one soil type that at the bottom. Many to one. 

Yeah, 

but don't worry. We're going to talk about most of the other relationship types as well. Now asks us if the comment, the new article property on comet is allowed to be and it's basically asking us if let's say know what this means is that we don't want it to be legal. We want it to be illegal to store a comment in the database that has no article related to it. It just doesn't make sense. So let's make it no 

next, and this is really important, is that asking us if we want to add a new property to article so that we can access or update common objects from it. In other words, it's asking us if we want to be able to say something like article Arrow, get comments. So of course when we set up this relationship, if we have a comment object, we're going to be going to be able to get or set the article on that, but optional, you can have doctrine set things up so you can go in the other direction, you don't have to do this and we'll talk more about it later, but if it's convenient for you and say yes, and in fact being able to say article Arrow, get comments, all the comments for a single article is probably going to be useful. We're probably going to use it to render the comments for a specific article at the bottom of the page. So let's use the default yes value there, and then it asks us what to give. That property and name will use the default comments. Finally, it asks a bit of a complex question about something called orphan removal. I'm actually going to say no for this. This is a little bit more advanced 

and you probably don't need it unless you're doing more advanced form collection stuff in symphony and perfect it finishes. Let's enter one more time to finish things. Now, before I hit record, I actually committed everything to get, so if I run a good status, you can see it actually updated both article and comments. Let's look at comment first. OK, awesome. So it added a new property called article and you'll immediately notice that with relationships instead of the normal at or I'm column it's at or I'm slash many to one and then there's just some metadata that says this, this, that this points to the article entity, and of course at the bottom we have the normal get article and set. So nothing too fancy here. 

Yes. 

If you look in the article entity, you'll see that it has a new comments property and then near the bottom it has a get comments method. Instead of a set comments for convenience, it generates a add comment and a remove comment. 

And also one other kind of interesting thing is that when you have a relationship that holds a collection of other objects, like an article holds a collection of comments, you actually need to generate a constructor and initialize the comments to a new array collection. That looks scarier than it is. Comments is really an array, and even though it's technically this array collection object, you can loop over it, you can count it pretty much anything you can do with an array you can do with an array collection, so this is a necessary evil, the generator ads for you, and you'll probably never even know it exists. I want you to think of this comments property as an array, so if you look at these two relationships, remember we generated a many to one relationship, so on inside comments to an article property and give it a many to one, but if you're looking at article, it's actually a one to many. That's the one of the first important lessons is back when we generated and asked us which of these four and relation types we wanted. Well actually many to one and one to many are these same relationship. It's just viewed from different sides, 

so they're not really different at all. 

Yeah, yeah. 

Not to complete the picture of what this actually does. I want you to go over and generate the migration with Bin Console, make Colin Migration, then move over and opened that new migration file. This is really important by creating these. 

Yeah, 

two relationships by creating this one relationship and and we're really seeing the two sides of it. The end result is simply that the comment has an new article underscore ID column, and of course then adds a foreign key constraint that references the articles id column, so even though in the comment entity we called this property article behind the scenes, it actually creates an article ID column, so the way that things are stored in the database is exactly how you would expect it to be. But in PHP, instead of thinking about Article Id, you call your property article and you relate it to an object. We're gonna. Talk more about this as we go forward, so if it doesn't totally make sense yet, don't worry. 

OK, 

moved back and what thrown. Let's execute our migration with bin Console doctrine migrations migrate, and now in the database we have an article table when we have a common table, and that table now has an article ID foreign key constraint. So next. 

OK, 

let's learn how you actually set that relationship. Yeah.