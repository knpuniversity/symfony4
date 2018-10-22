# Form Submit

Coming soon...

Creating the form class in rendering was really easy, so let's talk about form
posting. Now notice we haven't said anything on our forum about where it should
submit to and when we rendered it, we just said form start by default and you'll see
this if you inspect the element, forms will render without any action attribute,
which means that they'll submit right back to themselves. That's the typical way of
handling the form. Flowing Symfony will have these same controller responsible for
both the rendering the form on gifts and submitting the form in handling the submit
on post and the way you do that always looks exactly the same. First, get the `$request`
object by type hunting. `Request` the requests class from `HttpFoundation`. Then you'll
say `$form->handleRequest()` and pass the `$request` followed by 
`if (form->isSubmitted() && $form->isValid())` inside, just `dd($form->getData()`. Okay, so this
requires a little bit of explanation

`$form->handleRequest()`. It makes it look like the submit data is being read on every
single request, but actually, but internally by default hand request only does its
work. If this is a `POST` request, so this is a `GET` request from `handleRequest()` does
nothing `isSubmitted()`, returns `false`, and so we just render the unsaid unsubmitted
`$form`, but when we `POST` `handleRequest()` will read the fields off of the `$request`
automatically in `isSubmitted()`, will `return true`. Now later we're going to talk about
foreign validation. If the `$form` is valid, which it will be right now, then we get
into this block. If the `$form` is not valid, then it will actually skip this block and
render the template, but this time the `$form` will have errors on it. So the only way
we can get into this if statement is if, if, if this is a `POST` request and the form
has passed validation, so let's try this, move over. Let's create a very create a
very important article named about the fact that mercury hot, new alien vacation
spot. Then I'll hit enter, submit to submit that, and

boom, there it is. It dumps out exactly what would you expect `title` in `content`,
nothing fancy anymore, but this was so far really easy to set up, so now we want to
do is want to create an article object using that data. I'm going to do this the hard
way, the long boring way first, which means we're going to get the `$data` by 
`$form->getData()`

`$article = new Article()`, and then we're just going to start setting the data on that
`$article->setTitle($data['title']);`, `$article->setContent($data['content'])`, 
and we also need the author to be set, so we'll say `$article->setAuthor()` and will have 
to be authored by the current `$this-getUser()` right now. There is a much fancier way to do this 
and just setting all this data manually and you'll see it in a few minutes. So just 
sit tight. Now let's save this. Save us. We need the `EntityManager`, which I already 
have because I've typed, printed the `EntityManagerInterface`, so we'll say 
`$em->persist($article)`. `$em->flush()`. Awesome. Then the last thing we always do in 
a forum summit is we redirect to another page. For now, let's just say 
`return this->redirectToRoute('app_homepage')`, so that will return to the home page all 
so we dry it, moved back over

out,

refresh to repost an cool. I think that that works. Scroll down here. Hmm. I don't
see my article, but that's because only published articles are shown on the homepage.
Well, we really need is a way to see all of the articles in the admin like a list
page, but right now we just have the new page and we have the work in progress edit
page, so let's add a new end point down here. `public function list()` and above it will
make a new `@Route('/admin/article')` was all to us to all of the articles will type
it into the `ArticleRepository $articleRepo`, and then we'll say `$articles = $articleRepo->findAll()` 
and I'll just render a template just like before `article_admin/list.html.twig`. 
We'll pass an `'articles' => $articles` variable just before as a
shortcut. I'll put my cursor on the template name, hit alt enter to create that twig
template

right next to the other one. The contents of this, this is pretty boring stuff, so
I'm actually going to cheat and paste in a template. You can get this code from the
code black on this page. We're sending the same content and we're just looping over
the `articles` and printing out some basic information. I also have a link on top to
our new admin article. Now in those sudden here it is highlighting this one field
`article.isPublished`, which I used to show a check mark or an x mark. We don't
have an is published method yet on our article. All we have is it gets published at
method, so was that a new `public function isPublished()` which will return a `bool` and
very simply more `return $this->publishedAt !== null`. If you wanted to be a
little fancier, you could also check to see if published that is published as a
future date. Maybe it's not published. That's up to you how you want it to your app.
Alright, so now let's go back manually go to /article `/admin/article` and awesome.
You can see our articles showed up on the bottom so it is working.

Alright, before we move onto a little bit more form stuff, I want to make this a
little, this, this setup a little bit fancier in `ArticleAdminController`. Let's give
our new listing in new `name="admin_article_list"` and then after we are successful and
submit, let's actually redirect here. That just makes a lot more sense. The other
thing I want to do is show a message, so after I have successfully created an
article, I'm going to see some something green on top of says that we've actually
done it unfortunately is a feature that makes that very easy and it's called a flash
message. After we've had a successful form submit, say `$this->addFlash()`, then
I'll say `'success'` and I'm going to put a message `'Article Created! Knowledge is power'`
and if the controller, that's it. What this does is this sets a message in the
Session, but this message is special. It will only live in this Session until it's
been red for the first time. As soon as we read this message from the Session, it
will be deleted. It's a perfect way to put temporary messages in the session so that
you can show them on the next page. This success key here, I just made that up. We're
going to read that are twig template.

Now, the best way, but the best place to rendered a flash messages is actually in
your `base.html.twig` layout because that means no matter what page you redirect you after a
form, you're always going to see the message,

so I'll scroll down a little bit, find my black body, and right before black body
we're going to put a little flag here that `{% for message in app.flashes() %}`, that's a
special shortcut you can use to read out flash messages of a certain category, so put
our word `'success'` there. Remember `app` is the only global variable that you have inside
of a Symfony. Then we'll say n for inside we're given a little `alert alert-success`,
and then we'll just print out that message. That's it. You know, since a for loop,
which might look a little bit weird at first, but technically you can put as many
messages into each category as you want and then it will print all of them at once,
but usually we only have one message. All right, let's try this. Move over. Let's
create a another important article

called

Ursa minor. Major construction planned, hit enter and nice message shows up. Message
shows up. Of course there's a little margin problem here I don't like, but if you
refresh the page, you see the message that goes away because as soon as we read that
message at flash message, it was removed from the session. Now to fix that styling
issue, we needed to do a little bit of work here.

Inspect element when I'm looking for is actually this Nav Bar, it has a little bottom
margin. They're called mb because of this `mb-5` class. Basically, I don't want to
render that `mb-5` class in situations where we have a flash message, so check this
out. If we scroll up a little bit, we can find that Nav bar with the mb five, so
basically we want to count his success flash messages and only rendered this part,
this class if there is at least one. The tricky thing is though we can't just read
the last matches is like we did last time because you remember it as soon as you read
a flash message, it's gone, so if we read it up on the Nav Bar, it would be gone by
the time we read it down there. Instead we can use a little trick called Peking.
Check this out. I'll copy that class. Then I'll say `app.session.flashbag.peak()`, 
and then pass the `'success'`. No, pipe that to length and say, if that's greater than zero, 
then we will print nothing else. We will print our `mb-5` class and let's take a little 
bit of explanation. Remember first that `app`, that's a variable called `app`. Actually, 
you can hit shift, shift type `AppVariable` if you want to see exactly what that has inside 
of it. Before we were using the `getFlashes()` shortcut method,

which is nice way to get the exact flash messages you need, but if one only the `peek()`
functionality, then you actually need to use the `getSession()` shortcut. It turns out
that the flashback is just a sub, a property on the session, which you could see a
second ago when we looked at the previous thing, so we just get the session and we
get the flashback and then we use a special method on it called `peek()`. If we wanted to
do the other way, the long way, we could have done the same thing app, that session,
that flashback dot get anyways, that should take care of it. Let's move back over
credit. Another important article, Mars, God of war or misunderstood, hit enter and
much better flash messages already. Super powerful feature.