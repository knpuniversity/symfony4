# Search Form

Coming soon...

Yeah, 

because there's a lot of comments that are going to be on this page. Let's add a little search box on top so we can find the comment we're looking for, so let's move on to our template and for this we're not going to use symphony's form system because we haven't learned about it yet, but also this is a super simple for them and as one field it's actually overkill for symphony's form system above the table. I'm just going to pass a good old fashioned normal form. 

OK. 

What I want you to realize is that it has only one input field. It's name is q and then it has a button down at the bottom. Notice the form has no action equals that means that it will submit right back to the same page. It also has no method equals, so it's going to. It's going to submit via a get request and is the proper method to use when you have a search form. 

So if you move over and refresh. Yep, there's our form and you can hardly see if we search for, let's say if some if some enter. Of course, we're not using that data to filter yet, but you can see the question mark q equals if some up on top. Great. So let's go into our controller and actually process this. So the first question is how can we read query parameters from symphony, but we haven't talked about it much yet, but anytime you need to read hitters or query parameters or post data or uploaded files or anything, you need access to symphonies request object to get that. You can actually type hinted inside of your controller requests, makes you get the one from http foundation because there are several and then request, so are now actually three common cases, [inaudible] three common type hint cases in your controller. You can type in services as we've been doing many places. You can also type in entity objects if you want symphony to automatically clearly for the objects by using the wildcard or you can type in the request object. The request object is a special case. It's not technically a service, but you can kind of think of it. There are other tricks you can do here as well, but those are the main three. 

Now that we have the request object because a dollar sign q equals request Arrow querie Arrow get cute. So querie is how you get the query parameters. There are a number of other properties for the other things like request Arrow headers, request Arrow cookies, request Arrow files. 

Yeah. 

All right, so now that we have the query parameter, we're going to need to use that to make a custom queries we can't use find by because we're going to need to use the like keyword, so we know that we need to go into our content repository and added new custom function here. So let's call it public function. 

OK, 

find the all with search and I'm going to give us a knowable string argument called term reason. I'm making this knowable is I want for convenience. I'm going to allow this function to be called with no and if there is no term past and then we'll just show all of the comments 

above this. 

I'm going to go ahead and add any return. We're gonna. Add a at return to say that this is going to return an array of comments in that will help PHP storm. All right, so we know how to do custom queries. We always need to create a query about first, so I'll say this pre query builder and we'll give our alias. We use the alias of seat next. If there is a term specified, then we're going to need an. And where? Here's the tricky thing. I want to search a couple of fields on comment. When we search, I want to search for the [inaudible] 

yeah, 

content obviously, but I also want to search via the author's name. So we actually need an org side of our queries for the first time. 

Yeah. 

Now if you look at the query builder is actually is in or where, but I almost never use it and the reason is that it gets very confusing. If you think about a complex queries about where your parentheses are in your, in your ors, instead of always use an aware and if I have any where clauses, I put them right inside of here. So for example, we can say see that content like then we'll put in a 

OK 

a wildcard current called term, or you can put the order right here, see that author name, 

like term. 

Then after this we'll say set parameter. Of course we need to fill that term. And the only weird thing here is that term actually should have the percent signs around it. So we'll say certain percent 

that term percent and that's it, 

not the bottom. In all cases we're going to want to say return qb unless edit an order by for our c dot created at descending. And then we always finished with get queries and then either get results for an array of results or get one or no result. If we want only one row, this case, we want to get an array. 

Have comments. 

All right, so that looks good. Let's go back to our admin controller. In here. We'll use commas, eagles repository, Arrow, find all search and we'll pass in that queue. 

Yeah. 

All right, so let's see if it works. First thing I'm gonna do is go back 

and remove the cue and it looks like we've got everything. Then let's search for something very specific like this Latin word here and yes, much smaller results set and let's also try the author field will search for Ernie. Got It. So the only problem is you'll notice all the only thing I don't like is that we're losing the search term right here inside of our search box. So if you look at our template, that's easy enough. We just need to add a value equals onto our input field. But how can we get that query parameter value? Well, one thing we could do is we could pass this cute variable down as a new variable indoor template and that's totally allowed, but there's a shortcut in the template. He was curly curly app that request that Querie dot get cute. 

Yeah. 

So go back, refresh and you can see it's filled in that box. So here's the deal. When you use twig inside of symphony, you have exactly one global variable called APP. In fact, if you go to your terminal and run Bin Console, the Book Colin Twig, you will see that global's App and it tells you that this is an object called an APP variable. I'm going to go back to my terminal and type shift shift and search for at variable and you can actually load that up and see what's inside. Ignore the setter functions. Those are there for setup. Do you have something called get token which relates to security. Get user relates to security and then also get the request which gives you the current request object. That's what we're using is also gets session, get environment, get debug and something called flashes which are used to show temporary messages, so not a lot in here, but a few things that are really handy. So when when we say APP that requests, that gives us the request object and then that queries get is the same thing that we're doing inside of our controller. It's calling queries the queries property and then calling get on it, and then we're getting the cue off of that. So don't forget about this very important queer variable. So next though, let's up the challenge and I also want to make this search include a field on article. I want to search the articles title to do that. We're going to need a join.