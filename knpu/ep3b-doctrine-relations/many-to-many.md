# Many To Many

Coming soon...

Ok, 

when we originally generated the relationship between comment in the article, we learned inside the generation command that there are four different types of relationship. There is a many to one, one to many, many to many and one to one, but many to one, it turns out that's actually referring to the same relationship as one to many. 

OK. 

This is just the same relationships scene from two different sides. So if you're trying to choose what type of relationship to cheat to use between two entities maybe to one in one to many are actually identical. So really there are only three types of relationships, many to many to many and one to one, actually many to one and one to one are also the same. 

Both of them 

use these standard foreign key set of relationship in the database. Like for example, article underscore ID on comments. The only difference was routine, a many to one and a one to one is that doctrine adds a with a one to one doctrine adds a little extra constraint and the database to make sure that your entity is only related to one other entity. So you can imagine if, 

OK, 

this way, one to one relationship with the article, then there would be a unique constraint violation in the common table to make sure that every comment it is only related to the only one comment can be related to the same article, one to one. Relationships are very common anyways. A good example would be if you wanted a user table and then you got a one to one with a profile table which added extra user information. I think they're kind of a pain in the butt honestly. So really when I want you to think about what types of relationships you have as options, I want you to think of is there. There are only two, many to one to many to many. A many to many relationship would be something more like articles and perhaps a new entity called tax where you can apply tags to an article. So every article has many tags, 

OK? 

But each tag 

OK 

is used on many articles and that's actually exactly the example that we're going to go through. So first, let's go to our terminal and run bin Console. Make Colin entity, it will create a new tag entity. 

OK, 

we'll create a property called string name. That's a stream. 

Yeah, 

called slug that so that in the future we could use that in the url for tags and then I'll hit enter and that's it. Before we generate the migration, go check out the new tag class, no surprises, name and slug. Let's use the timestamp bubble entity on top to get our time stamps and then just like an article, I'm going to make that slug set automatically, so I'm going to copy the ad, the get slash slug annotation, put that above our slug, but make sure you re type the g the you, but we need to use [inaudible]. It's an easy way to get that is just have at slug on the next line. Find the right one, hit tab, and then delete that. That was enough to make sure that this new and new and got put on top, and let's also make sure that the slug is unique. 

OK, 

now that the entity is ready, we'll go and run bin console. Make called migration actually exploded. You probably saw the problem. Need the map, our slug field to name that title. Let's go back and try that migration again. There it is, and as always we'll open the new migration to make sure it looks right. Yep. Create Table Tag. There's no extra stuff in here. So run that migration with bin Console doctrine. Migrations. Migrate. 

OK, 

perfect. OK, so we have a 

OK. 

Oh, and then to get some tasks and the database console make corn fixture. We'll create a new class called the tag fixture. Let's modify that. Have extended our base fixture class that we've been using, which means that we'll rename this to load data, make it protected, and then we don't need to ru statement anymore. And then we'll use our trusty create to create 10 tags. And for the name of each set name is Arrow, fager arrow. Real text. 

OK. 

And I'll say 20. You could use this Arrow, faker, arrow word, which would give you one random word, but that random word would be in Latin. Real texts will give you potentially a few words, but they'll actually sound kind of real or just sort of Nice. So you can use either here, 

OK, 

and that should be yet. So to make sure that works. Run over here and run bin Console doctrine, fixtures, load and no airs. We articles and article table and we have a tag table, but there is no relationship between the two. 

Yeah. 

Now inside tag, we don't want to do a many to one relationship to actually create the relationship. We're going to have a make st once again do this for us. If you want to see what changes. It may not say that that's sort of been canceled. Make Colin entity. Now you can either add the relationship to the article table class or you can add the relationship to the tag class. 

Why would you pick rag type article or tag here? Well, it's a subtle difference that I'll explain in a second, but let's say article and here we're going to say tax because if again don't think about the database, just think that in php I want my article object to be able to have in a collection of tag objects. So we use tags here. I'll use the fake type, so then a wizard will ask me more about my relationship. We're to relate. Relate this to tag in here. We go in and asks us are different types of relationships. So this time look at many to many [inaudible]. This fits our situation on each article to have many texts. We also want each tag to relate to many articles. So this is a classic many to many relationship. 

OK, 

so let's type many. 

OK. 

And then just like before it asks us if we also want to map the other side of the relationship. This is optional, but it says, Hey, do you want to be able to say a tag arrow? Get articles if that's useful than say yes. It may or may not be useful in our case, but let's say yes, the field names I tags would be called articles because it will hold an array of article objects. That sounds good and it's done. So I'll hit enter one more time to finish that up. So let's go look at what that did. An article and at the bottom, not surprisingly, we have a new tags field which is a many to many that points over to our tag entity also when like we saw earlier with our comments, whenever you have a relationship that holds an array of objects in your constructor, you need to initialize that to an array collection at generator took care of that for us. Then at the bottom we have the same gift tags and tag and remove tag that we're used to seeing. If you look at the tag and sti, it is a really similar thing. Many-To-Many pointing to an article and then at the bottom, 

OK, 

it has good articles at an article and remove article. Now here's one difference between many [inaudible] and many to many, one to one relationship. I'll open comment. Remember how there's the owning side in the inverse side with a many to one relationship, one to one is always the owning side because it's the side that has the foreign key relationship. You'll notice that both sides are called to many, so many, so many to many and article and then many, many in tech, so which side is the owning side? Well, it has to do with inside article. We have a little extra configurations as inversed by articles and that's pointing to the articles property in tack and over here it says mapped by equals tags which is pointing back to the tags property in article. 

What I'm getting at here is what a many to many. You actually get to choose which side of your relationship is the owning side. Just by switching the inverse by or the mapped by. The only side of this case is article and the reason is the owning side is because that's what we use in our. When we started generating our entity, we said that we wanted to add a field to article, so it may article the owning side, so the case of the many to many. When you go and generate the relationship, it doesn't matter too much, but I want you to think of which relationship you'll be more likely to set and use that as the main entry point, so for example, it's more likely that I'll add tax to an article, then add articles to a tag, so I made an article by owning side so I can easily add tags to it. And remember this is all important because when you said data, you can only send data on the owning side. In other words, you can add tags to articles and safe. 

Yeah, 

if you add articles to a tag, insane, it won't do anything. That's not entirely true because the generated code actually makes synchronizes the owning side. So thanks to the clever generated code, you can actually set data from both sides, but it's still something that I want you to keep in mind right now. This is set up. Go and run bin Console, make colon migration, and we're going to see what this looks like in the database. So let's open that migration file and check this out. It creates a table because this is the way that you model a many to many relationship in a relational database, so it creates a join table called article underscore tag and it only has two fields on it, article ID and tag id. This is very different than anything we've seen so far. This is the first time and really the only time that we have a table in the database, but we do not have a direct entity for that table. This table is created that magically by doctrine to help us relate tags and articles and as we make those relationships, doctrine will take care of inserting and removing records from this article tag table automatically. 

Let's talk about how to do that next, but first, let's migrate.