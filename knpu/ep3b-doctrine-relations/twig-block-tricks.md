# Twig Block Tricks

This tutorial is *all* about Doctrine relations... plus a few other Easter eggs
along the way. And one *big* topic we need to talk about is how to create queries
that *join* across these relationships. To do that properly, we're going to build
a comment admin section. Well, for now, we're just going to *start* building it.

Since this will be a new section on the site, let's create a new controller class!
And because I'm feeling *especially* lazy, find your terminal and run:

```terminal
php bin/console make:controller
```

Call it `CommentAdminController`. This creates a new class *and* one bonus
template file. Go check it out!

[[[ code('0aee7254a9') ]]]

Ok, nice start! Hmm, but let's change that URL to `/admin/comment`:

[[[ code('f6b612cf01') ]]]

Let's see what we have so far. Open a new browser tab and go to
`http://localhost:8000/admin/comment`. Awesome! The template even tells us where
the source code lives!

## Building the Comment Admin Template

Let's open that template and get to work!

[[[ code('93525be1b4') ]]]

This already overrides the `title` block, which is cool! Change it to say
"Manage Comments". Then, delete all the body code:

[[[ code('8c0756ca17') ]]]

To make the page look nice, open `show.html.twig`: we need to steal some markup
from this. Copy the first 6 divs. Back in `index.html.twig`, paste, close each
of those 6 divs and... back in the middle, add `Manage Comments`:

[[[ code('69ff9b0dcf') ]]]

Try that again in your browser: refresh. Ok! Those 6 divs give us this white
box that you also see on the article show page.

## Creating a Sub-Layout

Hmm. If you think about it, it's probably going to be *really* common for us to
want a page where we have some nice margin and a white box. The homepage doesn't
need this, but, I bet a lot of internal pages *will*.

So, needing to duplicate these six divs on *each* page is pretty *lame*. Fortunately,
Twig comes to the rescue! Go Twig! We can isolate this markup into a *new* base
layout.

In the `templates/` directory, create a new file: `content_base.html.twig`. What's
*cool* is that, we can extend the *normal* `base.html.twig` and then just add the
*extra* markup we need:

[[[ code('4d2d65dc38') ]]]

To do that, override `block body` just like we would in a normal template. Then,
steal the first four divs: these are the divs that *really* give us the structure.
Paste them here, and type a ton of closing div tags:

[[[ code('f003bc9c5b') ]]]

Next, and here's the key, in the middle, which is where we want the *content* to
go, create a *new* block called `content_body` and `{% endblock %}`:

[[[ code('fbfebbb080') ]]]

I just invented that name.

And, that's it! Let's go use it! In `index.html.twig`, change the extends to
`content_base.html.twig`:

[[[ code('c5ca9b835b') ]]]

Now, we do *not* want to override the block `body`. Nope, we want to override
`content_body`. Thanks to this, we should get the normal base layout *plus*
the extra markup from `content_base.html.twig`.

Remove the 4 divs, their closing tags, then clean things up a bit:

[[[ code('dda2ebba24') ]]]

Ok, try it! Go back to the admin tab and refresh. Yay! A nice layout with *no* work.
Repeat this in `show.html.twig`: extend `content_base.html.twig`, change the block
to `content_body`, and remove the 4 divs on top, the 4 closing tags on the bottom
and... un-indent a few times so this looks decent:

[[[ code('f706d1baae') ]]]

And unless we forgot something... nope! It still looks perfect! We now have a
*super* easy way to create new pages.

## Adding a Custom Class to One Template

Except... there's *one* small design change I want to make... but I want to make
it to the comment admin section *only*. Our project already has a `public/css/styles.css`
file. On your browser, "Inspect Element" on the white box. *One* of the CSS classes
in `styles.css` is `show-article-container-border-green`.

If you add this, you get a nice green border on top! And according to our hard-working
and very particular design team, they want this on the "Manage Comments" page, but
they do *not* want it on the article page. Apparently some of our alien readers
associate the color green with fictional, untrustworthy content. Well, we can't
have that!

But, dang! This ruins everything! The class needs to live on the `show-article-container`
div... but *that* lives inside `content_base.html.twig`. How can we change this
for *only* one of the children templates?

The answer is... drumroll... blocks! Blocks are *almost* always the answer when
you need to do cool things with Twig inheritance.

In `content_base.html.twig`, surround all of the classes with a new block: call
it `content_class`. After the classes, use `endblock`:

[[[ code('908cb8ac51') ]]]

This defines a new block that has *default content*. If nobody overrides the block,
it will have all of these classes. But, in the comments template, we *can* override
this: `{% block content_class %}`. But, we don't *really* want to fully replace it:
we want to *add* to the block's content. No problem: use `{{ parent() }}` to print
the existing classes, then `show-article-container-border-green` with `{% endblock %}`:

[[[ code('dbd66ddba0') ]]]

I love it! To make sure I'm not lying: find your browser and refresh the manage
comments page. Looks great! But the article show page... nope! No green border:
our alien readers will continue to trust us.

Next, let's finish our comment admin page by listing them in a table. And, we'll
install a cool library called "Twig Extension".
