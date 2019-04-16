# Sass

Coming soon...

What about SAS? What if want to use SAS, uh, or less or stylish instead of just CSS?
Um, normally that requires a little bit of setup because you actually need to now
compile your sass into CSS. But with Encore, that's basically you get that for free.
So let's rename our `app.css` to `app.scss`. Of course, as soon as soon as we do
that, the build will fail because we need to update the `import` inside of our

mmm.

Instead of our JavaScript file. But the build is still fail. And if you go check out
the air, it's actually pretty awesome. It basically says, Hey, it looks like you're
trying to load a sass file. Do you want to use that? You might need to enable it in
Encore and then install a few libraries. This is the philosophy of Webpack Encore. We
want to give you a really solid core, uh, but then everything after that is optional
but super easy to install. So I'm gonna copy this and look at this `.enableSassLoader()`
If you go back to a `wetback.config.js`. This is actually
already in here, online, 50 to about there. So `.enablesSassLoader()`. And then the
other thing we need is yarn. Add this stuff. So it'll tell us exactly what we need to
install.

So I'll go to my open tab and I'll hit that 

```terminal-silnet
yarn add sass-loader@^7.0.1 node-sass --dev
```

and that might take a minute or two to
install node-sass is a c library so it actually might need to compile on your system
and there we go. Excellent. All done. Now the only time that you should have to,
normally when you were on the watch script, you don't need to restart Encore. The
only exception to that is when he made a change to your `webpack.config.js`
file, which we just did, this is just the way Webpack works. It can't reread its
configuration until you restart it. So that `control + C` and we'll run it

```terminal
yarn watch
``` 

again.

And this time, hello sass support in 60 seconds. How awesome was that? So this is
next part is optional, but I'm actually going to organize my code here and a little
bit and instead of having one big file, so let's create a new directory here called
`layout/`. And for this top stuff here, I'm going to create a an `_header.scss` and
little by little I'm just going to refactor all this code into different parts of my
into different files. So there I'll grab that stuff put into header. Next we have
this advertisement, so let's create a new directory called `components/`. And inside
there I'll do `_ad.scss` move the advertisement stuff there. Don't need the header
anymore.

Okay.

Next up is some articles, stuff's, I'll put that into `components/` as well, `_articles.scss`
and there and we'll get the main article stuff. Excellent pace. That in next
is profile, same thing on her `_profile.scss`.

Yeah,

copy the next little bit. Put that in there. And for just for simplicity, I have a
great article on the article show page. I'm actually just going to copy those into my
articles. This is just for organization.

Here we go.

But that's at the bottom of my `_articles.scss`. And finally we have a footer area.
So inside my `layout/` I'll create one more file call `_footer.scss` and I will put my
footer code inside of there.

And

at the bottom here I have one more little components for our sort of a functionality.
So I'm going to delete that and create one last component called `_sortable.scss`
and paste that in there. All right, so a little bit of rework there but
now it's really nice cause we can say `@import './layout/header'` and 
`@import './layout/footer'`. I noticed we don't include the `_` here or the `.scss`. That's
a SAS thing. It knows where to find those. And then I'll just do a couple more here
for the `./components`. So components and I'll just go out for medical medically `/ad`

pace that a few times. So we can import `articles`, `profile` and `sortable` of them through.
And we do that. Notice we get a module, not found air. Interesting. Let's go check
that out. And if you scroll up here, ah, can I resolve `./images/space-nav.jpeg`.
We've seen that error before. So this ended up in our head or his file and Yup, you
can see right up here it's unhappy because now we're extra level deep. So our image
is not resolving. Supposed to add `../` and that works. So again, you can't
accidentally have a broken build because it's watching your back. So all have a
refresh. We should have the same thing as before and we do. So let's flex this a
little bit. I'm gonna Create a new directory here, call `helper/` and a new file in
there called `_variables.scss` as and just to make sure this stuff is truly working instead
of my `_header.scss`. At the top I have our `background`  is this gray. So
variables, let's set a `$lightgray` variable, sat two pound, sign that value and then
done the headers. We can just say `$lightgray`, even getting auto completion on that.

And as soon as you do that, you can see it fails. Not surprisingly undefined variable
light gray. That's perfect because inside of our `app.scss` all the way at the top,
we need to add `@import` our `helper/variables`

and about a second later filled it's successful and everything looks good. So that
kind of proves that this SASS setup is actually working, but it's actually a little
bit cooler than this. Remember when we did till they bootstrap until they bootstrap
it new to load the exact `bootstrap.css` file. Well now that we're in a Sass file,
it was smart enough to actually import the `bootstrap.scss`. So I'm actually gonna
hold command and click into that. Again, if you look at the `package.json` File you
how to `style` attribute, but it also had a `sass` attribute Encore smart enough that now
that we're inside of a Sass file, it first looks for these Sass file and then it
falls back and looks for the `style` attribute. So not all libraries have this, but
some of them do. So it's actually loading `bootstrap.scss`.

If you look at the `font-awesome/` directory and look at its `package.json`, it
actually doesn't have a `sass` key. So it's still loading the `font-awesome.css`
file. If you didn't want to load the Sass, you would actually need to go and point at
it directly. So it's something that works sometimes, but not all the time to prove
that bootstraps Sass file as being um, used. Now I'm actually gonna go back and right
click on my search thing. Notice this as a `btn-info` and it's color. If you
scroll down here is set to this Hashtag right here. `#1782b8`.
Let's say that we want to actually change the inf all the info colors to be a little
bit darker inside the bootstrap, there's a variables file and one of the variables
that set is called `$info`. So now inside of our variables file we can
override that. I'll say `$info:` and I'll use the `darken()` function which comes from
bootstrap. I need to check on that piece, that value, and I'll say 10% all right.
Once that bill works over, watch closely refresh. Yes, it is a little bit darker. How
cool is that? So we're now able to control bootstrap as well.