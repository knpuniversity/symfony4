# Fetch the User Object

Once you have your authentication system step, pff, life is easy! On a day-to-day
basis, you'll spend most of your time in a controller where... well, there's really
only *two* things you can do related to security. One, deny access, like, based on
a role:

[[[ code('a887d9c486') ]]]

Or two, figure out *who* is logged in.

That's *exactly* what we need to do in `AccountController` so that we can start
printing out details about the user's account. So... how *can* we find out who is
logged in? With `$this->getUser()`:

[[[ code('49c948eca7') ]]]

## Using the User Object

Go back to your browser and head to `/account`. Nice! This gives us the `User`
entity object! That's *awesome* because we can do all kinds of cool stuff with it.
For example, let's see if we can log the email address of who is logged in.

Add a `LoggerInterface $logger` argument:

[[[ code('17932a44f2') ]]]

Then say `$logger->debug()`:

> Checking account page for

And then `$this->getUser()`. Because we know this is *our* `User` entity, we know
that we can call, `getEmail()` on it. Do that: `->getEmail()`:

[[[ code('c314b4e053') ]]]

Cool! Move over and refresh. No errors. Click anywhere down on the web debug
toolbar to get into the profiler. Go to the logs tab, click "Debug" and... down a
bit, there it is!

> Checking account page for `spacebar5@example.com`.

## Base Controller: Auto-complete `$this->getUser()`

But, hmm, something is bothering me: I do *not* get any auto-complete on this
`getEmail()` method. Why not? Hold Command or Control and click the `getUser()`
method. Ah: it's simple: Symfony doesn't know what our `User` class is. So, its
PhpDoc can't really tell PhpStorm what this method will return.

To get around this, I like to create my own `BaseController` class. In the
`Controller/` directory, create a new PHP class called `BaseController`. I'll make
it `abstract` because this is not going to be a real controller - just a helpful
base class. Make it extend the normal `AbstractController` that we've been using
in our existing controllers:

[[[ code('bd1a09fcb3') ]]]

***TIP
A simpler solution (and one that avoids a deprecation warning) is to advertise to your IDE that getUser() returns a User (or null) with some PHPDoc:
```
/**
 * @method User|null getUser()
 */
class BaseController extends AbstractController
{
}
```
***

Then, I'll go to the "Code"->"Generate" menu - or `Command`+`N` on a Mac, click "Override
Methods" and override  `getUser()`. We're not *actually* going to override how this
method works. Just return `parent::getUser()`. But, add a return type `User` - *our*
`User` class:

[[[ code('0532162c5e') ]]]

From now on, instead of extending `AbstractController`, we should extend `BaseController`:

[[[ code('00f5eed631') ]]]

And *this* will give us the proper auto-completion on `getUser()`:

[[[ code('8d0a70889a') ]]]

I also like to use my `BaseController` to add other shortcut methods specific
to my app. If there's something that you do frequently, but it doesn't make sense
to move that logic into a service, just add a new `protected function`.

I won't go and update my other controllers to extend `BaseController` right this
second - I'll do that little-by-little when I need to.

## Fetching the User in Twig

Ok: we *now* know how to fetch the `User` object in a controller. So, how can
we fetch it inside a template? Find the `templates/` directory and open our
`account/index.html.twig`. The answer is... `app.user`. That's it! We can call
`app.user.firstName`:

[[[ code('8a34035142') ]]]

Try that out. Go back to `/account` and... perfect!

Symfony gives you exactly *one* global variable in Twig: `app`. And it just has
a few helpful things on it, like `app.user` and `app.session`. And because
`app.user` returns *our* `User` object, we can call `firstName` on it. Twig will
call `getFirstName()` on `User`.

## Making the Account Page Pretty

Oh, and, oof. This page is *super* ugly. Clear out the `h1`. I'm going to paste in
some HTML markup I prepared: you can copy this markup from the code block on this
page:

[[[ code('3641c174b4') ]]]

If you refresh right now... oof. It still looks pretty terrible. Oh, hello robot!
Anyways, the page looks awful because this markup requires another CSS file.
If you downloaded the course code, you should have a `tutorial/` directory. We
already copied this `login.css` file earlier. Now, copy `account.css`, find your
`public/` directory, open `css/` and... paste! To include this stylesheet on this
page, add `block stylesheets` and `endblock`:

[[[ code('da76f926dc') ]]]

Inside, call `parent()` so that we *add* to the existing stylesheets, instead of
replacing them. Add `link` and point to `css/account.css`:

[[[ code('a897791823') ]]]

PhpStorm auto-completes the `asset()` function for me.

*Now* refresh again. So much better! All of this markup is 100% hardcoded. But
I added friendly `?` marks where we need to print some dynamic stuff. Let's do it!
For the Avatar, we're using this cool [RoboHash](https://robohash.org/) site where
you give it an email, and it gives you a robot avatar. I love the Internet!

Replace this with `app.user.email`:

[[[ code('5472722594') ]]]

Then, down by "Welcome back", replace that with `app.user.firstName`:

[[[ code('6b568e4540') ]]]

Cool! Let's see how it looks like now.

Hey! A brand new robot avatar *and* we see the first name of the dummy user. We
*are* still missing this twitter handle... because... our `User` class doesn't have
that property yet:

[[[ code('3266fb8529') ]]]

Let's add that next. Add a cool shortcut method to our `User` class *and* talk about
how we can fetch the User object from the one place we haven't talked about yet - services.
