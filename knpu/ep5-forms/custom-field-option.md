# Custom Field Option

Coming soon...

Thanks to the `DataTransformer` and specifically the fact that the `DataTransformer`
throws a `TransformationFailedException` when a bad email is past our field. Our user
select texts type has some built in sanity validation. Somebody puts an email address
in here that doesn't exist. It will always fail validation. The way to control that
error message for sanity validation is on the field itself to add an `invalid_message`
option, but actually instead of putting it on the instead of putting it when we're
configuring the field, we can actually give it a default value. So go back to user
select text type, go back to code, generate menu or Command + N on a Mac and override
`configureOptions()` inside. Here's a `$resolver->setDefaults()`, and here we can say
`invalid_message` is equal to "User, not found". All right, so try that out. Go back,
refresh

and

nice. There is the message.

So this is kind of a cool thing. We've seen this `configureOptions()` before we sought
on our. When we're building our forum class. So when you use `configureOptions()`, when
you're building an entire form, you're configuring your setting options for that
entire forum and there aren't very many data classes, the most important one, but
when you're creating a custom field type the `configureOptions()`, our options for that
specific field. So here we're setting an `invalid_message` option for this field and
the cool thing is is that those can be overwritten if we want to, when we use that
field so we can still set in an `invalid_message` when we use it to a different message
here and that would override that option. More options. Things are pretty

handy

actually. I want to talk about them a little bit more, but first there is a small
problem with our data transformer and that is that if we empty the author field and
then of course we need to disable html five validation. So I'll add the novalidate
attribute and hit update.

Our sanity validations still failed. It says user not found. That's not quite right.
Our data transformer should probably not fail in that case. It should just pass. `null`,
here's what I mean. Go back to your `EmailToUserTransformer` in `reverseTransform()`.
If there is no `$value` past that's an empty `string`, then let's just `return;`. What this
means is that if we submit the field empty or when you first transform is going to
return `null`, which is really the proper behavior for a transformer. Now the problem
with that is that it means that our set off their method is going to be called with
no, which technically is fine except that we want our author field to be required. So
if I actually submit this again, you'll see that we haven't given integrity
constraint violation, no is being said on the author, but the `author_id`, we made that
a required field in the database so it ultimately blows up. This is a great example
of missing business validation. So what were the top row of our author class `Article`
class. Find the `$author` field and we need and `@Assert\NotNull()` here and we'll say
`message="Please set an author"`

Now refresh.

Excellent. There is the error message we expect. All right, so I'm going to make our.
I want to make our field a little bit, our `UserSelectTextType` a little bit more.
Um, flexible. Supposedly use this `UserSelectTextType` in multiple places on our
system, but sometimes we want to allow any user demanded here, but sometimes we want
to do a custom query. Maybe we only want a certain sub set of users to be valid right
now the way that we're creating for our users, hard coded into our `email` to use a
transformer. So let's see if we can make that more flexible so that we can ultimately
control that query from our `Form` class when we use that. Like literally inside of
here will be able to pass an option that where we can control how we're querying for
the `User`. All right, so check this out. Let's start inside of the transformer first
and we did do is instead of doing this query here, let's actually make an option past
in here at a new construct, an argument which is a `callable`, and we'll call it 
`$finderCallback`. I'm just making that up. I'll hit all enter to initialize that field.

The idea is that whoever creates whoever uses this transformer, they'll pass in a
`callback` that's responsible for querying for the `User` down here. Instead of fetching
the user ourselves, I'll say `$callback = $this->finderCallback`, and then
we'll just say `$user = $callback()` and we'll execute that `callback` in for convenience.
We'll pass them `$this->userRepository` and will also pass them the `$value` that was just
passed in. Cool, so we've now made this class a little bit more flexible and 
`UserSelectTextType`. Though we now need pass this `$finderCallback` to our `EmailToUsertransformer`. 
So here's how we're going to do this. We're going to create a new
option. `invalid_message` was already an option that exists in Symfony. It has a
purpose, but we can totally invent new stuff. So I'm gonna. Make a new option called
`finder_callback` in. I'm going to give it a default value of a `callback` that accepts
a `UserRepository $userRepository` arguments, and also accepts the. The value which
we know is going to be a `string $email`. And inside I'm just going to 
`return $userRepository->findOneBy()`, it'll pass `['email' => $email]`. So I've done as I've
now created a new option that can be used to configure our field and it's set to a
nice default value, the same query that we're doing a second ago. Now, here's the
important part in our bill for method, see this array of options that is an array of
all of the options past to this field, which are basically any of our defaults.

Would you include any of our defaults? So we can do is I'm going to bring this on the
multiple lines for the second argument to `EmailToUserTransformer`, we can pass
options `finder_callback`. Alright, there's one little piece missing here, but let's
actually make sure this works. I'm just going to hit enter to reload the page and
down here I'll change the spacebar2@example.com. Hit Enter and yes it works
perfectly still. Now the real power of this is that in `ArticleFormType`, when we use
the `UserSelectTextType`, we can pass in at that `finder_callback` now if you
want it to and we can actually override that `callback` and do something entirely
different. I'm not going to do that here because I don't need to,

but if we did pass the `finer_callback` here, it would override the default here and
then when we initialized our `EmailToUserTransformer`, that would be the user
provided `callback`. This is how options are used internally and by the way, as you
probably noticed, just like every field type in Symfony is a class. So if you're ever
using a fuel type and Symfony, you want to know more about it. You can just open it
up. So for example, we know that this is the `DateTimeType`, so I'll hit shift,
shift and look for daytime type and get the one from the form component. It's going
to look a lot like ours. It has a bill form method. It actually adds some
transformers to it and then if you look down far enough, you're eventually going to
see a `configureOptions()` where you can actually see what all of the valid options are
for this field.

If you're interested in how they, how they are used, you could copy of one of those
options and just look for it inside of this class. So this is inside the `buildForm()`
method and you can see basically how those options are used. So even if you don't
know a lot about this field type by opening up the class and looking at the options
and just looking at the methods to see how are things are used. A lot of times you
configure out how to do something really advanced or use these as inspiration for
your own custom field type.