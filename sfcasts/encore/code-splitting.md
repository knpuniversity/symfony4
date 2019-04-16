# Code Splitting

Coming soon...

Okay,

this one of really cool thing happening right now, uh, after you view source in this
page and search for `app.js`, we actually have multiple JavaScript files being
output. Now

actually, let's look at this. Inside the inspector, it's a little bit more obvious
there. Look, `vendor` an `app.js`. This `vendors~app.js` thing. All
right, so let's go look at our `public/build/` directory. Yeah, in fact, there's an
`vendors~app.js`. This is awesome. So if you look in your Webpack got
fit. `webpack.config.js`, one of the features, one of the optional features
that we have preinstalled is called `splitEntryChunks()`. And here's how it works.
Basically we tell Webpack to read `app.js` and read all of the stuff inside of that
and create one `app.js` file. But internally it's actually going to determine that
it would be more, it would probably be more efficient if it's split `app.js` and
the two files, `app.js` and `vendors~app.js` and what `vendors~app.js`
has is as a bunch of a Webpack specific code in here, but this actually
contains the vendor libraries that were important like bootstrap and jQuery. So
here's what happens when

uh,

Webpack analyzes all the files in your `app.js` file and it decides if it wants to
split it into multiple JavaScript files and it uses a couple of pieces of logic to do
this. First, if `app.js` is getting really, really big, it might split it into,
might want to split it into multiple files so that your browser can make multiple
requests for second. It really likes to split vendor stuff into its own file. And the
reason for that is that sense of vendor stuff typically changes less often than our
application code by isolating vendors, `app.js` your browsers, your user's browser,
can cache this for longer and have to download it less often. The `app.js` file
that is updated more often is much smaller now, thanks to that. And the other thing
it does is it looks for reuse. Right now we only have one entry file, but in a little
bit we're actually gonna create multiple entry files that we can have page specific
CSS in page specific JavaScript. When we do that, Webpack tend to start analyzing
which files are shared between those entries and it's going to also isolate those
into its own files. This is something that's powered by these split.

Okay.

I these split chunks Webpack plugin plugin from Webpack and you can totally a
configure all of this stuff,

uh, to do different things. It's got great to false but you can configure it because
remember, ultimately this is what Webpack configuration looks like. Ultimately at the
bottom of our `webpack.config.js` file, we're just returning a big configuration of
race. So you can actually go after this and you can modify it. Or in this case
there's a lot of times we give you an easier way to do that. So for example, in this
case you can say `.configureSplitChunks()` and you can actually pass it a call
back so that you can take our default configuration from our split chunk plugin. And
you can change it to be a different if you want to. And again, the way that this
happens, what's great about this is you don't have to worry about it as a user.
Webpack decides to split things into multiple files.

And then when the `entrypoints.json` file is written automatically by Encore, it
includes all the JavaScript files that are needed instead of our `base.html.twig`
because we're calling `{{ encore_entry_script_tags() }}` that loops over
our entrypoints that JSON and renders all the `<script>` tags we need. So you basically
get this code splitting thing for free for performance. It's amazing. You don't have
to worry about it. I just want you to realize it's there. It will also start
splitting our `app.css` file and a few minutes as we start adding more stuff to it.