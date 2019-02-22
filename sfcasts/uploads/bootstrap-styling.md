# Bootstrap Styling

Coming soon...

Okay.

When do you use the bootstrap 4 theme? With Symfony? Uh, it's a little bit weird as
we saw because when you browse for files, you don't see anything. It usually says the
file name. I want to talk a little bit about how we can, the reason for this and how
we can make this work better. So the issue is that styling file upload fields is a
little bit hard. So if you want more control over it and bootstrap has a way for you
to create this custom file div and a bit of a structure here so that you can make it
look how you want. And this is what Symfony uses by default. Now check out here.
Here's the `<input type="file"...>`. This is actually hidden in by bootstrap unchanged the
capacity there is the actual file upload field. So bootstrap does is it actually
hides this and then all the markup comes from this label.

This label is actually that entire width and oddly enough that browse button actually
comes from the after content of the field. So this is great because it gives us
massive control over how the file upload field looks. Um, but you need to do a couple
of special things to actually make it at least function as well as you want. First
thing I'm going to show is how we can change this browse button cause you can see
it's actually the file label after. So it's like, okay, how can we customize that
when we're working with Symfony forms?

So go over to our `templates/` directory and open `article_admin/_form.html.twig`.
And here is our `imageFile` field. So normally the second argument, as you guys know,
it can be used to pass variables. One of the most important variables, it's called an
attribute, which normally is attributes that are added to that input field. In this
case I want you to add `placeholder` set to "Select an article image". This would
normally add a placeholder attribute to the input, you know, so you could have, for
example, some empty text if an input isn't filled then. But for the file upload
field, it's used in a special way. It's actually used as the default text.

Okay.

Um, in the image field itself. Now, if we select a file, it's still not going to
update though. So we need to fix that as well. The way you do that is if you, because
Symfony's form theme is opted into this custom file input, we need to do it with a
little bit of JavaScript. So if you look at the structure again, basically what we
want to do is change the html of this label. One a file is selected. So to keep this
simple, I'm actually gonna go into my `base.html.twig`. I'm the rice in JavaScript
that will work into across the entire site. So I'll go down here. I'd have a little
of global JavaScript, um, I recommend using Webpack encore actually for
this, instead of just putting inline JavaScript, but I'm trying to keep things simple
in this tutorial, we're going to say, here is, we're going to find it all 
`$('.custom-file-input')` fields. That's the class that actually goes onto the input itself. 
And then we'll say `.on('change')`. We'll pass that call back. And here what we're gonna do is
we're very simply, we're going to actually find the label. So I'm actually gonna go
to the parent and then I'm going to find the custom file label down there. And then
we're going to set the inner html. So first I'm going to grab the input. It's element
itself cause we'll need that in a second. That's `event.currentTarget`. That will
be, they'll represent the `<input type="file">`. And then we'll say, okay,

okay,

`$(inputFile).parent().find('custom-file-label)`, well I'll say it `.html()`. And
to get the actual file name that was just uploaded, we can say `inputFile.files`.
Now I should be an array because technically you can have multiple file uploads,
sport and then `.name`. So probably not something that you worked with very often. Um,
but that should do it.

Okay.

All right, let's try that. Refresh. We've got our custom select an article image. We
select rocket dot jpeg and boom. There it is. Very, very nice. There's a few other
things you can do, but that is enough to get you started. Now you can style it
however you want.