# Web Debug Toolbar & the Profiler!

Make sure you've committed *all* of your changes - I already did. Because we're
about to install something *super* fun! Like, floating around space fun! Run:

```terminal
composer require profiler --dev
```

The profiler - also called the "web debug toolbar" is probably the most *awesome*
thing in Symfony. This installs a few packages and... one recipe! Run:

```terminal
git status
```

Ok cool! It added a couple of configuration files and even some routes in the `dev`
environment only that help the profiler work. So... what the heck *is* the profiler?
Go back to your browser, make sure you're on the article show page and refresh!
Voilà!

## Hello Web Debug Toolbar!

See that slick black bar at the bottom of the page! That's the web debug toolbar!
It's now automatically injected at the bottom of any valid HTML page during development.
Yep, this JavaScript code makes an AJAX call that loads it.

Oh, and it's *packed* with info, like which route was matched, what controller was
executed, execution time, cache details and even information about templates.

And as we install more libraries, we're going to get even *more* icons! But the
*really* awesome thing is that you can click any of these icons to go into... the
*profiler*.

## Hello Profiler: The Toolbar's Powerful Sidekick

OoooOoo. This takes us to a totally different page. The profiler is like the web
debug toolbar with a fusion reactor taped onto it. The Twig tab shows exactly which
templates were rendered. We can also get detailed info about caching, routing and
events, which we'll talk about in a future tutorial. Oh, and my *personal* favorite:
Performance! This shows you how long each part of the request took, including the
controller. In another tutorial, we'll use this to dig into *exactly* how Symfony
works under the hood.

When you're ready to go back to the original page, you can click the link at the
top.

## Magic with The dump() Function

But wait, there's more! The profiler also installed Symfony's `var-dumper` component.
Find `ArticleController` and go to `showAction()`. Normally, to debug, I'll use
`var_dump()` to print some data. But, no more! Instead, use `dump()`: dump the
`$slug` and also the controller object itself:

[[[ code('b964f4a635') ]]]

Ok, refresh! Beautiful, colored output. *And*, you can expand objects to dig deeper
into them.

***TIP
To expand all the nested nodes just press `Ctrl` and click the arrow.
***

## Using dump() in Twig

The `dump()` function is even *more* useful in Twig. Inside the `body` block, add
`{{ dump() }}`:

[[[ code('670987dfd6') ]]]

***TIP
If you don't have Xdebug installed, this might fail with a memory issue. But don't
worry! In the next chapter, we'll install a tool to make this even better.
***

In Twig, you're allowed to use `dump()` with *no* arguments. And that's *especially*
useful. Why? Because it dumps an associative array of *all* of the variables you have
access to. We already knew we had `title` and `comments` variables. But apparently,
we *also* have an `app` variable! Actually, *every* template gets this `app` variable
automatically. Good to know!

But! Symfony has even *more* debugging tools! Let's get them and learn about "packs"
next!
