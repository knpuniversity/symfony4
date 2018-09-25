# Impersonation (switch_user)

 Now while
we're here, I'm going to talk about one other really cool feature which is called
switch user. A lot of times, and here's, here's how it works. If you're an Admin
user, sometimes if you're helping debugging an issue for the user, you'll be really
convenient. If you could just log in as that user for a second and you can totally do
that

insecurity, .yaml Andrea your firewall. I'm at a new key called switch_user set to
true. As soon as you do this, you can go to any page on the site and add question
mark_switch_user equals, and then the email address of some user you want to switch
to. So we'll say space bar one at example, dot com. Now when we do this, we get an
access tonight and that is because of course we don't want to allow every single user
in the system to use this little trick. That would be a huge problem. Internally.
Symfony requires you to have a special role in order to be in order to do this. If
you go back to secure .yaml give role admin one more roll roll allowed to switch. Any
user that has this role is allowed to do this magic. So Watch. I still have
the_switched_user = in the url when I refresh, that's gone, but now I'm switched to
that user. You can see it down here. Spacebar one at example. Dot Com. Of course I
get access. I on this page like go around and I'm actually logged in as them. I can
serve around the site and this is actually that, that user, by the way, the reason
that's a question mark

underscore swish_user equals. And then the email address is because of our user
provider. Remember, this is the code inside the Symfony that helps reload the user
from the session, but this is also called when you would buy these switch user
functionality and if you're using the doctrine user provider like we are, then this
property email is actually what determines which field to look up on. Now, one of the
small issues with switch users that it's not obvious when you were switching user,
sometimes you can switch and you can forget that you switched and you can start doing
weird things in the site. So first the way that you can exit from it is the
same_switch_user with a special_exit that will pop us back to our normal user. So I'm
gonna switch back again to space for1@example.com and one of the issues is it's not
obvious when you switched so it's easy to forget that you switched. So was a cool way
to fix this. Go into your base, that age, that tweak and go all the way up to the
top. Where can see the body tag. When you are switched over to a user Symfony gives
you a special role called role previous Admin, they give that to you so that you can
determine whether or not you are currently switched. So we can say if his car is
granted role, previous Admin,

then I'm going to add a little block up here. It says you are currently switched to
this user will even add a link to switch away from this. So we'll path is the path
function will lead to Atlanta or homepage for. Remember, we need to have
that_switch_user equals_exit. So we can actually pass this as the second argument
here. Underscore, switch underscore, user equals_exit. And then inside of here I'll
say exit impersonation.

Alright, so let's try that move over and refresh and yes you can see it right here.
And if you look at the url there, question mark underscore, switch underscore, user
equals_exit. Now, one thing that might confuse you about that is if you go down to
your templates article homepage, that h way, and you go down to our article lists,
you might remember that the second argument of the function is normally used to fill
in the wild cards for a route. So I'm going to hold command or control and click
article show. You can see this route has a slug wildcard, so when we linked to it, we
pass it. The slug wildcard is the second argument and that's normal. That's the
normal reason that that's the neural of purpose of the second argument to path.
However, you can also, if you want to pass other things too, the second argument, and
if they are not, if there is no wild card, if this, if the key here doesn't match a
wildcard in the route, then it just becomes a query parameter on the end of the url.
So that's why that works. So we can click x and presentation and we're back to normal
in awesome feature.