# Form Theming a Single Field

The last thing we need to do is fix this "agree to terms" checkbox. It doesn't look
that bad... but this markup is *not* the markup that we had before.

This fix for this is... interesting. We want to override how the `form_row` is
rendered... but *only* for this *one* field - not for everything. Sure, we could
override the `checkbox_row` block... because this is the *only* checkbox on this
form. But... could we get even *more* specific? Can we create a form theme block
that only applies to a *single* field? Totally!

Go back and open the web debug toolbar for the form system. Click on the
`agreeTerms` field and scroll down to the "View Variables". A few minutes ago
we looked at this `block_prefixes` variable. When you render the "row" for a field,
Symfony will *first* look for a block that starts with `_user_registration_form_agreeTerms`.
So, `_user_registration_form_agreedTerms_row`. If it doesn't find that, which of
course it will not, it falls back to the other prefixes, and eventually uses `form_row`.

## Creating the Form Theme Block

To customize *just* this one field, copy that long block name and use it to create
a new`{% block _user_registration_form_agreeTerms_row %}`, then `{% endblock %}`.
Inside, let's *literally* copy the old HTML and paste.

Try it! Find the main browser tab and refresh. Whoops!

> A template that extends another cannot include content outside Twig blocks.

Yep, I pasted that in the wrong spot. Let's move it *into* the block. Come back
and try that again. Yea! The checkbox moved back into place. Yep, the markup is
exactly what we just pasted in.

## Customizing with Variables

This is nice... but it's totally hardcoded! For example, if there's a validation
error, it would *not* show up! No problem! Remember all of those variables we have
access to inside form theme blocks? Let's put those to use!

First, inside, call `{{ form_errors(form) }}` to make sure any validation errors
show up. I can also call `form_help()` if I wanted to, but we're not using that
feature on this field.

Second: this `name="_terms"` is a problem because the form is expecting a different
name. And so, this field won't process correctly. Replace this with the very handy
`full_name` variable.

[[[ code('41fea084fe') ]]]

And... I think that's all I care about! Yes, we *could* get fancier, like using
the `id` variable... if we cared. Or, we could use the `errors` variable to print
a special error class if `errors is not empty`. It's all up to you. 

The point is: get as fancy as your situation requires. Try the page one more time.
It looks good *and* it will play nice with our form.

Next: let's learn how to create our own, totally custom field type! We'll eventually
use it to create a special email text box with autocompletion to replace our author
select drop-down.
