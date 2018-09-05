# Fetch User

Coming soon...

What's your have your authentication system set up? That's the hard part. Most of the time you're going to be in controllers and really there's only two things that you can do. Either you can check to see if the user has access, which is pretty simple and the only other thing that you will ever need to do is figure out who is logged in, which is it, which is exactly what we need to do in a comp controller so that we can start printing out details about the user's account. So how can we get access to what user is logged in from inside of controller? Well, it's this arrow. Get user. 

Yep. That's it. Refreshed that free press that no, 

if you go back to your browser and go to slash 

account, 

go back to your browser and go to slash account. Yes, there it is. This gives you the user object. So our user entity that is logged in, which is awesome because we can do all kinds of cool stuff with it. So just as a simple example, let's try to. Let's log the users. What's a log? A message here that includes the user's email address, so I'll type in lager interface logger as an arguments. Then we'll say lager Arrow debug checking account, page four, and we'll say this Arrow, get user and because we know this is our user entity, we know that we can call, get email on it, so arrow get email, cool, move over, refresh no errors. If you click any anywhere down on the web debug toolbar, you can get into the profiler, go to logs, go to debug and down a bit. There it is. Checking account page four, space bar five at example, dot com. 

Now the only kind of annoying thing is that you noticed I didn't get any auto completion on this. The reason is that if you hold command or control clicking to get user simply doesn't know what your user class is, so all it can say, so it can't really tell you what this is going to return. So to get around this, what I like to do is create my own a base controller, so the controller directory, printing new php class called base controller. I'll make it abstract because this is not going to be a real controller and I'll make it extend the normal abstract controller that we've been using from symphony inside. I'm going to go to the code, generate menu or command n on a Mac, do override methods and look forward get user, so we're going to override the gate user method, but not actually because we want to override it. I'm going to return to parent call and call and get user, but because I can add a return type to this, that's my user entity, so thanks to this from now on, instead of extending base abstract controller, I'm going to extend base controller. 

This means that I will get nice little all my proper odd completion on get user. I also use base controller just to add a nice shortcut methods. If there's something that you're doing really commonly, add new protected function here and then you can use it from any of your controllers so I won't go and update my other controllers to extend base controller right this second, but little by little as I need those shortcuts, I'll use my custom base controller. It's just a nice little short cut. All right, so that's how you get the user object inside of the controller. So how can we get the user object inside of our template? Find the templates directory and find our account slash index dot html twig. The answer is app dot user App. That user, so we can say APP dot, user dot first name. Try that out, go back to slash account, 

and 

perfect it prints out so when you're inside of Twig, in symphony you have exactly one local variable called APP and it just has a couple of convenient things on it like app dot user, an APP dot session. After that, users by far the most useful. And since this returns are user entity, we can call first name on it. So you get the first name to call, get first name on that object. It's just that simple. 

Yeah. 

Yeah, because this page is super ugly. I'm gonna. Go back to my controller, clear out my age, one in paste in some markup that I I prepared. You can get this market by copying the code block on this page. This actually reuses some of the special classes 

I'm from our logging 

that css file that we used earlier, right? 

Or is it templates? Oh boy. Ah, 

few refreshes right now. It still looks pretty terrible and that's because, oh, but a cool robot, that's because this new market new uses another css file. So if you download the course code, you should have a tutorial directory. We already copied this log in that CSS earlier. I want you to copy his account that css, find your public directory, open css and paste it there. Then just like we did before, to include this one style sheet on this page, we'll do block style sheets 

and block 

call the parent functions that we don't override all the normal ones. Then do rink link and I will say account dot css, hit tab and pizzerias Germaphobia and for me, all right, now refresh. Awesome. It looks much better except you'll notice I have a bunch of question marks in there because all of this markup is just hard coded right now. So now we can start to fill in the dynamic pieces. First of all, for the Avatar, we're using this cool robot hash site where you just pass it in email and it gives you the Avatar. So let's replace this with app.user.email. I think we can do down here as welcome back. Let's replace that with APP, that user, that first name. All right, now when I move over and refresh, yes, brand new avatar for us and we have the first name. What we're still missing this twitter handle because our user object doesn't have a twitter handle yet, so let's do that next. Also add another cool little shortcut method or a user class in talk about how we can fetch the user object in the one plate. Last place we haven't talked about from inside of services.