# Upload Field Styling & Bootstrap

If you use the Bootstrap 4 theme with Symfony... things get weird with upload fields!
Yea, there *is* a good reason for *why*, but out-of-the-box, it's... just super
weird. The problem? Select a file and... get rewarded by seeing absolutely *nothing*!
Did the file actually attach? We *should* see the filename somewhere. What happened?

## Why Doesn't it Work?

The thing is... styling a file upload field is kinda hard. So, if you *really*
want to control how it looks and make it super shiny, Bootstrap allows you to create
a "custom" file input structure, which is what Symfony uses by default. Check this
out: see the `<input type="file"...>` field? That's *hidden* by Bootstrap! Try
removing the `opacity: 0` part and... say hello to the *real* file upload field...
*with* the filename that we selected!

Bootstrap hides the input so that it, or *we*, can *completely* control how this
*whole* field looks. Everything you *actually* see comes from the `label`: it takes
up the entire width. Even the "Browse" button comes from some `:after` content.

The *great* thing about this is that styling a `label` element is easy. The sad panda
part is that we don't see the filename when we select a file! We *can* fix that -
but it takes a little bit of JavaScript.

## Customizing the Text in the Upload Field

Before we do that, we can *also* put a message in the main part of the file
field by putting some content in the `label` element. But... it doesn't work
like a normal label.

In the `templates/` directory, open `article_admin/_form.html.twig`. Here's
our `imageFile` field. The second argument to `form_row` is an array of variables
you can use to customize... basically anything. One of the most important ones
is called `attr`: it's how you attach custom HTML attributes to the input field.
Pass an attribute called `placeholder` set to `Select an article image`.

This would normally add a `placeholder` attribute to the input so you can have some
text on the field if it's empty. But when you're dealing with a file upload field
with the Bootstrap theme, this is used in a different way... but it accomplishes
the same thing.

Refresh! Cool! The empty part of the file field now gets this text.

## Showing the Selected Filename

But if you select a file... the filename still doesn't show. Let's fix that already.
Look at the structure again: Symfony's form theme is using this `custom-file-input`
class on the input. Ok, so what we need to do is this: on *change* of that field,
we need to set the HTML of the label to the filename, which *is* something we have
access to in JavaScript.

To keep things simple, open `base.html.twig`: we'll write some JavaScript that will
work across the entire site. I'd recommend using Webpack Encore, and putting this
code in your main entry file if you want it to be global. But, without Encore, down
here works fine.

Use `$('.custom-file-input')` - that's the class that's on the `input` field itself,
`.on('change')` and pass this a callback with an `event` argument. Inside, we need
to find the `label` element: I'll do that by finding the parent of the `input` and
then looking for the `custom-file-label` class so we can set its HTML.

In the callback, set `var inputFile = event.currentTarget` - that's the DOM node for
the `input type="file"` element. Next,
`$(inputFile).parent().find('.custom-file-label').html()` and pass this the filename
that was just selected: `inputFile.files[0].name`. The `0` part looks a bit weird,
but technically a file upload field can upload *multiple* files. We're not doing
that, so we get to take this shortcut.

Give it a try! Refresh... browse... select `rocket.jpg` and... yea! Our placeholder
gets replaced by the filename. That's what we expect *and* the field is easier to
style thanks to this.

Next: the upload side of things is looking good. It's time to start rendering the
URL to the upload files... but without letting things get crazy-disorganized. I
want to *love* our setup.
