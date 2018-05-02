# Pagination

Coming soon...

A lot of times this comment, this can get really long and actually it's kind of get worse and worse when we pushed this to production. Not only is it not very usable, but this is actually something you need to watch out for with doctrine printing 100 queering and printing 100 objects, probably not that big of a deal trying to query and print a thousand objects. There's a good chance that your page is going to fail before it loads, so let's see if we can add page nation to this page. Doctrine itself doesn't come with any page nation library, but fortunately there a really nice bundle called p Page paginated or bundle as usual. 

No 

disclaimer is I did not create this bundle. I just really like it. 

Yeah, 

so let's go down to the composer. Require line copy of that fire to terminal and running. Composer require Katie labs slash can p page in your bundle? 

Yeah. 

While this is installing, let's go back to the documentation. As you know, the main purpose for installing a bundle is that it will give you services and that's true for this bundle. Before that I'll say notice it has some details about enabling your bundle. We don't need to that symphony for. If you look down at the usage example, you'll actually see that from your controller. You can say, this Arrow get can, p underscore pollinator to get a service with that id out of the container and the page and enter is really powerful because you can basically pass it a querie 

yeah, 

he has what page you're on, so you say we're reading a page query parameter from the request and the number of items you want per page. It will basically figure out the rest. So if you want 10 per page and you're on page three than the page, Nader will contain the results that should show on that page and we'll see exactly what that looks like in a second. 

Now, the only tricky thing is that the documentation for this is a little bit outdated and symphony for while still possible. We don't like to fetch things out of the container directly like this instead, and be like to use auto wiring to auto wire this as an argument to our controller or as an argument to a service, and since it doesn't say anything in here about that, let's go see if we can figure out what Ottawa class or interface we can use for auto wiring back to my terminal. Excellent. The install finished. Let's run bin Console. Debug colon auto wiring. If you search for pager, boom, there it is. Apparently there is a nate or interface that we can use to get that same server a service, so that's what we'll go now into our comment admin controller. Add that is a third argument, originator interface type, the last letter and hit tab to get the use statement for that and we'll call it 

page and nation a page in next. 

Go back to the use of example and we'll copy the page and nation equals line and this is what actually prints that page and enter object. No. One important thing here is that when you use the page editor, you're not. You are no longer responsible for making the actual queries, so we're not going to actually query for all the comments. Instead we're going to build a query and pass the built querie originator, so this will actually be a querybuilder object, so this means that in content repository we want to factor this method a little bit to return the querybuilder but not actually the results. So for example, I'll remove the average return is that I'll say that this were to return a query bowler from doctrine. Next at the bottom, we just get rid of the get queries and get results and this will return that queered filter and let's rename it to get with search query builder. 

Perfect. 

I'll copy that they've done over here. Instead of comments equals we can say queer golder equals repository arrow. Get with search querybuilder and pass it our queue. Then down below I'll change this to querybuilder and that's it. 

OK, 

finally, instead of passing the comments, we're actually going to pass this page and nation objects into our template. All right, so let's find the template. We need to update it. The first thing I'm gonna do is actually at the top where we have managed comments. Let's actually put the total number of results that are being shown. We may only have 10 results on this page, but there might actually be 100 results in total. To do that, and by the way, I'm getting this code from the documentation we can call that gets total item count 

and you notice you don't always get auto-completion a doctrine or we can't always help. The PHP Plug-in can't always help you. The most important part is down here in the loop, so instead of [inaudible] comment in comments you say for comment in page and nation, this page and nation, OK, variable isn't object, but it implements iterable. So that means you can just loop over it. This won't contain every comment, it will only contain the comments needed for this page, so it's perfect finally after the table, but we're gonna need some navigation so we can go to page two, page three, and there's a helper that you can use to get that. So I'll copy the campaign page, nation runner, go back and we'll paste that at the bottom. And that's it. It's actually really simple. So let's go back to our page refresh and perfect 100 comments. Check this out. Only 10 shown on this page. We're go to page two and you see the page equals two on top. We get different results and now we're on page two. Page three different results. Page five different results. 

Yeah, 

and we can even use the search. So let's search for something really common that matches [inaudible] results. You can see the question mark q equals s, t, and if we go to page two, it's page two, but it keeps that query on there. Now, the only problem with this, and it's pretty obvious, is this navigation is horrible. Fortunately the bundle comes with a bunch of different themes built into the page and nation. So if you scroll back up to the configuration example, obviously you don't need to configure anything on this bundle, but there's lots of options that we can use to configure it. The most important one is this template page nation. The says which template to use internally to build that navigation link and there's one built in for twitter, bootstrap version four, which is what we're using on the site. So first question is if we want to configure this, where should this stuff go? Sometimes a recipe will create a profile for us like the stuff doctrine extensions, but in this case that didn't happen, so we'll just create it by hand. Create a new file and config packages called camp underscore a page, and nadir at Yamhill. 

Yeah, 

we're using that name to match the root key, but as we learned in the previous tutorial, you can actually name this file anything. It doesn't matter. Next, I'll copy everything down to the page and nation line pasted in here, but then let's delete all of those things because those are the thought value so we don't actually need them. And then for the template we need copy the bootstrap version page, page nation faced that in there and we should be good. Flip over, refresh and beautiful. It still works of course, and now we're using bootstrap markup, so just looks good on a box. So pays nation, it's Ah, it's awesome.