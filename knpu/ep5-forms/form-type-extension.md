# Form Type Extension

Coming soon...

No,

I'm going to talk about it. Crazy. Powerful feature in Symfony is `Form` system that
allows you to modify any part of any field in any form across your entire system. As
an example of this, we're going to talk about the `<textarea>`

feel.

One of the properties of text areas in general is that you're allowed to have a rows
option. You've reset `rows="10"`. It gets longer. Okay, that's nice. So what if we
decided, and of course that means that when we have a `<textarea>`, feels like our
content we right now could actually pass in `attr` option, set `rows` to some value.
What if I wanted to do that automatically for every `<textarea>` across my entire
application? Is that possible? Absolutely. In your `Form/` directory, create a new
directory called `TypeExtension/`

and then inside there and your class called `TextareaSizeExtension`, and make this
implement `FormTypeExtensionInterface`.

As the name implies, this allows you to extend existing form of types in the system
or the Code -> Generate menu or command and in quite implement methods to implement
all of the methods. And notice, look, these are the same exact methods that we've
been implementing in our form and they're going to work pretty much the same way. Oh,
except for `getExtendedType()`. In order for a Symfony to know that this exists, we need to
go into our config pack, our `config/services.yaml`, and at the bottom we need to
register, we need to give this a tag, so I'll put the name of the farm class or just
`App\Form\TypeExtension\TextareaSizeExtension`. That below I'm going to add
`tags:`. And the syntax here is a bit ugly when you the 
`- { name: form.type_extension }`
. And then I'll say `extended_type:`. And here we're going to put the
class name of the field that we want to extend. We want to extend the `TextareaType`,
so I'll cheat real quick. And Edda, use David for `TextareaType` copy, that class
name, delete that you statement and go and paste this right there.

Oh, when I did my comma, now as soon as we do this, every time a `TextareaType` is
created in the system, every single method on our `TextareaSizeExtension` will be
called, and it's as if each of these methods actually lives inside of the text area
type. So if we put code inside of the `buildForm`, that's as if how open up the text
area type class that says, if we're literally inside this class inside of a method
called bill form, it puts us effectively inside of the class that we're modifying.
Now, two important things. If you're using simply four point two, then you do not
need to add this, any of this `service.yaml` stuff, and simply four point two, this was
improved, so this is all detected automatically. The way that Symfony knows which
type you want to extend in that case is actually something we need to do as well, is
in `getExtendedType()`. When you `return TextareaType::class`. So before simply
four point, do you still have to have it registered as a service? It's a little
redundant because it's in two places, so that's been improved. Alright, let me remove
the rest of the to do's here

and then we will get to work and we don't need to implement all of these methods. We
just need to add code to the ones that we actually need for whatever we're doing. So
in our case, what we want to do is we want to add a new view variable. We want to
modify the aptr view variable so that it has a rose field on us, so we can do that in
`buildView()`. We can quite literally say `$view->vars['attr']`, and then set a
`rows` attribute equal to 10 and that's it. Move over and refresh. It still looks big
and I inspect it. Yes, `rows="10"`. Every single `<textarea>` on our site has been
modified just like that. By the way, if you instead, if you want it to modify every
single field on your system in some way, you can actually use a `FormType::class`
because of Sydney has that inheritance system behind the scene. All types, all
fields. Eventually extended `FormType::class`, so that's your way to where
you could actually modify everything.

Anyways, let's make this a little bit more flexible because right now we have
hardcoded of 10, but what if I still want to be able to control this on a field by
field basis? For example, in this case I'll pass, knows the type so it keeps guessing
text area. What if I wanted to be able to say rose 15 inside of my forum class and
override that right now if you refresh, you're going to get a huge error, undefined
option that rose the option. Rose does not exist. You can't just invent new options
here. There are only a set of valid options, but in `TextareaSizeExtension`, you can
invent new options. You can do it down here and `configureOptions()`, so we'll say
`$resolver->setDefaults()` and I'm now going to invent a new `rows`,

a option,

and given the default value of 10, now appear in `buildView()`. Notice that almost all
these method are passed the final `$options`. We can just use that options rows and it's
that simple. It will default to 10, but since we've overwritten it in this form,
yeah, that looks really big on inspect element on the `<textarea ... rows="15" ...>`, so this
is the power of form type extensions in Symfony actually uses these behind the scenes
to do various things. For example, the CSRF token looked down the bottom of your
field, of your form. Every single form somehow magically has a CSA, has an_token
field attitude it. That whole thing is handled, but they formed hip extension type
shifts shifts. You can look for CSF

form

for a CSRF extension and you'll actually, you'll find one of the tweak bridge, but
you also find one there. It is the `FormTypeCsrfExtension`, it extends an
`AbstractTypeExtension`, which actually it just implements `FormTypeExtension` and
prevents you from needing to override everything so you can also extend `AbstractTypeExtension`
if you want it to instead of implementing the interface directly. But the
point is this is actually the spot that in `buildForm()` actually does some magic. It
uses an event, subscriber, a topic we talked about to ultimately add that a field
into the view and then adds a bunch of options that you can configure to control the
behavior. So this is still a little bit more advanced, but you can actually see a.
These custom form type extension is actually control. A couple of things can the
corpse and pay themselves.