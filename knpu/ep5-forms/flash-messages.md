# Success (Flash) Messages

Our form submits and saves! But... it's not all *that* obvious that it works...
because we redirect to the homepage... and there's not even a success message to
tell us it worked! We can do better!

In `ArticleAdminController`, give the list endpoint route a `name="admin_article_list"`.
After a successful submit, we can redirect there. That makes more sense.

[[[ code('498721350e') ]]]

## Adding a Flash Message

With that done, I *next* want to add a "success" message. Like, after I submit, there's
a giant, happy-looking green bar on top that says "Article created! You're a
modern-day Shakespeare!".

And... great news! Symfony has a feature that's *made* for this. It's called a
*flash* message. Oooooo. After a successful form submit, say `$this->addFlash()`.
Pass this the key `success` - we'll talk about that in a moment - and then an inspirational
message! 

> Article Created! Knowledge is power

[[[ code('4d49451736') ]]]

That's *all* we need in the controller. The `addFlash()` method is a shortcut to
set a message in the *session*. But, flash messages are special: they only live
in the session until they are *read* for the first time. As soon as we read a flash
message, poof! In a... *flash*, it disappears. It's the *perfect* place to store
temporary messages.

## Rendering the Flash Message

Oh, and the `success` key? I just made that up. That's sort of a "category" or "type",
and we'll use it to *read* the message and render it. And... *where* should
we read and render the message? The *best* place is in your `base.html.twig`
*layout*. Why? Because no matter *what* page you redirect to after a form submit,
your flash message will then be rendered.

Scroll down a little bit and find the block `body`. Right *before* this - so that
it's not overridden by our child templates, add `{% for message in app.flashes() %}`
and pass this our type: `success`. Remember: Symfony adds *one* global variable
to Twig called `app`, which comes in handy here.

Inside the `for`, add a div with `class="alert alert-success"` and, inside, print
`message`.

[[[ code('59907b99a8') ]]]

Done! Oh, but, why do we need a `for` loop here to read the message? Well, it's
not *too* common, but you can technically put as *many* messages onto your `success`
flash type as you want. So, in theory, there could be *5* `success` messages that
we need to read and print... but you'll usually have just one.

Anyways, let's try this crazy thang! Move back so we can create another important
article:

> Ursa Minor: Major Construction Planned

Hit enter and... hello nice message! I don't like that weird margin issue - but
we'll fix that in a minute. When you refresh, yep! The message disappears in a
flash... because it was *removed* from the session when we read it the first time.

## Peeking at the Flash Messages

Ok, let's fix that ugly margin issue... it's actually interesting. Inspect element
on the page and find the `navbar`. Ah, *it* has some bottom margin thanks to this
`mb-5` class. Hmm. To make this look right, we *don't* want to render that `mb-5`
class when there is a flash message. How can we do that?

Back in `base.html.twig`, scroll up a bit to find the `navbar`. Ok: we could *count*
the number of `success` flash messages, and if there are *more* than 0, do *not*
print the `mb-5` class. That's *pretty* simple, except for one huge problem! If
we read the flash messages here to count them, that would also *remove* them!
Our loop below would *never* do *anything*!

How can we work around that? By *peeking* at the flash messages. Copy the class.
Then, say `app.session.flashbag.peek('success')`. Pipe that to the `length` filter
and if this is greater than zero, print nothing. Otherwise, print the `mb-5` class.

[[[ code('b8f32ed38b') ]]]

This... deserves some explanation. First, the global `app` variable is actually an
*object* called, conveniently, `AppVariable`! Press Shift+Shift and search for this
so we can see *exactly* what it looks like.

Before, we used the `getFlashes()` method, which handles all the details of working
with the Session object. But, if we need to "peek", we need to work with the
Session directly via the `getSession()` shortcut. It turns out, the "flash messages"
are stored on a sub-object called the "flash bag". This new longer code fetches
the Session, gets that "FlashBag" and calls `peek()` on it.

Ok, let's see if that fixed things! Move back over and click to author *another*
amazing article:

> Mars: God of War? Or Misunderstood?

Hit enter to submit and... got it! Flash message *and* no extra margin.

Next, let's learn how we can do... less work! By bossing around the form system
and forcing it to create and populate our `Article` object so we don't have to.
