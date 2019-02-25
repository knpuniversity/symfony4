# Thumbnailing

Coming soon...

All right, let's do some thumbnailing. The issue right now is that if you go to the
home page, obviously we have these images restricted. They're restricted to 100
height by a hundred width. Um, but the problem is that I actually I'm, which is much
bigger than that. So that's just wasteful and we know that it's wasteful and that's
why typically you're going to want to thumbnail any image that the user uploads. So
Google for `liipimaginebundle`. I can find there a,

yeah,

get up page and actually they have a bunch of documentation on this page, but most of
the documentation is actually over on Symfony.com so if we just click download the
bundle for example, that will take us to Symfony.com which is where most of their
documentation is. So let's go to download the bundle copy that composer require line.

Yeah,

shoot over here.

```terminal
composer require liip/imagine-bundle
``` 
 
So the way or waiting, the
way that imagine on the works and we don't need to do any of the enabling bundle or
registering to routes because we're using Symfony flex. So that's done automatically.
So it is fun to works is you define things called `filter_sets`.

Okay.

Basically you create something that has a name like `my_thumb` and then you basically
tell it a bunch of different filters you want to apply to that. You can apply a
`thumbnail` filter, which is what we're going to do. But there were a ton of other
things do you do, you can do kind of backgrounds, border colors, um, many, many
different transformations that's um, is supported by this bundle. So we're just going
to show how you thumbnail stuff, but there is so much more that you can do with this
bundle.

Okay,

so move back over. Perfect. It's done. And as you can see down here, it says that we
do need to do a couple things of, of configuration `liip_imagine.yaml`,
which is where we are going to go first. So `config/packages/liip_imagine.yaml`
all, and this was just added. Let's uncomment out leave. Imagine I'm gonna keep the
driver at `gd` That's the default. But you can also use `gmagic` or `imagic`. These
will require you to have um, some PHP extensions installed. And there are pros and
cons to each of them.

Okay.

But it won't make any difference in how you use the bundle. So we're going to
uncommon, I'll `filter_sets` and we're just going to start creating different filter
sets. I'm going to create one called `squared_thumbnail_small`. This is going to be a
filter that is going to reduce images down to this 100 by 100 size right here. So we
can actually use

okay

on coming off filters and then they have a good example of a thumb down down here.
I'm actually just going to copy that. I'll leave the documentation right here,

uncomment that out.

And then it would changed the size to 200 by, by 200. There's also this `mode: outbound`
`inbound` has to do with how it's thumbnailed. I'm not going to talk about that. You
guys can look at the many options that this thing can do, but we'll just start with
this. And I'm doing 200 by 200. Um, even though this is actually a 100 image, so that
there's a little extra quality, uh, for retina and that's it. So we've now defined a,

yeah,

fis, they filter set called `squared_thumbnail_small` and we can just apply that
wherever we want. So in this case we're going to go to `homepage.html.twig` and
the way you apply this as, as a filter, you say `|imagine_filter()`,

and then you pass it in the name. Oops, has the name of the filter set that you want.
So ours is `squared_thumbnail_small` And that's it. So
I don't watch very closely what happens here when we refresh to the image path. So
/uploads this article

image.

Sure. When you refresh the first time, notice it has the absolute, you are all here.
That's not important. But it goes to `/media/cache/resolve/squared_thumbnail_small/...`
 Blahblahblahblahblah. This is an actual Symfony route. You can see it if
you've got a 

```terminal
php bin/console debug:router
```

Yup. There it is right there.

So that means is that on the first request? This is actually hitting a Symfony
routing as Symfony controller. And that controller is actually looking at the source
image, applying the thumbnail right at that moment, and then returning the new image.
So that's actually a slow operation the first time you do it because it's actually
this, this is actually going to wait for that thumbnailing process to happen. The
cool thing is watch this closely from media cache resolve the second time you
refresh.

Yeah,

it's just the media cache. The resolve is gone. This is not a Symfony routes. If you
look in your public directory,

there is now a `media/` directory with a `cache/` with the `squared_thumbnail_small/`, `uploads/`,
`article_image/astronaut-...jpeg`. So on that first request, when it hits the
Symfony route and controller, the controller does all of the thumbnailing right then,
uh, and then return. But then it saves the image to this new path and then returns
it. So the first request has to wait a little bit longer, but it ultimately gets the
image every request after that, uh, the bundles smart enough to first look, see if
this file already exists. And if it does, it just renders the path to this file. And
so we get a really, really fast return. So it's not that important of a detail, but
um, I just want you to know that something different happens to the very first time
that you thumbnail image and enough of that it's just reading dynamic 80 from his
file. So if we were to delete this, it would uh, it would rerun it and then I sing is
thanks to the flex recipe. If you look at your `.gitignore`, file the a 
 `liip/imagine-bundle` already ignoring this path. So yeah, just kind of works. This bundle is
awesome. So let's click in here and let's create one more for this spot here. This
one is 250

with, so

yeah,

I'll actually create another filter set here, `squared_thumbnail_medium` 500 by 500

copy that name and this time we'll go on to `show.html.twig` and we'll
`|imagine_filter()`

and we'll use that one. I'm going to refresh

it works. See the first time it has the resolve in the URL. Second time, it just
points directly to that file that had just saved. Awesome. All right, so the last
thing we're going to do a while while we were having all this luck.

Okay.

Is Let's actually go and find it. This image. Let's go back to the admin section. Go
to edit. Um, it would be really helpful. We can't even tell if there is an image file
upload to here. It would be really helpful to actually put a thumbnail of the image
right inside of this. Uh, we're inside of the form.

Okay,

so let's go to our form template `templates/article_admin/_form.html.twig`. And
here's where a rendering the image file. Now we could do some sort of a Symfony form
theme or we actually modify how the `form_row()` renders and then kind of in make
automatically when you call `form_row()`, make it kind of render the image in some way.
Um, you can't do that, but I think it's honestly easier just to add some markup
around this. So I'm going to add a `<div class="row"></div>` and then `<div class="col-sm-9"><div>`,
 to set up a little bit of grid system and I'm going to move
my uh field into that. And on here

we'll do a `col-sm-3`

and will just render the image tag if there is one. So how can we get access to what
we need is access to the `Article` object because we know that if we have the `Article`
object we can get the image path from it and we can do all this stuff here. In fact,
I'm going to paste a copy of this because we'll use this in a second. So in this case
and the `_form.html.twig`, if you go back and look at the controller
`ArticleAdminController`,

yeah.

If you look at the edit action, which is right here, the new action, we're not
actually passing the `Article` object in, we're only passing in the form and sending
edit also renders this template and it is only passing the forms. So we don't
actually have the `Article` object inside of our form template, which is fine because
you might remember from our forms tutorial that we can get it via the form. So check
this out. We can say `{% if articleForm.vars.data %}`, that right there, The 
`.vars.data`, that will be the `Article` object. So we can say if it has an 
`imageFilename`.

Okay,

then we're going to say `<img src="">`

okay.

And I'm actually going to

okay,

paste that. Oh my bad.

Okay. Yeah,

she going to paste that. Oh actually that's not right. I need to do a `{{`
first. Now I'm going to pace that, uh, except instead of `article` here, we're going to
have `articleForm.vars.data`.

Okay.

We'll run it through our same small thumbnail here. I should do an alt image.

Awesome.

Also restrict the height to a hundred since that image will be 200 and that's it. So
if I want to go back and refresh, boom, there it is. And if we go to the new page
where there isn't one, oh, we actually get an air possible to access attribute image
file name on a knoll variable. So we need to be careful here because 
`articleForme.vars.data`, um, on a new form that might actually just be `null`.

So I kind of, an easy way to fix this. It's kind of not a great fix, but is to just
do pipe default and then it works just fine. If I go back to the edit one, that also
works, um, the `|default`, I could've said 
`{% if articleForm.vars.data is defined && articleForm.vars.data.imageFilename %}`, 
I could have done that. But when you do something in `|default`, it basically suppresses the air
and if any, any part of `articleForm.vars.data` didn't exist, it just
defaults to `null`. So this has the same effect. All right, next let's talk about
something else.