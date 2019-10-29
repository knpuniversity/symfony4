# Create Pdf

Coming soon...

We're now sending an email from our accustom console command, which, which is pretty
cool. Um, we've fixed our duplication in our tent and our email templates and we've
even fixed the paths inside of here so that they work instead of a consult man, I
want to go over to the next level. I now want to attach a PDF to this email. So in
addition to having this table here that summarizes everything that this author has
written in the past week, I wanna attach this table as a PDF. So first we're going to
do is focus on how to create a PDF and how to style it nicely. The tool that I'll
like to use to run PDFs is snappy bundle. So if I a terminal and run a 

```terminal
composer require knplabs/knp-snappy-bundle
```

Now snappy bundle is really just a
small layer around a command line utility called the `wkhtmltopdf`,
which is for a long time been the defacto standard for generating PDFs. You create a
big HTML page with CSS style CSS. You give that to `wkhtmltopdf` and it gives
you back a PDF that is styled exactly like that. We'll look inside of a browser. So
you're also want to make sure that `wkhtmltopdf` is installed on your system 

```terminal-silent
wkhtmltopdf --version
```

and you're going to want to make sure that it is a where it is on my system. 

```terminal-silent
which wkhtmltopdf
```

It's in `/usr/local/bin/wkhtmltopdf`. If yours live somewhere else, then you need to tweak
some configuration. When we installed that bundle, that bumbles recipe, edit a new
section down here to our `.env` file. You can see the `WKHTMLTOPDF_PATH` is the one
I have on my machine, so if yours is different, you can modify this path here or in
`.env.local`. Don't worry about `wkhtmltoimage` cause we're not going to use that

now. Open up `templates/email/author-weekly-report.html.twig`
remember the goal with snappy is to generate a full HTML page and then give that to
snappy. We could actually just render this entire email and pass that to snappy. But
that's not really gonna work because this has the, this requires the `email` object.
And if we just rendered this normally via twig, we're not going to have that, uh,
that `email` variable plus the way we want our PDF to look is probably not going to be
exactly like our, um, our email. We're probably not gonna want this, uh, logo header
up there. So instead of I'm going gonna do is on a copy of this entire table. That is
really what we want as a PDF. And in the templates email directory, I'll create a new
file called `_report-table.html.twig`, all that toy and I'll paste that in
there. I'm also up here going to add a little class `class="table table-striped"`,
these are Twitter, these are CSS classes. And when we render our email, um, these
aren't going to have any effect because there's no CSS that affects those

But when you render the PDF in a second, we are actually going to import our normal
CSS and I want to make sure that actually we are going to have this a be styled. That
was a terrible explanation. Anyways, back in `author-weekly-report.html.twig`
we can remove the table and instead of say `{{ include('email/_report-table.html.twig') }}`
let's wait. Now as I mentioned, what we're going to get to
snappy is actually a full HTML page with CSS, not just a little fragment like you see
in this template. We could do that, but then there wouldn't be any styling. It
wouldn't look very good, so instead of any templates email directory, create a new
file. Let's call it `author-weekly-report-pdf.html.twig`

to create the full HTML body here in Peter's dorm, you can use the shortcut
exclamation point tab. That's going to give you a nice full age, Tim, a body inside
of here because I'm planning on bringing the Twitter bootstrap CSS that we use on our
normal site. I'm going to add a little bit of Mark up here cause I did `class="container"`
`<div class="row">` `<div class="col-sm-12">` and it's out of
here. I'll put a lot of it, a little `<h1>` that will say weekly report and then we
can print the date by saying that `{{ 'now'|date('Y-m-d') }}` well this will bring in 
the table itself, so `{{ include('email/_report-table.html.twig') }}`

Now if we just rendered this, if we just passed this to snappy, it would work, but
there's no CSS at all. If you look in our base that aged him a twig, this project
uses webcam, Webpack Encore and we use this Encore entry links tag to basically say
bring in all of our CSS in the app entry. Even if you're not using Encore, the point
is that you have some CSS that you normally bring into your application. I'm actually
gonna copy that line, close that template and put that in my PDF template. So I'm
going to include the normal CSS that my site uses, which is going into, which is
going to include bootstrap, which is going to help me, uh, with some styling here. By
the way, if you are generating multiple PDFs in your site, you probably refactor this
template into some sort of a `pdfBase.html.twig`, that all of your
PDF templates could extent that way all your PDFs gonna have the same look and feel.
Oh, and because we're using the site CSS, one thing you'll notice is that our site
actually has a gray background.

Okay.

If you looked in the CSS tags in there, you'd see

that's applied to the body tag. So I'm just going to override that here. A really
easy way on the body tags. So the background color is white.

Alright, so we have a template we can render that's going to give us what we want an
email to want it. We want our PDF to look like. So back in `AuthorWeeklyReportSendCommand`
Right before we create our email. This is when we're going to want to
generate the PDF so we can attach it. To generate the PDF, we're going to need two
new services in our command. The first one used is going to be the type hint
`Environment`. It feels a little bit weird, but you see this one from twig. That's
actually the type that you use. If you ever want to use the tweak object directly,
we're gonna use the TWIG object directly to render our new PDF template. The other
thing we're gonna need is a service from snappy. That's going to help us turn that
HTML into the PDF content. And the type in for that is just `Pdf. So I'll do 
`Pdf $pdf`. As a reminder, you can always go over and run 

```terminal
php bin/console debug:autowiring pdf
```

to find that type pens that you can use inside of your application. All right, back down
in the body of our command, right before we send the email, this is when we are going
to generate that, uh, that template. So first I'm going to say `$html =` and
we're going to render this template just using normal twig code. The way you do that
is `$this->twig->render()`.

Oh, and you know what? I'm not saying tweak here because this is being called because
up here I forgot to initialize both those fields. So that Alt + Enter
and select an "Initialize fields" to create both those fields and set them all right,
that's thank you PHPStorm for catching that. Okay, now we'll say
`$this->twig->render()` this time it finds it and we'll say 
`email/author-weekly-report-pdf.html.twig`. And the only variables we need to pass into this,
um, are actually the one from `_report-table.html.twig`, which is we need to pass in the
articles in that list. So I'll pass in an `articles` variable set to our `$articles`
variable here, right? So that just gives us an HTML page to turn that into a PDF
content. We can say `$pdf = $this->pdf->getOutputFromHtml($html)`. This is a really cool
thing where you can pass in html string and it's going to give you back these binary
PDF string and content

So at this point, we have a PDF string content, which we could use for anything, and
we could attach it to an email. If this were a controller, we could stream that back
to the user. We can do whatever we want with that. So next, let's actually attach to
this email and send it. When we do that, we're going to notice a problem. We're going
to be missing styles inside of that, inside of that PDF.
so let's try that and fix that next. Okay.