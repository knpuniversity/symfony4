# Is Auth

Coming soon...

I mentioned that there are two ways to check whether or not the user is simply logged in at all. The first one is role underscore user. That's the one you should use 

and it works because we have set up our role so that every user has this role, but I want to show you the other ones so they know what it means when you see it and it also touches on a couple of other interesting things. So to show this off, I'm going to go to secure that Yam on add. A new access control will make its path carrot slash account karen slash account, so it's a bit redundant. This is just an example. It's a bit redundant because this is already protected the annotations, but for the roles instead of role in user use is authenticated 

fully. 

This is a special string that simply checks to see if the user is logged in. In our system. The way our system's set up, this is 100 percent identical to roll underscore user. Actually if you go back to your site and click 

alright, 

I think I can site and refresh. Yup. You can see that we absolutely still have access, by the way, if you clicked it back down on the web debug toolbar, this security areas pretty sweet. It shows you what roles you have. It also shows you some lower level information 

about our 

security, including our authentication listeners, which you're talking about earlier, and a couple of other things. Well, most importantly, what I really want you to see is down at the bottom, it has a little access decision log. This is pretty sweet. 

Shoot a 14. 

I'm going to start over recording part of that right now, 

so if you move over, we'll go back to r slash account and no surprise access granted. By the way, click the little security icon on the web debug toolbar. This is some pretty sweet stuff in it. In addition to saying who you're logged in as and your roles, and also has a key down here with a little bit of lower level information about your security system, which might be useful as you're getting a little bit more advanced. What I really want to show you is all the way at the bottom, ah, the access decision log, this records every single time that we checked whether or not the user had access on this page so you can see the first one you can see is authenticated fully, is returning as access. Granted. You can also see role user two times enroll avid once one of those real users is coming from our account controller, the other role users coming from his grandson, the template, and then role admin inside of our template here. So really cool way to kind of debug what's going on in your system there. Anyways, as I mentioned, 

real user is authenticated. Bully are effectively exactly the same, but this does touch on another interesting question in our site. A lot of the pages are going to be public because they're going to be. It's a public newspage, but in a lot of sites you want a lot of sites are different. You actually want every single page of your site to require authentication or or maybe almost every single page of your site to require authentication. Access controls are a great way to do this. For example, if you just change this to carrot slash because this is a regular expression, it is everywhere else. So slash, so this will match everything and it required logging on every page. Again, you can use is authenticated for here or role user. They're the same thing. So if I refresh the logged in, I of course still have access, but now log out and 

Whoa, this page isn't working. Local host redirected you too many times. So the problem with this approach is that because we weren't authenticated and every page requires authentication, it redirected to the to slash login. But guess what? Wagon and requires us to be authenticated. So what does it do? It redirects us to slash login. We have made security so tight on her page that you can't even get to the login page. So here's the really cool way to fix this at an access control. Bug this for carrot slash login, you can put a dollar sign in the end if you want to match this exact you were out or leave it off. If you want to match slash login slash anything, then say roles is authenticated anonymously. This 

roll 

is a role that literally every single user has in the system. Always. Whether you're logged in or not, you always have is authenticated anonymously, so that might at first might seem like an entirely worthless enroll, but if you go back and refresh, it fixes our problem. Remember symphony goes down, the access controls one by one, and as soon as it finds one access control that matches, it uses that one in stops, so when we go to slash login, the first access control is used. Everyone has is authenticated anonymously, so access is granted for every other. You were on the page on our site, it's going to require us to be logged in. Now there's one other special is authenticated string. There's three. Total special is authenticated strings. Change is authenticated fully to is authenticated, remembered is authenticated. Remember it is almost the same is authenticated fully. If you just went to your site and logged in, you would have. You have authenticated, you have is that dedicated fully and is that thank to remembered and of course is authenticated anonymously. 

Yeah, 

what if you closed your browser and reopened it, but if you use the remember me functionality and you close your browser and reopened it and we're only logged in thanks to the remember me token, then you would have is authenticated, remembered, but not is authenticated fully. Basically what this allows you to do if you use the remember me functionality is that you can protect your. All of your normal pages was is authenticated, remembered, which basically means that you only care that the user users actually logged in and you don't care whether they just logged in during this session or if they logged in via the remember me cookie. Then you can protect very important pages like the change password page, which is offense authenticated fully. Then if a user tries to go to that page and they're only logged in via the, remember me cookie symphony will redirect them to the login page. We're redirecting the log in page so that they can authenticate fully. That's it. By the way, I'm showing you all of these examples via access control, but you can use these in side your controller or inside of twig. There's nothing special about this area. Alright, since our site is going to be mostly 

public, I'm going to uncover these examples right here.