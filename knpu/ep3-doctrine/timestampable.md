# Timestampable & Failed Migrations

Coming Soon...

Another really nice behavior to have his time stamp bubble. 

If you go back on the library's documentation, you'll see this. This is really simple. It just allows you to have a creative that updated that field and your entities that just automatically update, so let's add that, but along the way we're going to see a surprise, so part of your terminology, so let's go on to generate those two knew we could just create those fields by hand, but let's generate them. Make con entity update. Our article entity created that daytime said it's knowable to know because created. That should always be set. Then same thing for daytime for updated. That updated. That should also always be set. It'll be. It'll equal to create that in the beginning. Then entered to finish those fields. Next you guys know the drill run, make a colon migration. Then I'll move over and opened that migration and it looks good. Alter table, adding the grand debt and the update of that. Perfect. Alright, moved back over and execute that migration doctrine. The Colon, colon migrate and it works. Wait, no it exploded. Check this out. Incorrect daytime value, a bunch of Zeros for column created at. Huh? So the problem is that our database already has 10 articles in it 

and when my sql tried to create a new column for that 

OK 

a column that can not be no, it had a hard time figuring out what value to put for it. So unfortunately sometimes migrations fail. Fixing them is a very delicate process. So let's think about this. What we really need to do is probably create those columns and allow them to be no first. Then we can update all the updated that values of those columns to today's date. Then we can do another alter table to then change them to not know. So this means we just need to modify our migration by hand. So instead of not no. For now we'll say default. No, and we'll do the same for the update of that column. Then below that, what does it add? Some of our own sql, it will say update article set. Creative APP equals now updated that equals now. But don't do it for now and just it. Just do that to start. 

Sorry. 

Now we can go back and try the migration again, but wait, you might, you might not be able to run the migration immediately. In this case, our original migration only have one statement in that one statement failed. This means that it's safe for us to just try this migration again because the migration made no changes. However, sometimes you'll have a migration that we'll have multiple sql lines and it might be the second or third line that fails. In those cases, if you try and rerun the migration, the first line will be will be running for the second to time. Basically your migration system isn't it in an invalid state. In those cases, what you actually want to do is run bin Console doctrine, Colin database call on the Dash Dash Force to drop the database. Then you will create the database and then you'll finally run bin Console doctrine migrations. Migrate again. In our case, we don't need to do that because none, none of the sql statements in our migration executed. Plus, I kind of want to keep the existing articles in the database because I want to make guarantee that this migration works with existing data. Say a little bit tricky. 

All right, so run, migrate and this time it works. The last thing we did do though is we need to make the. We should now be able to make those fields and not not just to do that, just run a. just generate another migration. Make Colon migration. 

Yeah, 

go look at that. And perfect. This just changes those fields to be not no. 

OK. 

Run Doctrine. Migrations. Migrate One last time and we're good. 

So Tom Stamp, whenever you use a new behavior and stuff, doctrine extensions bundle. First thing it needs to do is actually activate it, which again is described way down here. So in our case, go the convict packages, stopped doctrine extensions and it will say time stamp a bolt through. And the second thing you need to do is add some annotations to your entity. And this isn't the library's documentation. So you can say we just couldn't do an at get mo, timestamp, bubble on, create an ad, get no timestamp on update, awesome article. It's find our new fields. I'll say at times they available on equals. Create copy that and put that down for updated APP and do on update. All right, we should be good. So let's flip over now and reload our fixtures to make sure that those fields are being automatically set doctrine. Colin fixtures, Poland load 

no errors, which is good to make sure it's working. I'll run doctrine, colon, colon, sql, select star from article. Guess there is right there and we don't see it yet, but now whenever we update and Anthony get updated that it's going to be set automatically. Now I love the timestamp bubble so much. It comes in handy sometimes when things go wrong. So I like having this on every entity to make that super easy. We can leverage a trait in the entity directory, create a new PHP class called time stamp bubble trait, and of course change its type to trait. Go back to article, copy of the updated and created that and updated that. Delete them from there. 

OK, 

and then after I closed a few files, I'm going to paste those into my trade. Now an article up at the top, we can say, use the timestamp, a goal trait. 

What? 

Because timestamp mobile is so awesome. I like having it on all of my entities and unfortunately there's a shortcut for doing this. We actually did way more work than we needed to. I want you to completely delete a credit app and updated that field and completely delete the getter and setter on the bottom. Instead go all the way to the top and type. Use Time stamp, visible entity. Yeah, I'll hold command or control and click into that. Check this out. It just creates the exact thing that we had a second ago created at updated, at segregated, at an o. get created at everything is still there. Exactly. So basically we just get that functionality for free to make sure there isn't any slight mapping differences in the database. Run Bin Console, make colon migration and awesome. No database changes were detected so their fields are named exactly like our fields are named and now it should be able to run doctrine, fixtures, load no errors, and when I it still works. Hello, timestamp bubble entity. All right guys, 

I hope you're loving doctrine. We're getting a lot of functionality fast. We've got magic like timestamp a bowl, slugable. We've got really cool fixtures, system with fake data and we have an awesome migration system to go with this. One thing that Dr Ha has that haven't talked about is when you go to production, we did talk about how you have to have doctor migrations migrate, but also want to point out that doctrine comes with its own production config. It actually makes sure that all the things that should be cached are being cached, so you get a really nice performance system out of the box. The big thing that we have not talked about yet is doctrine relations and that's gonna be the topic of our next tutorial, so I hope you'll join us there. Where are we going to get really good at joining tables together and creating a really rich database? All right guys, see you next time.