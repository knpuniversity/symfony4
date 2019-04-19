# Async Imports

Coming soon...

Head back to the `/admin` section. A don't have a performance issue here or something.
We can make much more. Awesome. So if you create a new article, we have a an author
feel here and it uses a bunch of autocomplete JavaScript. The thing is that if you go
back and edit an article, we don't have this working on the admin edit and the edit
page and that's on purpose. We purposely want the author field to be disabled. So
here's the problem with that. Go Open `admin_article_form.js` so we import this auto
complete um, JavaScript file here, which imp importance. I'm a third party audit
complete library. It also includes the important some CSS. So it's got, it's not a
tiny amount of code and if you think about it, since this file is included on both
the edit and the new form page, all of this code here is totally wasted on the edit
page cause we don't need any of that functionality.

The problem is that you can't conditionally do things. Can't put an if statement
around here and conditionally import that audibly because Webpack needs to know at
build time. Should I put the contents of this inside of the final built `admin_article_form`
file or not. Fortunately, there's an awesome feature called `async` imports or we
can work around this because this is really common situation. You might even have a
situation where you have, for example, some dialog box that pops up, but that
dialogue box is something that pops up only very, very rarely. So you don't want to
have a user download all of that extra JavaScript for that dialogue box until they
actually click the link. So we need to be able to lazyly load dependencies. Here's
how we do it. I'm gonna copy this file path here. Then delete the import import is
normally are all at the top of the file and yeah here

inside the if statement. This is when we know that we want to actually use that
library. So down here we can use `import()` like a function and pass it the path that we
want to complete. This is going to work very similar to an Ajax call. It's not going
to be instant. So we're going to chain a promise onto it. You want to say `.then()`
and here it's going to pass us whatever that library, um, export it as a value. So
we'll say `autocomplete` and then I'll do arrow function right here. And then inside we
can move that code there. So it's going to hit her hand port code, it's going to make
an Async, it's gonna make an Ajax call off that script tag. And then whenever it
finishes, we will actually call that function. So this is also a perfect time. Do you
could add like a loading animation, this word or something where they use or clicked
on something and then you load it. You could do and loading information there and the
loading animation inside.

Yeah.

All right. So check this out. Let's go refresh. And while of course it doesn't run
this, let's go to our `article/new` Page. Oh 

> autocomplete is not a function 

at an `article_form.js` ah, so this is a little bit of a Gotcha. When your module uses
the new syntax, the kind of export default, when you use code splitting, you actually
need to say `autocomplete.default` just to kind of a weird thing you need to know
about snap and go back and refresh.

Okay.

No errors and it works in, check this out. We can actually see, if you look at the
network call here, I'm going to go to the script tags look

okay.

It actually downloaded `1.js` and `0.js` Africans out of there. It
actually, this pertains the `autocomplete.jquery.js` and `1.js` contains the
CSS file and the JavaScript file. So it's actually still code splitting those
components and yeah, it actually includes the CSS file, which is, I know seems kind
of crazy, but

okay.

It's including the CSS that we need inside of here.

You can see some comments here about the CSS, but there's not actually anything
there. But if you go over to the CSS tab, actually asynchronous, they downloaded the
CSS file as well and applied that to the page. Uh, in fact, you can see it actually
hacked into the tent. You are header, so it just works. You can split the JavaScript,
you can slip the CSS. You can imagine how powerful this is with a single page
application where you can asynchronously load all of the components for the pages
when they're actually clicked. Instead of having one giant JavaScript file.