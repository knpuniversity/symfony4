# Processing Encore Files through inline_css()

So based on whatever's most efficient, it might tomorrow start splitting these into
three files or only one file. Also in production, these file names would change and
they would start including a hash on them like `email.12345.css`
That changes every time the content of the email changes. This is why
normally for example, in `base.html.twig` we just call it `encore_entry_link_tags()`
that takes care of everything. It actually looks in the `public/build/` directory
for an `entrypoints.json` file, and this actually tells it all of the files that
it needs to include for the entrypoint, sorry for the app CSS or for the app
JavaScript. So if we look down here for our email one, you can see that it's
advertising that we said the two files that we need. The problem is that we don't
want to just output link tags. We actually need to read the source code of those
files.

Now [inaudible] by using another Encore function called `encore_entry_css_files()` and
some serious twig magic, we can actually do this, but it's kind of so crazy and so
magic that instead I'm going to create a new twig function whose job is to load all
of the source CSS for specific entry. So I'll actually show you what it's going to
look like. First, I'm going to make a new function where I can say
`encore_entry_css_source()` and then pass it `email`. That's gonna be smart enough to find all the CSS
files that are needed for the email entry point, load their contents and return them
as one big giant string. To do this, to add that custom function, our application
already has a custom twig function called `AppExtension`. So inside of here

I'm just going to add a `new TwigFunction()` called `encore_entry_css_source`

and the method that we'll call in this method book called `getEncoreEntryCssSource`
So I'll copy that name then down here.

Call `public function getEncoreEntryCssSource()`.says a source that's going to take a
`string $entryname` and it's also gonna just return a `string` of the CSS source. Now
in order to, um, Symfony fortunately already has a built in service that's smart
enough of smart enough to look in the `entrypoints.json` and returned the files that
you need for specific entry the way get that services to type hint, a entry point
collection and `EntrypointLookupInterface`.

Now for reasons I don't want to get into in this tutorial, instead of using proper
constructor injection, we're using something down here called a service locator and
there's a performance reason for that and you can read about it in this tutorial. The
point is regardless of whether you're using the kind of facet fancy locator injection
or whether you want to use kind of the normal um, a constructor injection, we need
the `EntrypointLookupInterface` service. So because in this case, because I'm using
this service locator thing, I'm going to go down to `getSubscribedServices()`
and certain `EntrypointLookupInterface::class` and that will suck it into
this method. Then up and `getEncoreEntryCssSource()`. We can start with
`$files = $this->container->get()`. And your `EntrypointLookupInterface::class`. So when you're using
the service locator pattern, that's how you would get that service out. Otherwise, if
you're doing it to the constructor, it's just `$this->entrypointLookup`. And then
this has a handy thing on it called `getCssFiles()` and we pass it the `$entryName`. So
this should return to us in array with something like these two paths, uh, built in
there.

so we will foreach over `$files` as `$file` and above, that's all credit in new `$source`
variable set to an empty string. Now all we needed to do was take these pads and
actually go look for that path inside of the public directory and open and open that
file up. I could hard code the path to the public directory right here. Instead I'm
going to set up a new parameter and inject it. So open up your `config/services.yaml`
file. And one of the things we talked about in previous tutorial is this global `bind`
functionality that's under defaults. This is a way for us to set, um, scalar
arguments that we want to be autowirable into our system. So I'm not going to do
one here called `string $publicDir` set to `%kernel.project_dir%`
That's a built in parameter that is the full path through our project `/public`
now is saying `string $publicDir` dear here. What that literally means is the `string` part
is actually optional.

now putting `$publicDir` here, that literally means that we can go to any service
and up in the constructor we can have, I'll add `string $publicDir` and Symfonys to
get to know what value to pass to the public during this wouldn't normally be auto
wired well because it's not a service bypassing a `string $publicDir` , the `string`
parts actually optional. That's a new feature and 4.2 and actually means that you
have to type it this with `string` in order for that, a autowiring to work. So it's a
little bit more responsible. We didn't use that up here on these other ones. Uh, but
we could have, so we're gonna have `AppExtension` `string $publicDir` there. I'll hit
Alt + Enter and Go to "Initialize fields" to create that property and set it you okay?
Finally we can go down here and we can say
`$source .= file_get_contents($this->publicDir.$file)` And those files should have
an opening `/` on them.
So we shouldn't need a `/` in the middle and the bottom or `return $source`. Whew. Okay,
let's try this. We're already running Encore, so it's already dumped our `email.css`
and `vendors~email.css`. So all we need to do is actually just go and try to send an
email. So I'll hit back.

Okay.

Bumped an email type any password, hit register and wow. Okay, great. No errors
didn't mean to sound so surprised. Go up. I'll refresh. Mailtrap okay. Now I remember
because we've refactored to use, um, a messenger that email's not going to be sent
until we consume messenger. So I'm actually gonna open up a new tab or a

```terminal
php bin/console messenger:consume -vv
```

There it is. You can see the messages found. The
messages got sent, spin over and there it is. And the styling looks great. All the
styles are in line. The styles are actually coming from CSS and SASS. So a little bit
of setup with Encore. Um, but you can absolutely get it working and it's a great way.
All right guys, I hope you absolutely loved this tutorial. I hope you want to mail
things. I like you. Okay. Bye. Bye.
