# Twig Block Tricks

Coming soon...

Just show off some join stuff. 

OK, 

we have comments, have articles, we have comments, um, but we need to talk about custom queries in how we can join the tables together in custom queries. And to do that, we're going to start by building a comment admin section. We're not going to fully finish the admin section, but we're going to bootstrap it. To do this, I'm going to create a new controller and since I'm feeling particularly the particularly lazy, I'll use been consultant, make colon controller and we'll create a new comment admin controller that creates a controller class and even creates one template file. So let's go check that out and let's change the url to slash admin slash content. Go move over. 

OK? 

And I'm actually going to open a new tab and we'll go to local host colon, 8,000 slash admin slash comment. And Oh, there it is. Our brand new common admin controller page even tells us where the source code lives. All right, so let's open our new template and start customizing this a bit. It's overriding the title block, which is cool. Change that to manage comments and then I'll delete all of the code down here and in order to get the page to look nice, I'm actually going to open the show template and I'm going to steal some market from this. I'm actually going to steal the first six dibs out of this. Go to index, don't close all those actives, and then all the way in the middle will say just to see if it's working. Managed comments. 

OK. 

All right. Move back over. Refresh and there it is. The 16th is actually give us this white border that you see also on the article show page and actually if you think about it, it's probably going to be pretty common for us to want a page where we have some nice margin and a white border. The homepage doesn't use this because it does its own custom mark up. A lot of our internal content pages are probably gonna want this markup, so having these six and not a great solution for that. So let's isolate this mark up into a new base templates so that we can really easily spin up new pages that have this markup. So my templates directory, I'm going to create a new content underscore base that eats too much. What's cool about this is we are actually going to extend the normal base that eats Jim Twig, but then we're going to add a little bit of extra markup that goes in when somebody uses this base class. To do that, we're going to override the block body as if we were a normal template. Then I'm going to go steal the first four. These are the four lives that really give you that structure. I'm going to pay stays here, make money for closing Devs, 

and then in the middle of this, this is actually where we want our content to go. Create a new block called block content body and the block, and I'm just making that up. So if someone extends this template in, overrides the content body block, then they'll get the normal base layout, but with this extra markup. So let's try that in our index template. Change the base layout to be content and underscore base and then it a block body. It's going to be block content, Bonnie, and then we can remove the four duplicated lives here, that things a little bit so that it looks nice and should we get over to your home and Admin and perfect, so let's repeat this same trick and show to use content base, change the block to content underscore body and then we can remove the first dibs and all the way at the bottom. We moved those four devs and then let's uninvent this little bit so that it looks nicer. So if we didn't mess anything up on our short template, looks great. And now it's very easy for us to create new content pages with our markup. 

Yeah, 

except there's one small tweak I want to make to my comment admin section only. Our project already comes with a [inaudible] public css styles dot css file, which has a bunch of styles for built in classes. I'm going to do inspect element on the manage comments live and I'm going to use a new class that already exists inside of that css file is called show dash article, container border green. 

We had this, you see we get this nice little green border on top and according to our design team, they want to have this on the manage comments page, but they do not want to have it on the article page. So the tricky thing is that this class needs to be applied to the show article, Container Div, and the problem is that this actually exists in our base layout, so it's not something that we can modify directly from our manage comments page. So how can we handle this? The answer is once again with a clever use of blocks, so in content base surround all the classes with a new block, we'll call it block 

content class. 

At the end of that we'll use our end block. So this case we've defined a new block and that block has default content. So if nobody overrides this block, it's going to have all of these classes. Then in our comment section, we can override that block content class. For this case, we don't want to override it, we just want to add a another class to it, so we'll use the parent function to print those. Then we'll do a space and we'll say, show dash article, container border, and then our end block, and that should be it. Go back, refresh the managed comments page. It looks perfect. It has that class in our show page does not have that class, so a little bit of clever tweet blocks and we suddenly have a really flexible layout. All right, next let's continue building out the comments page by listening to comments on the table. OK.