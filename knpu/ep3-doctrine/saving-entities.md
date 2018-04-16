# Saving Entities

Coming soon...

Or the first thing you want to do, the next thing we need to do is save. Insert some rows into our table. This is probably one of the easiest things to do in doctrine. I'm actually going to create a new controller called article admin controller. This will be a place where we can add new articles. I'll make an extent. The normal abstract controller will create a public function new, and I'll add the at route above this using auto-completion on the one from symphony components. Make this slash admin slash article slash new. We're not really going to build out an admin form here yet and we're just going to write some code to insert a new article into the database. 

Yeah, 

make sure this works. Return the response. The one from age two, DB foundation and. Well, let's just say space rocks. Include comments, asteroids and meteorites. 

Yeah, 

cool. Just that should be able to go over here and go to slash admin slash article slash new and awesome page is working. So how do we create, how do we insert a new article? Well, it's as simple as creating a new article object. Then telling doctrine to save it to the database, so quite literally it's article equals new article and then we're just going to start setting the data on it. So actually let's go back. I'll go to my wife to ask her is it tastes like Bacon and let's just use this as an example, so I'll copy the title article title or pays that notice I'm using these setter methods that were automatically generated into my entity. Also, when you use the entity generator, all these setter methods return this which allows you to change your method calls. In other words, we can say sets slug. Then I'll go copy the part of the url. I can go back and paste this here, but we want to make sure this is going to be unique. So let's just add a little random part on the end of that. Then we'll say set content and to get this let's go into article controller and awesome. Let's copy our fake markdown code 

and paste that here. That probably won't paced correctly, so you need to make sure that you have everything completely uninvented. There we go. 

Perfect. 

All right, so that's the title, slug content, and then the other fields that are honor and city are the only other field that our entity right now is published that. So to make testing nicer, we're going to, let's create, let's randomly make some articles published in some not. So let's say if a brand of one, a number from one to 10 is greater than two, then let's set the published APP, so article set published at and we'll say new slash date time, and to give us some randomness, let's use a sprint. F here will say percent minus the days. I'll give that a random number from one to a hundred. 

Perfect. 

So this one I just want to stop because all I've done is create an article object in set data on it. This is just random php the. To save this, all we need to do is just tell doctrine, hey, I want you to save this article to the database. You guys remember from the last episode that the biggest thing that outside bumbles give you are more services. The doctrine bundle gives you one very, very important service which is used for both saving and queering from the database. It's called the entity manager. 

Yeah, 

and actually if you go and run bin Console, debug con auto wiring, 

scroll to the top. You will see it doctrine entity manager interface. That's what we're gonna use to fetch it. So let's go back to our method and our new say entity manager interface from Dr [inaudible] hit tab and call that. Once we have the entity manager to save, it's actually a two step process which might look weird at first am persist article and then em Arrow flush. It's always these two commands. Persist, tells doctrine that you want to save this article, but it doesn't actually do it yet. Then when you call em flush, this is when the actual queries are made. It's done this way mostly so that you could create multiple objects at once and then just save them with one command 

or that in place. Let's give ourselves a little bit more of a helpful message here, so we'll se Haya new article ID is some number. Slug is some string and we'll say article and we'll start using those getters, so I say get id and article Arrow gets sluggish now known as we did not set the ID, but as soon as we save it, doctrine is going to set that id property for us, so that should give us the results. All right, you ready? Let's try it. Go back over and go to slash article slash admin slash article session new and article id one, two, three, four, five, six. Everything is inserting. If you want to prove it, you can load up your php, my admin or whatever tool you like to use to talk to the database. Or if you're in a pinch, you can always run doctrine. Colon queries, sql. We can say select star from article. Remember Dr uses underscored versions of the table and column names. Yes. And there we get six results. Awesome. So next let's talk about how we queried things from the database. OK.