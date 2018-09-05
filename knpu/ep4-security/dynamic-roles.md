# Dynamic Roles

Coming soon...

Now that we know want to create a new new page slash up a new page where a user can see their account information, find a terminal run bin console, make controller, let's get a new one called account controller. 

Awesome 

to open that up and perfect new slash account page, which we can see instantly if we go to that url. 

Perfect. 

Let's change the route name to APP under a square account to be consistent with other places. And I'm not going to pass any variables in for now. And if the template templates, account index dot html dot twig, let's just customize this a bit. Just say manage account for now. We'll just put an h one, 

manage 

your account. We're going to add a lot more details in a little bit. Alright, so obviously I only want this page to be accessible if we are logged in. 

So if I log out 

and then go back to slash account. Obviously right now anybody can access this. So we already know how to deny access for a specific role. Like in common admin controller, we're looking for a role admin, but how can we just ask is the user logged in or not? Well, they're actually two different ways. I'm going to tell you about one later, but the simplest one, the one that I like to use is just to check for role user, so boat my account controller so that it is applied to future methods at at is granted role underscore user. This will work because remember in the user class we're making sure that every single user always has this role, so effectively we're just checking to make sure that the user is logged in. So refresh now, it'll bump us to the login page. We can log in with password engage. Ended up bump us right back to the account page. Awesome. 

Now one thing we don't have at this point, we still don't have admin users. If I go back to slash admin slash comment, none of our users can actually access this page because none of our users have role Admin, so to make our site a little bit more usable, let's go to data fixtures, user fixture, and in addition to kind of our normal users, I want to create a couple of admin users down here, so I'm gonna copy the entire create mini block pasted below and I'll get this group of users a different name called admin users. Remember, this key is not important yet, but it will become important later if we ever want to read it, become important later. If we want to relate these users to other entities inside of other fixture classes, you'll see me do this later. Let's just create three admin users and for the email will say admin present the at the space bar dot Com. First name is fine password. We'll keep engaged. For simple, for simplicity, for the role, say user Arrow set roles will say role underscore admin. Now I don't need to say I don't need to also put rural underscore user here because our get rosemount. That again is going to make sure that's returned even if it's not stored in the database. 

All right, let's try that. Bin Console doctrine fixtures 

low. 

Well that finishes whoever. Let's go back to slash login. Let's log in as admin2@thespacebar.com, password engaged. Then go back to slash admin slash comment and perfect and notice we have access to this page. We also have access to our account page because our user has role admin enroll user even though technically only it has role admin in the database, so now that we have the ability to check whether the user is logged in or not, let's fix our little drop down up here. We should not show the login link once the user is locked in, so move back over and find templates based on age two months wig and scroll down a little bit. You remember earlier we added the log in page and we commented out our big user drop down, so now we can be a little bit smarter. I'm going to copy that entire dropdown. 

We'll say 

the question is, now how can we check whether the user has a role inside of twig you don't how to do it inside of a controller. How do we do it? Instead of tweak? The answer is with a special is granted function. So if is granted role underscore user 

else. 

I'll indent my log out link and said the first part I'll paste in my dropdown. Awesome. Check that out, refresh the page, and perfect. We have our user dropdown. All. Alright, so while we're here, let's actually fill in a couple of these broken links. So for profile we know that that url is APP underscore account. So path APP underscore account for log out. That's path APP underscore log out for create posts. So create an article we don't actually have as part of our site yet, but if you there is a controller called article admin controller and you can see we haven't actually put any logic here yet, but we do have a page for this. So let's add a name equals to this. We'll call it admin article new and we'll link to this page even though it's not quite working yet. And on top I'll also add the at is granted role underscore admin. So first I'll link to that admin article new, but thing is we only want to show that create posts if the user's logged in as an admin. So here we can say if is granted role underscore Admin, then show admin article new. 

Awesome. 

Alright, let's give this a try. Ever refresh and because we are logged in with an avid user, yes, we do see the create posts and we can access that page. Sweet.