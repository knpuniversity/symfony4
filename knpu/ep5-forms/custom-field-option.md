# Custom Field Option

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