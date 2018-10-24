# Form Theme Variables

Coming soon...

We just learned that whenever Symfony renders any part of your form, it looks for a
block in this core `form_div_layout.html.twig` to the age smarts wake, for example, when it's
rendering the row part of any field that looks for `form_row()`. We also learned this a
little bit of a hierarchy to it, so when you're rendering the label for a text type,
it first looks for `text_label()`, but then it looks for ultimately looks
for `form_label()` as a fall back because really all labels are the same.

Heck, you can even look at the `form_start()` tag. There's a block that defines how that
looks inside of there. Now we use this information to create our own `form_theme` and
register that hr where we told twig to now look inside of our template for blocks
when it's rendering the registration form so it's not looking at outside of art, a
template and finding `form_row()`. Now when you're inside of these form theme blocks,
you are in an entire different universe universe. You want to pretend like this block
doesn't even exist in this template. It's an island, it gets past a completely
different set of variables from the `Form` system. This does not work like any of your
other blocks in this template. And if you look inside of here, you can already see
that there is apparently a `help` variable. There is apparently a `form` variable. So the
first thing you need to figure out when you're inside of these boxes, what variables
do I have access to? And the easiest way to do that is just to use the `dump()` function
inside of here. So if you go over now and refresh,

yes, we get big giant dumps whenever it renders the rows for any of our fields. So
you can see there's `attr`, there's the `ID`, that's the `ID` attribute that's used for
the full name attribute. This might look familiar to some of you, some of you. These
are the exact variables that we can use when we're rendering our field. So if I go
back and look at `article/admin/_form.html.twig` we had learned earlier that
there is a variable called `label`. And the second argument is you `form_row()` is an `array`
of variables. You can resolve this in the tweet template, function form, reference.
If I search for form row inside of here. The second argument to it, and most of these
functions is variables. So here the idea is whenever a field renders, there are a
bunch of variables that are created in those variables are ultimately used inside of
your `form_theme` to render things. For example, you can see `form_start()`, it's using
a variable called `method` in order to render the method attribute on there that `method`
variables, something that's created by the `Form` system. Then in our templates,

we can actually override those variables when we call our form rendering functions.
So that's a very, very powerful thing to understand right there. Now when you're
inside of a `form_theme`, what you are doing is you are actually in an environment
where all of those variables are passed to you. So that's why we have all these
variables here for each individual field, which is great because it means that we can
actually change those variables, use those variables, uh, do whatever we want with
those.

So check this out. If you go back to our `register.html.twig`, I'll remove
the `dump()`. One of the things in, in the old form is that our labels all had a class of
`sr-only`. That's screen reader only because the labels were actually invisible by
default. So we want our labels automatically do have that `sr-only` class on them.
Now, of course, `form_row()` we call `form_label()` and we just pass it the `form` object
which represents the current form. If you go back to our form function reference and
search for `form_label()`, you'll see that the second argument is the actual label
itself. And the third argument isn't a late `array` of variables. And guess what
variable you have, there is a variable called `label_attr`. If we set
that, we can control the attributes that are passed into our `label` variable. In fact,
to make this more clear, if I go into `form_div_layout.html.twig`, search for 
`form_label` to find the block that's used inside of here, you can see it using this 
`label_attr` variable. It's doing some complex processing on it, but ultimately 
it uses it down here a little bit complicated

to pass in those variables. This is one of the things about these codes. They are
super complicated, but it's using that down there anyways, back on `register.html.twig`, 
weeks since we're the ones calling `form_label` directly, we can pass no as the
second argument, which you remember is the `label_text`, so we'll allow a label tech
still to be auto use the normal value.

Okay.

Then we can pass a third argument here with `label_attr` and then another array of
`class` set to `sr-only`.

All right, so move back, go over refresh and yes you can see it. Those labels are
gone. They now have this `sr-only` a variable paths to them, whereas the only
problem is that there is no actual text here on these fields. The way that was
handled before is that each field had a `placeholder` attribute, so this is kind of the
same thing before. This is actually the same thing. This is just an attribute that we
want to set on each individual input, so we actually could go down to `form_widget()`
here and we could change this widget `attr` to actually have a new, `attr` class on it,
a key on it with a class that does something, but unfortunately we don't know what
the label should be, so instead of doing it there, I'm going to go down and actually
finally refactored my `form_widget()` into just three form render robocalls, so 
`form_row(registrationForm)`, and then let me close a couple of files here and we have `email`
`plainPassword` and `agreeTerms`, so use that email.

Okay.

`plainPassword` and `agreeTerms` and for `email` will pass a second argument which is the
variables `attr` with `placeholder` set to "Email". And then the same thing down here for
`plainPassword`. Is that a `placeholder` to "Password"? And that shouldn't be it. Now
notice we could have been less fancy about this and actually just passed this label a
ttr variable directly down here and that would have worked fine. That would have
passed down eventually into the label and it would have said it. So the form theme
actually isn't completely necessary to get rid of this part. It's just a different
way of doing it. All right, so we'll go back and refresh now. Yes, it pops those in
except I have a typo on password, but other than that, things work great.