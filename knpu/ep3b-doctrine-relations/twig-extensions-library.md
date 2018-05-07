# Twig Extensions Library

Coming soon...

Ok, 

all right. Time to bring this manage comments section to life by actually creating a comment table in good news. This is going to be pretty easy because of all the tools we have so far, so we need to query for all of the comments in the system so we need the content repository. 

Then 

we can say comments equals repository Arrow, and we could use find all, but I'm actually going to use find by passing it to an empty array and then creative at descending so that way we can get all the comments in descending order 

and then the render, 

we'll clear out the existing variable that we're not using anymore and pass into the template. Perfect. 

Yeah. 

Now that we have that, let's go into our template in the age too much weight and below the age [inaudible]. I'm actually gonna Paste in the beginnings of a table, has a couple of bootstrap classes in. We'll print out the article name, be comment author, the comment itself in when it was created, so down in the body. We can do it very simple, more comment in comments 

with our end for 

and then we'll put atd Dr and we'll start printing on the tds. So the first thing for the article, and then you're just printing the articles name, let's actually link to the article. So let's create a tag and we'll say h ref, I'll keep that blank for a second. And then inside of that will print the title of the article. Now remember we have a common object and we want the articles title. So the only way to get at here is actually to use our relationship comment dot article, which is an object. And then doc title. Now for the link, we're gonna. Use the normal path function and twig and we need to know the name of the route for our comments. Show articles, show page. So I've got an article controller you can see above. We have a name equals article underscore show, so I'll close that. Go back to our template and we'll link to articles. Show that we need to pass a slug wildcard. And again, this is the slug of the article, so we need to use comments that article, that slug so you can see how handy having those database relationships are. 

All right, let's keep going. Let's add another td. This time we'll print out comment, that author name, another td. I'm actually going to add a style equals with 20 percent on this one so it doesn't get too big and it will print out. Comment that content and eight for the 

date. We'll print out, comment that presented at and we'll just use the same filter we've been using. 

Awesome. So let's see if it works. Go back, refresh and boom, we got a big giant list which probably needs page nation and actually will add that later. Now, one interesting thing is check out the queries, 11 queries. This is that same end plus one problem we were talking about earlier. The first queries, the one we'd expect. It selects all the comments from the system, but as we loop over the comments and we start using the article data each time we referenced a new article, it actually needs to go and query for that one article. 

I can see it first squish the article data for it [inaudible], then for [inaudible] and then one 83, so we get 11 queries because we need one carrier for all the comments and since our 10 articles on the database, eventually we reference all of the individual articles and so we get 11 queries in total. So this is the n plus one problem and the question is do we need to solve it? And the answer is maybe I honestly wanted you to be aware of the n plus one problem, but it's not something that I always solve in my code because a lot of times having one querie versus 11 queries, especially on an admin page, it's no big deal. So I will show you how to solve it in a few minutes, but don't worry about it too much yet. Now, before we get there, one thing here is that these comments can actually get pretty long, so it's kind of messing up our table. So what I'd really like to do is maybe show just the first 30 characters of a text and then a dot.dot. 

Yeah, 

and actually if you go to that symphony com and click on documentation. 

Yeah, 

there's actually not a filter or a function that does this. You might think that there is thought searched for tweak extensions and click on the documentation for the actual tweet extensions. So we know that if we want to write custom functions or filters, we create a twig extension while there's actually a library out there called extensions. And what it is is nothing more. 

OK? 

Then a bunch of extension classes that contained some shortcuts in that. So for example, there's one twig extension, one called text inside of it. It actually gives us a truncate filter, which is super handy. We can see that if we click on the documentation about the text extension, so if we can install this library, then we can very easily use that. 

OK, 

so how, let's first copy the composer require line required tweaks. Lash extensions, 

yeah. 

OK, 

OK. 

Paste that in our terminal and wait for that to get installed. Now, one thing to realize is that this is just a php library is not a bundle, so it's going to give us the twig extension classes, but it's not going to automatically configure those into the service container. It just gives us the classes. It would be as this as if a library just gave us this app extension but didn't have any configuration to integrate that into doing in our project. You'll see what I mean in a second. So I'll go back to the terminal and we'll wait for it to finish installing. 

OK. 

OK, cool. And you'll notice it actually installed a recipe for this. So I committed before I started recording, so I'm going to get status and you'll see of course it updated the composer that Jason vial composer that lock out, but it also created a new yammel file and this is really cool if you move over and find config packages, tweak extensions that GMO. So the library gave us those classes, but it didn't automatically activate them in twin. So to help with that, there's a twitter recipe and all it does is actually list out the classes that you want and if you want to activate them, you just uncommon to. Now there's a couple of things going on here simply by having this text extension here that adds that service to the container. Until that means no, just means we don't need to add any further configuration for this service, we just want this service to exist. Second, thanks to the default section above specifically auto configure. That texts extension is going to act in the same way as our app extension we created earlier. Specifically the container is going to notice that this is a twig extension and it's going to automatically integrated into 

twig so that twig knows about it. 

Yeah. 

In other words, we can go into index that [inaudible] twig now immediately do pipe and in fact, before I even try it, go back to your terminal and run bin Console, debug colon. Remember, this is a nice little debugging thing that shows you all of the functions and filters that exist in the system and you'll notice that we now have one called truncate. This was not there a second ago before we activated that service in the configuration file. So move over, go back to our manage comments and perfect twig. Extension is a really great tool and I want you to realize there's also stuff for, um, until its date and some other good stuff. Yeah.