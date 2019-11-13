# Styling PDFs with CSS

Our PDF attachment looks *terrible*. I don't know *why*, but the CSS is *definitely*
not working.

Debugging this can be tricky because, even though this was *originally* generated
from an HTML page, we can't exactly "Inspect Element" on a PDF to see what went
wrong.

So... let's... think about what's happening. The `encore_entry_link_tags()`
function creates one or more link tags to CSS files, which live in the `public/build/`
directory. But the paths it generates are *relative* - like `href="/build/app.css"`.

We *also* know that the `getOutputFromHtml()` method works by taking the HTML,
saving it to a temporary file and then *effectively* loading that file in a browser...
and creating a PDF from what it looks like. If you load a random HTML file on
your computer into a browser... and that HTML file has a CSS link tag to
`/build/app.css`, what would happen? Well, it would look for that file on
the *filesystem* - like literally a `/build/` directory at the root of your drive.

*That* is what's happening behind the scenes. So, the CSS never loads... and the
PDF looks like it was designed... well... by me. We can do better.

## Making Absolute CSS Paths

Once you understand what's going on, the fix is pretty simple. Replace
`{{ encore_entry_link_tags() }}` with
`{% for path in encore_entry_css_files('app') %}`.

[[[ code('7822dca71f') ]]]

Instead of printing all the link tags for all the CSS files we need, this allows
us to loop over them. Inside, add `<link rel="stylesheet" href="">` and
then make the path absolute with `absolute_url(path)`.

[[[ code('38b379a6a2') ]]]

We saw this earlier: we used it to make sure the path to our logo - before we
embedded it - contained the hostname. *Now* when `wkhtmltopdf`, more or less,
opens the temporary HTML file in a browser, it will download the CSS from
our public site and all *should* be happy with the world.

Let's try it! Run the console command:

```terminal-silent
php bin/console app:author-weekly-report:send
```

Move back over and... I'll refresh Mailtrap... great! 2 new emails. Check the
attachment on the first one. It looks perfect! I mean, hopefully you're better at
styling than I am... and can make this look *even* better, maybe with a hot-pink
background and unicorn Emojis? I'm still working on my vision. The point is: the
CSS *is* being loaded.

Let's check the other email to be sure. What? This one looks terrible! The first
PDF is good... and the second one... which was generated the *exact* same way...
has no styling!? What madness is this!?

## Encore: Missing CSS after First PDF?

This is a little gotcha that's specific to Encore. For reasons that are... not
that interesting right now - you can ask me in the comments - when you call an
Encore Twig function the first time, it returns all the CSS files that you need
for the `app` entrypoint. But when we go through the loop the second time, render
a second template and call `encore_entry_css_files()` for a second time,
Encore returns an empty array. Basically, you can only call an Encore function
for an entrypoint once per request... or once per console command execution.
Every time after, the method will return nothing.

There's a good reason for this... but it's *totally* messing us up! No worries,
once you know what's going on, the fix is pretty simple. Find the constructor and
add one more argument - I know, it's getting a bit crowded. It's
`EntrypointLookupInterface $entrypointLookup`. I'll do my normal Alt + Enter and
select "Initialize fields" to create that property and set it.

[[[ code('7e9aae6907') ]]]

Down below, right before we render... or right after... it won't matter, say
`$this->entrypointLookup->reset()`. This tells Encore to *forget* that it
rendered anything and forces it to return the same array of CSS files on each call.

[[[ code('6563cc419d') ]]]

This *should* make our PDF wonderful. Run the command one more time:

```terminal-silent
php bin/console app:author-weekly-report:send
```

Fly over to Mailtrap and... I'll refresh. Ok, two emails - let's check the second:
that's the one what was broken before. The attachment... looks *perfect*.

Next, I like to keep my email logic close together and organized - it helps me
to keep emails consistent and, honestly, remember what emails we're sending. Let's
refactor the emails into a service... and eventually, use that to write
a unit test.
