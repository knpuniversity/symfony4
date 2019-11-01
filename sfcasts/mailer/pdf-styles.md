# Styling PDFs with CSS

Our PDF attachment looks *terrible*. I don't know *why*, but the CSS is *definitely*
not working.

Debugging this can be tricky because even though this was *originally* generated
from an HTML page, we can't exactly "Inspect Element" on a PDF and see what went
wrong.

So... let's... think about what's happening. The `encore_entry_link_tags()`
function creates one or more link tags to CSS files, which live in the `public/build`
directory. But those paths are *relative* - like `href="/builds/app.css"`.

We *also* know that the `getOutputFromHtml()` method works by taking the HTML,
saving it to a temporary file and then *effectively* loading that file in a browser...
and create a PDF from what it looks like. If you loaded a random HTML file on
your computer into a browser... and that HTML file had a CSS link tag to
`/builds/app.css`, what would happen? Well, it would look for that file on
the *filesystem* - like literally a `/builds/` directory at the root of your drive.

*That* is what's happening behind the scenes. So, the CSS never loads... and the
PDF looks like it was designed... well... by me. We can do better.

## Making Absolute CSS Paths

Once you understand what's going on, the fix is pretty simple. Replace
`{{ encore_entry_link_tags() }}` with
`{% for path in encore_entry_css_files('app') %}`.

Instead of printing all the link tags we need, this just loops over all of the
CSS files we need to include. Inside, add `<link rel="stylesheet" href="">` and
the make the path absolute with `absolute_url(path)`.

We saw this earlier: we used it to make sure the path to our logo - before we
embedded it - contained the domain name. *Now* when `wkhtmltopdf`, more or less,
opens the temporary HTML file in a browser, it will download the link tags from
our public site and all *should* be happy with the world.

Let's try it! Run the console command:

```terminal-silent
php bin/console app:author-weekly-report:send
```

Move back over and... I'll refresh Mailtrap... great! 2 new emails. Check the
attachment on the first one. It looks great! I mean, hopefully you're better at
styling than I am... and can make this look *even* better, with a hot-pink background
and unicorn Emojis. The point is: the CSS *is* being loaded.

Let's check the other email to be sure. What? This one looks terrible! The first
PDF is good... and the second one... which was generated the *excat* same way
has no styling!? What madness is this!

HERE!

This is a little gotcha. That's specific to
Encore

four

for reasons that are not that interesting. But if you want to know, you can ask in
the comments section. When you call in Encore function the first time it returns all
of the CSS files that you need for the app entry point. But the second time that we
run through, we go through the loop and the second time that we render this template
and the second time that we call this `encore_entry_css_files()` function Encore returns
an empty array. So basically you can only call one of the Encore functions once per
request. By the way, I forgot to mention why I didn't do the JavaScript files. You
need to put that in there and if you call it more than once per request or once per
command, it's going to return nothing. There's a very good reason for this, but in
this context it can be a gotcha. So the fix is to autowire one more function into
our command. I know it's getting a little bit crowded here and it's a lower level
function. Don't class you don't need normally need to worry about, it's called
`EntrypointLookupInterface`?

Let's say `$entrypointLookup`

`EntrypointLookupInterface` and `$entrypointLookup`. I'll do my normal Alt + Enter
"Initialize fields" to create that property and set it and then down here, right before
I render all the, we can do it right after, it doesn't really matter. We're going to
say `$this->entrypointLookup->reset()`. That's basically going to say
forget about any previous renderings you've done during this command and render
everything fresh. So last time and move over. Run a command

```terminal-silent
php bin/console app:author-weekly-report:send
```

spin back over to metal
trap. I'll refresh. So my files shop there and let's check the second one in here.
I'm pretty sure the first one is going to be okay. Open up that attachment and
perfect. It looks good. Both of these files render just fine. Next, let's do
something different. I can't remember what it is.