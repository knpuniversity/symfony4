# Updating an Entity

In the previous tutorial we created this fun little heart feature. You clicked the heart and it makes an Ajax request back to the server, which it should update hardcore on heart. 

The new thing, 

you can see the age extra quests being recorded down here. Actually it's just a bit of a dummy feature right now. If you look on the public 

js directory article under scope showed that Jazz, you can say we wrote some really simple Java script that said when that article is clicked, then it actually changes the class on the heart and then it sends a post request to a certain end point the reads the h ref off of the link that was clicked and then updates the html with your hearts returned from the server. So let me show you what is going on here. When it reads the [inaudible] off the link, it's actually reading this age ref right here. So we have an end. We have a route called art article, title heart, and we send these slug of the article up to this end point that lives in article controller and near the bottom toggle heart action. But you can see this doesn't actually do anything, it just returns a jason response with a random number of hearts that our Java script reads that hearts kiosk in updates what's on the page, which is why you see this random behavior right now. But now our heart count is actually stored in the database, so let's do this correctly, or at least more correct. 

So we an article controller. The first thing we need to do is actually use this log to eye for the article object and actually we don't need to do that because we can use this shortcut, just replace these slug argument with a tight end article article and as we learned earlier, symphony will automatically query for that for us. Then to update the heart count, we can say article Arrow set heartcount article Arrow, get heart count. Now I do need to mention though, it's not important for us. If you had a really high traffic system, this would. This could introduce a race condition. 

Yeah. 

Between the time this article is queried for and when it saves 10 other people would have liked the article, which would mean this would actually save the wrong number. That's not important, for example, but just something to keep in mind. Then at the bottom, instead of the random number, we can say article Arrow get hard count. So the question is though, how do we update? How do we save this article today about is how do we do an update queries? We already know how to do a insert queer. He would call persist in flush. It's update. It's the exact same thing. 

Yeah. 

First get the entity manager by adding an entity manager interface argument. 

OK? 

Then just call em Arrow flush. That is it. There's two important things here. First, notice that there is no persist because when you're updating doctrine already knows about your article objects so you can call persist, but it's unnecessary in second flush actually does a bit more than I explained earlier. Second, when you call flush, doctor knows already that the article object is not new, so it knows that it needs to do an update. We're not an insert so we don't have to worry about it. Doctor knows whether it needs to insert or update articles in the background and so yeah, that's it. 

Yeah. 

Go back and refresh. When you do, you can see the real heart count for this article is 88, 89 and if we refresh that actually stays. We can go 90, 91, 92 93. Now obviously in a real site we would need to do more work to make sure that I can only heart and article one time, but we need to get into database relationships to talk about that oil. But this does update that number in the database and safe, but there's one small improvement that we can make and it's kind of an important topic instead of calling me when we were really doing here is we're incrementing the heart count by one. So instead of putting all this logic and our controller, go over the article and at the bottom create a new public function called increment 

in heart counts. 

Give it no arguments and return self like all the other set or methods here. Say this Arrow, heart count equals this Arrow heart count, 

last one, 

just this era, hard count plus plus if you want. Then after this, return this back in the article controller. Of course, now we're going to say article Arrow increment heartcount. 

OK, 

and the only reason we do that is because it reads really well. Article Arrow, increment heart count is very descriptive. In fact, this touches on a controversial topic related to your entities. Notice that for every property we have a getter and setter methods. 

Yeah, 

and I really liked doing that. However, some that's fine, but sometimes you might not need a good or method or you set or method. 

For example, we don't really need a set heartcount method because we're never going to set the heart cow. It starts at zero and then we're always going to increment it so we could actually delete these heartcount method, which just makes our code a little bit simpler actually will keep it because we do use it when regenerate some fake data, but you get the idea. So even though the generator starts with all the getters and setters, I want you to feel free to rename them, delete them, and create new methods that are more descriptive. This makes your entity more descriptive and fit your business logic better. OK.