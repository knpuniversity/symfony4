# Saving Relations

We know that our comment entity has an article property and an article underscore id column in the database, so the question is in php code, how do we actually set that? How can we relate a comment to an article? It turns out to be very easy and also very contrary to your thinking. If you're very accustomed to thinking about the database. Let's open up our data fixtures. Article fixtures class, 

OK, yeah, 

and just for simplicity, I'm going to hack in a new common objects here in the bottom. I'll say comment [inaudible] equals new comment, then comment on one Arrow set off name and we'll go grab our Mike Franky Arrow set content or grab one of our fake comments. 

Perfect. 

That if we just stop here, obviously that is valid coach who actually create an entity and saving the database. It just doesn't actually relate it to the article and actually if we try right now, bin Console, doctrine, fixtures, load 

be 

then to actually persist that. In this case, since we're creating it by hand, we need to persistent manually, so I'll go to the top and I'm going to use that manager variables so it becomes available inside of here at the bottom we'll say manager, arrow persist, comment one. 

Now obviously that's enough to actually create and save an entity. It's not related to article, but it's valid and actually if you try loading the fixtures with doctrine fixtures, load, it'll fail within integrity constraint violation. That article ID cannot be no, so it's trying to create the comment, but it doesn't have a value for the article id because it's because we haven't set up the relationship yet and the reason this throws an error is that in our comment in-state, you'll notice we have this joined column annotation that says noble equals false. That's the same as having knowable equals false. At the end of the column, it makes the column in the database actually required. If you didn't want him to require, you could change that to noble equals true. Anyways, how can we relate this comment to the article? The answer is by calling comment one Arrow set article article, 

and that's it, 

and this is both the most wonderful thing and the weirdest thing about doctrine relations. You do you actually relate objects. Notice we do not say set article and then say article Arrow get id or something like that. No, no, no. That's how it saves them the database, but all we need to do is actually set the article object onto the comment object. You need to pretend like there is no database storage in the background that creates these foreign keys. All you care about is I want a common object related to an article object. I'm going to copy that entire block. Let's create a second comments, make things a bit more interesting. I'll copy a different comment text and then I will paste that into set the content. All right to see if this works. Let's go over. 

Yeah, 

reload our fixtures, no errors, and then check this out. Run Bin Console doctrine, colon queries, sql. Select Star from comment and there it is. Look the comments, ideas 20 down here. The article underscored the Acetyl 1:18, and if we scroll further, it's [inaudible]. These are the ideas of the articles that were just loaded in the database, so simply by setting the article on the comment when the comment saves, of course doctrine goes and gets the articles ID and uses that to save in the background, but in php code we always relate objects to each other. All right, next let's talk about how we fetch things off of this relationship.
