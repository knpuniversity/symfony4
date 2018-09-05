# Csrf Remember Me

Coming soon...

I log in form is working perfectly bought. There's one tiny annoying detail that we need to talk about and that is that every form on your site that perform some action like saving something or logging you in, nice to have CSRF protection when you use symphony's form system. CSRF protection is built in, but since we're not using simply form system for our page, our form is not protected, but no big deal. It's super easy to add. 

It's two parts. First, we need to add an input type equals hidden field to our form given name. How about underscore CSRF token, and then for value print, use a special CSRF token twig function past this, these string authenticate and close that up. The string authenticate is just kind of the name of the CSRF token and you'll see in a second when we checked to see if this, see if this token is valid, we'll use that same string so this could be anything, right? The only other step we need to do is we need to make sure that's valid inside of our login form authenticator. The first thing we need to do is get credentials in addition to email and password. We're not going to set a CSRF underscore token. He does that to requests for request Arrow, get underscore CSRF underscore token. 

Perfect. 

Now down here and get user. This is the place that we're going to want to check the CSRF token. We don't want to do it down here and check credentials because we want to do it before because I want to do it before we, before we queried for the users that we can stop the log in process as early as possible. So how do we check if a CSRF token is valid? Well, like everything in symphony that is probably done with a service, so even without reading the documentation, we can run debug bin counsel, debug and fake auto wiring. 

I'll search this for CSRF and yeah, we have a couple of things here. CSRF token manager and something called a token generator and somebody called token storage. These other two, these two interfaces are a little bit lower level. The CSRF token manager interface is, is that's the class that's going. That's the interface. That's gonna. Let us check to see if the token is valid. To get this, go back up to your constructor. Let's add a third constructor arguments, CSRF token manager interface. I'll read type the e and hit tab to auto complete that to get the use statement, and then we'll add is CSRF token manager argument hit option, hit alt enter to initialize that field. 

Perfect, 

and we'll see how that class, that interface works. I'm gonna hold command and click into that. All right, so we have a good token method refresh. Token removed token perfect is token valid returns, whether the given CSRF token is valid and we need to pass this as CSRF token object 

which has two arguments, the ID and the actual CSRF token value. That Id. What that's referring to is this string authenticate whatever string you used here when you generated the token, and then the value is going to be whatever's submitted. The value was at the user submitted. So I'll close this back up, back to login form authenticator and find get user. So first we'll add token equals new CSRF token hit tab. I'll complete that. It'll pass this authenticate and then the CSRF token, which has credentials, and then CSRF underscore token. Remember whatever we returned from getting credentials like this CSRF token key is what's past to get user. Then if not, this Arrow CSRF token manager Arrow is valid, is token valid passive token van. We're going to throw a special new invalid CSRF token exception and that's it. So first let's try logging in successfully. Let's refresh the login form. So we get our new hidden inputs. What's you spacebar one example that come? Any Password and got it? We are logged in. Now let's go back. I'll inspect element on my form 

that's a mess with that CSRF token long again and got it invalid CSRF token that comes from that specific error message. Perfect. So that's just another box that we can check while we're looking at the html form. There was one other field that we originally had in here, and this is a remember me check box currently you can check this, but that doesn't do anything. Fortunately activating, remember me and in symphony is super easy. You just need to do two things first. Make sure that you have a check box with no value and make its name underscore, remember, underscore me. That's the magic name that you need to have there. The second thing you needed to do is go to secure that Yammel and under your firewall. Add eight. Remember me section and this. Love this. I'm going to put two pieces of configuration. The first one is called a secret, which I'm going to set two percent kernel that secret. The second thing is going to be a lifetime. Which else? That two, two, five, nine, two, zero, zero, zero, which is 30 days in seconds. By the way, the lifetime that if you don't have, if you don't specify a lifetime value at defaults to one year, so two important things here just by having this. Remember Miki here, insecurity that Yammel. 

If the user checks this, a checkbox whose name is underscore, remember, underscore me, then a, remember me. Coconut token is going to be instantly set. The second thing is the secret. Here is a cryptographic secret that's used to generate the value in that. Remember me cookie in symphony, if you ever need a cryptographic secret, there's a parameter called kernel that secret. Remember anything surrounded by percent. Science is is a parameter. This is one of those built in parameters that symphony hat, that symphony that you can use in symphony. I remember if you ever want to see a list of all the parameters and symphony, you can do bin Console, debug container dash, dash parameters, and that will give you the 

full list 

and they're really important ones are the ones that start with colonel. These are a couple a bunch of built in once to help you do your work. You can see our kernel dot secret, which is set to an environment variable 

right there. 

Alright, so let's try this out. I'm going to bring my inspector backup. I'll refresh the login page, then I'll go to application 

cookies 

and you can see that right now the only one I have is this php session id. 

You Not gonna do that again. 

This time I'm gonna. Check the remember me box. I have any password. Hit enter an s. Now we have a remember me cookie set here and the cool thing is you see I'm logged in a space bar one. If we delete that phb session Id, you can see it's q three, hp and refresh. We are still logged in there. Remember me token causes a new cars are new session to be activated and you can see we're still logging in a space are one example. Dot Com. You can even say there's a new token class that's a low level of detail. It's not very important, but you can see that we're logged in as remember me, so pretty cool feature if you need it and just like with the log out, there are other options you can add under. Remember me if you need to customize the behavior a little bit more.