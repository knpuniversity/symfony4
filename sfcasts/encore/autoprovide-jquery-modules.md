# Autoprovide Jquery Modules

Coming soon...

There's still some work to do on this new dot. Html twig. We bringing some
autocomplete stuff. We also link to one of our own,

yeah,

public jazz Algolia audit files, but which is actually our last JavaScript by on the
pump directory. Yay. Which is Dr Reddy, very traditional and does some autocomplete
stuff with this. Actually does it add some auto completion to this box, which is
totally not working right now because we've broken everything, so let's fix that.
First thing is in some of our new debt east amount twig, we have a CDM link to an
auto complete library. I'm going to install that yarn, add auto, complete that js
Dash Dash Dev.

Next, just like before, I'm going to take this Algolia audit, complete that jazz, and
I'm going to move it into my js directory, but I'm not going to make this a new entry
file. I could do that, but really we already have a file and entry file being brought
in on this page. It's Adam in article form. So really what admin article form should
probably do is just use the code inside Algolia autocomplete or should actually call
that code. So I'm gonna Move Algolia autocomplete dot js into the components
directory, which is kind of meant to be for reusable little components. And this
isn't really written like a component yet because these files and here's your
probably return a value export value, not just run some random code, but we'll work
on that in a second. I'm also going to take the Algolia autocomplete, that CSS file
and I'm going to move that all the way up here into my CSS directory and just because
we can, I'm going to rename that to scss.

Okay,

now that we have these two files over here under assets directory from Admin Article
Form Dot Js, we're going to import those so I can say import that /components
/Algolia autocomplete and import.dot/csi/algolia out of pre scss. Getting noticed.
I'm not doing import from on my job stir file because it doesn't return anything yet,
so I'm just importing it so it executes. Um, we're going to make that a little bit
nicer and a second. Now in new dot. Html twig. The great thing is, is we don't need
to import this CSS file anymore, uh, or any of these script files. This is really how
we want our templates to look. A single a call to encore intro script tags and a
single call to encore entry link tags. All right, so you refresh right now, not
surprisingly, it's still not going to work in the air is our classic dollar sign is
not the find. Come from Algolia autocomplete that js.

Okay,

so let's get to work inside of that file. Of course we're referencing dollar signs,
so that means that we need to import dollar sign from j query. We're also using the
auto complete library in here, so I'm going to say important. Oh, complete from a
complete dot. Js. Oh wait, that's not quite right because if you look, your honor,
that Jess Library is just a standalone library and you can, you can export it, you
can impart a value from it and use it. But at least the way that we were using auto
complete, we were using it as a jquery plugin.

Okay.

So we're, we're, and this is a common thing with libraries, they'll kind of have a
main way for you use the library. Then we'll have a jquery plugin way. So we could
refactor our code down here to use the actual kind of official way of doing it. But
we'll see if we can actually get this to work as a jquery plugin. So what I'm gonna
do is I'm actually gonna hold command or control, click into autocomplete that js.

Okay.

And then on a double click over here so we can get right down to it. So you can see
it normally includes index dot. JS is its main file right at the root of the project.
But if you look in dissed, Hey autocomplete that j query dot js. That's actually what
we were including via script tag before, so instead of in importing audit plate from,
I'm just going to say import autocomplete dot js /dist /autocomplete dot jquery.
Remember we don't import from with Jquery plugins because they actually modify the
jquery object instead of returning a value. All right, so let's try this move over.
Refresh and o j query is not defined. Notice it doesn't say dollar sign is not
defined. It says j query is not defined and it's coming from inside of autocomplete.
That jquery query, that jazz is coming from inside of that third party module online
to 41 so this is a tricky thing with jquery plugins. This is actually the second
jquery plugin that we've worked with. The first one was a bootstrap any for remember
when did with bootstrap, we didn't have any problems. If you look inside of our APP
dot js file,

we imported bootstrap and it just worked. Now bootstrap actually modifies jquery, but
bootstrap is a well written jquery plugin, which means inside that booth bootstrap.
If he looked at it, it actually doesn't import on jquery and then modifies it. But
this Algolia autocomplete dot js plugin is not Weldon written. What it does is it
simply starts referencing jquery as if it were a global variable instead of trying to
import it. But since we're not using global variables anymore, it doesn't work. So
I'm a jquery plugins are kind of a special monster. They've been for the most part of
the system, has been around for so long. It doesn't always play well with kind of the
new way of doing things, which Jake named Jerry Koo. Jquery plugins in general are
getting less and less popular, but so the basically the module, the filing we're
trying to import is written incorrectly. Fortunately Webpack amazingly has a way
around this and went back that can fit that js, it doesn't matter where, but we
already have an example down here. There's a spot called auto provide jquery
uncomment that go and restart encore and when it finishes moved back over and
refresh,

took it out, no errors. And if I start typing in this audit complete box, it works.
So honored. Provide. Jquery is something that basically fixes old code like this. And
I don't use it until I have to, when it actually does is every time that it finds a
Jake where you, or a dollar sign variable anywhere in any of the code that we use,
including our own code and that variable is um, an initialized. It replaces that with
require jquery. So it fixes this problem by actually rewriting the broken code.

Okay,

so not works, but while we're here, I want to make one small improvement. And that's
this. If you look inside the admin article, Form Dot Js, we include both the, this,
our JavaScript file here and the CSS file for Algolia autocomplete. But if you think
about it, this CSS valley here, this is meant to support the Algolia autocomplete. So
really that CSS file, that Sass file is really a dependency of Algolia autocomplete
that js here's what I mean. I want you to take out the important and move it into
Algolia auto play dot js and make sure you update the path that is just a little bit
nicer. This file is now defined defining the CSS that it needs in order for it to be
used. Wherever the heck gets used from an admin article form that jazz, all we need
to do is just require import that one JavaScript file and that one JavaScript file
takes care of important in the CSS that it needs. The result is exactly the same. It
just is a little bit of a cleaner way of doing it.

Okay.

All right. The last thing that I think we can clean up is um, we're so used to
writing code like this with document that ready and just having all the code do
stuff. We really need to start thinking about reusable components. So instead of
Algolia autocomplete doing stuff, let's actually have an export of value, like a
function that can initialize all this functionality. So check it out instead of
document that ready? I'm going to say export default function, and I'm going to have
this, I'm going to require three arguments. The elements, jquery elements that we
want this behavior attached to the data key, which is going to be used down here as a
way of a defining where you get the data from on the Ajax call. Um, this is something
we built an earlier tutorial, so I'm not going to go into all the details and the
display key, which is another key down near the bottom, which says which field on the
JSON to actually render.

So what I'm basically done is I've taken all this cohere and I've taken out the
specific parts and replace them with generic variables. So now I'm going say dollar
sign elements that each for the data key, we can put a little thing here that says if
data Qi and data = data lesker backup data key. And down here we'll just call, call
back on data, not on here. Oh, plus dislike. He sat to dislike it. The point is this
file doesn't do anything anymore. It just exports a re usable function in Edmon
Article Form Dot js. Now we're going to import auto complete from that /component
/Algolia auto complete.

Yeah. Okay. Yeah.

Then down here I'll say const auto complete = m to find that same j query element as
before. JS User auto complete.

Yeah,

so this is the exact same um, selector we were using before and js user autocomplete.

Wow.

And then if not autocomplete that is disabled. Then we're going to call that function
auto complete referencing the very, we brought in here Dawson auto complete comma
users. That was the data key we had before an email that was the display key. It's
we're calling that function, which is really useful. By the way, the reason I'm doing
this colon disabled is the way we originally made these forums is this author field
is actually disabled on the edit end point, so I don't want to actually add the
functionality and less it doesn't have disabled. So for your question now, yeah, I
think that worked out of the planes nicely and just to make sure I didn't mess up my
edit page, I'll go back to /admin /article and at one of these and yes it looks good
so you can see it disabled here so it doesn't add that functionality. This is much
nicer.