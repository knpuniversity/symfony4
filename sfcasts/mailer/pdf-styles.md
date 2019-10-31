# Styling PDFs with CSS

We've just used snappy to render a template via twig, take that HTML and give us back
PDF content. So this actually represents like the PDF content itself to attach this
to an email. It's pretty much how you'd expect it's `->attach()`.

What's cool here is that you can actually pass this a string PDF where you can pass
us a resource. Uh, that's important because if you are attaching a really large file,
you wouldn't want to read that entire file into content with `file_get_contents()`, um,
that'd be too big of memory. So instead you could actually open it with `fopen()` and
past that here. And then you could send a large attachment without actually reading
that into memory. But we just have a string. So we're going to say `$pdf`. And then, uh,
for the name that's going to be attached to the email, we'll say
`weekly-report-%s.pdf` and we'll just pass that little `date('Y-m-d')`.
love it. All right, so let's try this.

I'll move over to a terminal in our and

```terminal
php bin/console app:author-weekly-report:send
```

now as a reminder, even though this looks like it's sending to six authors, it's
really just going through six authors and seeing if any of them have sent a, um, have
created any content within the past week. So I'm going to move over now to male trap
and you can see that actually sent two emails from me. If they didn't send any emails
from you, re load your fixtures with `doctrine:fixtures:load` a, there's some random
NYSED data. So hopefully you'll get some better data there and you can actually see a
couple of emails sent. All right, so let's check this out. The email itself, of
course it looks the same. There's just one article, but now you can check out
attachments. `weekly-report-2019-10-28.pdf` click to open that and it works. But it
looks terrible. That definitely does not have a bootstrap CSS applied to it.

Why?

Well, what's tricky is even though we have a full HTML page here, there's not really
an easy way to preview what the contents of this look like in a browser. You know
other than rendering it manually from a controller. When you run on-call entry link
tags, what that does is it creates one or multiple link tags depending on how many
you need, but the paths are relative. So behind the scenes, if you picture what WK
HTML to PDF is doing, what snappy does is it takes that HTML content that we passed
it. Thanks the HTML content, we passed it, it saved that to a temporary file on your
filesystem and then runs `wkhtmltopdf` and points it at that, uh file.

So if any, if there are any CSS or image pads in that file that just have relative
paths like `/main.css`, those aren't going to load all of the pads in a, in a PDF
need to have absolute pads with the domain. So one of the trickier things with
rendering PDFs, so the fix here is pretty simple. Instead of `{{ encore_entry_link_tags() }}`
which prints the `<link>` tags for us, we can actually say `{% for path in encore_entry_css_files('app') %}`
and we'll pass that our app. So instead of just printing out all the link tags
we want, this allows us to loop over all of the link tags that we need for our app
entry. And then we can just make the link tags by ourselves. So we'll say
`<link rel="stylesheet" href="">` and then we'll use the normal way that we've been using
inside of our templates. Um, uh, for console commands to make things absolute, which
is `absolute_url()` and pass that `path`. So now on the CSS, it's going to have our full
domain to that CSS file. So when `wkhtmltopdf` renders it, it is going to
be able to go out and download our CSS file and everything should work fine.

So let's go back over, send that run our command again,

```terminal-silent
php bin/console app:author-weekly-report:send
```

move back over in end. Let's
see, I'll refresh there. They go 2 new emails and I'll check the attachment on the
first one.

it looks great. I mean hopefully you're better at styling than I am, so you can make
this look even better. But it does have the styling on there. It's not a particularly
interesting table, but you can see that the CSS is being loaded. Let's check the
other one just to be sure. And this one looks terrible. Interesting. The first email
is good, the second email looks bad. This is a little gotcha. That's specific to
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