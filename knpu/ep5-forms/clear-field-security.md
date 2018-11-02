# Clear Field Security

Coming soon...

There's one small problem, we change this to the solar system. Okay, great. It loads
the planets. We can even change it over to interstellar space and it disappears. But
if we change it to choose a location, Huh? Nothing happened. And actually if you look
closely on the web, debug the Ajax part of the web, have our tow bar. We got a 500
error. This is one of the great features about the web debug toolbar. When you get a
500 air like this, you can, I'll hold, I'll hold to open this link in a new tab. It
takes me straight to the profiler for that, for that request. And specifically it
takes me to the exception screen so I can see exactly went wrong, what went wrong. So
apparently `ArticleFormType` line 125 undefined index blanket value. Ah, so if you
look in `ArticleFormType`, this is the method that we call to get the correct
choices. In this case, location is actually empty and so an empty string is not found
as about key. So it fails. So you can very simply change this. Add a `?? null`
and that will fix this. This says if the location he exists on this object, use it 
otherwise else use `null`.

All right, so go back and we'll change over to back to the solar system. Back to
choose the location and cool the field disappears and know what? 500 error this time.
All right, so there's one other subtle issue. I'm going to refresh the page here. So
right now we are. We have regal selected as the star and we're an ID 28, so I'm going
to find, find your terminal and run 

```terminal
php bin\console doctrine:query:sql 'SELECT * FROM article WHERE id = 28'
```

. Okay. Not surprisingly, we have the location star
and we have regal as these specific location name, so now let's change this back over
to interstellar space and hit update. Perfect. Except when you go over here and run
that query. Again, location is interstellar space, but this specific location name is
still regal. This might not actually be a problem for you, but technically it's wrong
because we changed the interstellar space. The specific location names should be set
back to know the reason that it wasn't changed is a little subtle when we, when we
change the option, when we changed the location to be interstellar space and we
submitted,

okay.

The end result of that is that when our form listeners were called, these 
`setupSpecificLocationNameField()` saw that there was no choices and so it removed the
specific location named from the form entirely and when you have, if you have a form
in one, a field doesn't exist in a form. The end result is that the underlying object
isn't changed. In other words, because there's no `specificLocationName` field,
nothing calls the form does not call it `setSpecificLocationName()`. It does nothing
leaves this specific location name alone. There are some ways we can fix this inside
of the form, but honestly I think the better solution here is to fix it on our
`Article` entity, so open the `Entity/Article.php` and look for it `setLocation()`. Basically
when we set the location, if the location is a blank string or a set of interests,
our space, no matter who sets it, even if it's set outside of a form, we should
probably set the specifically location name back to not. So I'll literally say if not
this error location, `if (!$location || $location = 'interstellar_space' )`. Then
`$this->setSpecificLocationName(null)`. By the way, you might want to use constants for
these special interstellar space keys instead of using strings everywhere. I'm being
a little bit lazy here. Alright, so we try that

first. I'm going to change this back to be a planet so we can see the whole system in
place

and then we'll change this back to interstellar space and update. Perfect spin back
over to our terminal. We run that query and now it's set to `null`. Awesome. All right.
There's one last little tiny bit of business and that's in our `ArticleAdminController`.
We created this new endpoint, but there's no security on it. It's
actually open entirely to the world because there's no answer. There's no, um,
security annotation on this specific article on this specific controller class. Now
that might be okay, this is just something that returns some html fields, but let's
be, let's add some security to it. Now the tricky thing about the security is that
we, it's new, it's used on the new page and also the edit page and on the new page we
require you to have the role as an article, but in the edit page we actually allow
you to get to this edit page if you are the author of this specific article. So
basically for this location select end point. You should have access to this if you
have `ROLE_ADMIN_ARTICLE`

or if you are the author of at least one article. So the proper way to solve this is
to create a new voter and do something like `@IsGranted()` and we would just make up a
new attribute called something like `ADMIN_ARTICLE_FORM` and inside the new voter we
would handle this attribute and we would do that if statement check to see if they
have that role and also check to see if they or check to see if they are an author of
at least one article. But we're only going to use this on this one end point. So I'm
just going to inline the logic here. Actually let's add in `@IsGranted()`, let's first
just simply make sure that they are logged in, but then inside the method, let's put
the real logic here. I'll say 
`if (!$this->isGranted('ROLE_ADMIN_ARTICLE') && $this->getUser()->getArtiles())`, 
hm. Actually see that. That is not auto completing for
me. Oh, I know why it's a hold this thought. Go Up to the top of his class and
extended instead of extending `AbstractController`, extended `BaseController`,

there we go. As a reminder `BaseController` is a controller that we created that just
extends `AbstractController` but it properly type instigate `getUser()` method with our `User`
class. So anyways, down on our method now I can say, okay, 
`$this->getUser()->getArticles()->isEmpty()`, so if we don't have real adamant article or if this user does not
have any articles that they are the author of, then we're going to 
`throw $this->createAccessDeniedException()`. Cool. And that should take care of it. So just
to make sure I didn't completely break things if I change this location to a star.

Yeah,

there we go. It loads just fine.

Yeah. No.

Now related to this are too small. There's one thing I want to point out in order to
be. I'm being lazy. I'm actually calling `$this->getUser()->getArticles()`. The
problem with that is that if this user is the author of 20 articles, then that's
actually going to query for and hydrate all 20 articles just to figure out that
they're empty. So what we really want to hear is just the count of them `isEmpty()`, is
actually just going to count them. So let's go up to the top of this. Look for that
`$article` is property. There it is, and on the end of it, do I `fetch="EXTRA_LAZY"`. It's
going to make sure we don't do too much work. Checking access on there is empty. Is
Smart enough just to do a count on it and that will do that quick count query. All
right then one last bit here.

Am I talking about is actually, it's unrelated to forms. It's in the 
`BaseController`. As I mentioned in a previous tutorial, we created this cool 
`BaseController` that extends abstract controller, gives us all the same shortcuts, 
but we over `getUser()` so that it tells our editor that it returns a `User` object, a very
helpful, simply gas user pointed out that the user method and the parent class is
marked as final, which is his way of telling us that we're not supposed to, um,
subclass this and override it. So it actually a better solution as the same effect as
to delete that. Instead we can use a little bit of special documentation on the
class, say `@method User getUser()` that does the exact same thing. Hints to our
idd that to `getUser()` method returns our `User` object which corresponds with this type
pant. So now down here at get user goals should still get these same get articles.
Yep. Still exist in the same way as before. Alright guys, that's everything we are
done. Symfony form system. It's complicated. It's hard but oh it's so powerful.

So go out there and create some forms. Use the art, the form system to accelerate
your work and create great forums. But remember if you get into super complex
situation or you have something that includes a lot of Ajax in some point, might be
easier to not use the Symfony form system and to build a more robust Java JavaScript
based front end. As always, this form system is a tool, but it doesn't mean you need
to use it everywhere. So let me know what you guys are building and if you have any
questions as always, ask us in the comments. All right guys, see you next time.