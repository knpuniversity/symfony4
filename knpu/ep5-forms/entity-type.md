# Entity Type

Coming soon...

Alright, next, right now when we submit the form or setting the `author` to whoever's
currently logged in in my application, I actually want the whoever creates the
article to be able to select the `author`, so go to the. If you go back to the
documentation, click back to the form field types. One of the most important options
and all of Symfony is the `ChoiceType`, which actually is responsible for creating
select elements, radio buttons, and check boxes all in one because if you think about
it, those are all just different ways to choose things the way you and it's really
simple. You basically just pass it eight choices option with the choices you want.
Maybe yes, no, and you get a dropdown if you want to. If you don't want to drop down.
If you want radio buttons or you want select boxes, you control those by the multiple
and expanded options. So for example, if you said expanded to true, you'll get
instead of a dropdown, you would get radio buttons. If you then also said multiple to
true, this would turn it into checkboxes. So the APP is super powerful for creating
dropdowns, but in this case of `author`, we actually have a special case because we
want a dropdown, but one of the dropdown to be populated from a table in a database,
when you have that situation, instead of the `ChoiceType` you want to use 
the `EntityType`

you have to type is actually kind of a child of choice typing, see parent type choice
type. So it basically has all the same options, but it makes it a little bit easy to
query from the database.

So first go to your `ArticleFormType` and let's add our `author` field and we're
choosing the word author here because that is the name of the property on our, uh,
`Entity` class. And more importantly it's because we have a `setAuthor()` and a 
`getAuthor()` methods. Now, as soon as we do this, if you go over and refresh, boom, we have
a dropdown. It's populated with all of the users from the database might look a
little bit weird because by default it uses the `__toString()` method that we have on our
`User` entity in order to figure out what display value to use, so the first name, if
you don't have a two string method, you'd actually get an error and I'll show you in
a second how we can control this and get rid of that air.

Now we know behind the scenes that this is being guest as an entity type, so just to
be a little bit more specific, I'm actually gonna pass `EntityType::class` is the
second argument because something interesting happens. Suddenly we get a huge error
required. Option of `class` is missing, so check this out. When you use the `EntityType`, 
here's one option that you have to pass it, which is `class`, which makes sense
because this is the `Entity` class that you want to display. The cool thing about form
field guessing from type guessing is that if you allow the type to be guest, then it
also tries to guest certain options, but as soon as you pass the type, it stops
passing the options, which means that we need to pass `class`, specifically 
`User::class`. As soon as we do that. Nice. It works just like a day before. I
want to talk about a few other options on the `EntityType` and actually a lot of these
options, as I mentioned, a lot of these options on `EntityType` are the same with
`ChoiceType`. The first one is `choice_label`.

This is how you, if you're not happy with just using the `__toString()` method, this is
how you can control exactly which field on your entity is used for the for the
options, so let's say `choice_label`, and we can set this to `email`. That
means that we're going to call, `getEmail()` on our `User` objects, so want to refresh.
Nice. It works perfectly. If you want to get fancier, you can also pass this a
callback. It's gonna receive the object. The `User` object, and here you can return
whatever you want, so we'll `return sprintf('(%d) %s', $user->getId(), $user->getEmail());`
Now under your refresh, Nice. We get a very
specific format. One option that's special to the `EntityType`. One other option that
the staff has that actually be `ChoiceType` also has is `placeholder`. This is how you
add that empty option on the top, which we'd want. It's a little weird that it auto
selects the first one, so let's go back over and add `placeholder` sat. Two shoes, an
author the back. Try that the highest. That works. Perfect.

All right, well now that we have this setup, let's go back to our controller. Let's
remove that. Set off their call in. Let's actually try this. Before I fill the
center, I just want to point something out. If you look at the options, the value of
each options, the idea that user and the database, so when we choose an author, this
is the value that's actually going to be submitted to the server, the number. I want
you to remember that. Oh, that's right. A very important article. Pluto. I didn't
want to be a planet anyways. Anyway, we'll set the publish that just to today at any
time or you can leave that blank.

Thanks.

And she's an author and we'll hit create. Yes. spacebar3@example.com
and it is published.

It's actually a more amazing than it seems and it's because of the data transformer.
The intercept is cool because it makes it really easy to create a dropdown or check
boxes based on some a `Entity` in the database, but the really important part of it is
the data transformer. The fact that when we submit an id like 17, it transforms it
into the `User` object. That's important because when we submit a form system
ultimately calls `setAuthor()`, so this needs to be a `User` object. The data transformer
takes care of that for us. Now I want to do one last thing.

Okay.

If you go back to the create connect to create, a lot of times you might want to
control. You might not want to show all of the rows from a database table where you
might want to control how their order. You can totally do that needed to type. There
are actually two ways. Normally when you use the `EntityType`, you don't need to pass
the `choices` option. Remember that if you look at the `ChoiceType`, he `choices` option
is how you actually specify which choices you want to show. In the dropdown. We don't
have to pass the `ChoicesType` because we pass the class and then it queries for the
choices automatically to control how that query that query. There's an option called
`query_builder`,

which you can use to create a custom query, but actually I'm going to do it a
different way. I'm just going to say instead, I'm just going to override the `choices`
option. We can do that. We can say, look, I can handle queries for the choices
myself. To do that, instead of our form class we're going to need. We're going to
need to query, so we're going to need the `UserRepository`. So let's. Fortunately, our
services, our form types, our services, so we can use dependency injection like
normal to create a `__constructor()` at an `ArticleRepository` argument. I'll hit alt enter,
go to initialize fields to create that property and set it

perfect.

Then down below where `choices` set to `$this->userRepository`. I did it again. We need
the user repository user repository. I'll hit alt enter to initialize those fields to
create that property and set it

perfect.

Then down below we'll just pass `choices`, `$this->userRepository()`, and I'll call a new
method on it called `->findAllEmailAlphabetical()` copy that. Then we'll go over to our
`src/Repository/`, open `UserRepository`, and we'll create that method. Say

```
public function findAllEmailAlphabetical()
{
    return $this->createQueryBuilder('u')
        ->orderBy('u.email', 'ASC')
        ->getQuery()
        ->execute();
}
```
 
and above. This will advertise this returns. We know this returns an
array of `User` objects. Perfect. That makes our article form type happy. Now when we
go back to our form and refresh,

yes, so you can see it is reversed the order on those. I've got the admin first, so I
just want to point out at this point it might look like we are using the `EntityType`,
but we're not really using any of the features after all. We are passing the `choices`
in specifically so we could just use the `ChoiceType`. Right? Well actually no. The
one important thing to `EntityType` is still giving us is that data transformation. The
idea that when we pass it an `array` of we it an `array` of `User` objects in it knows how
to convert those into the label we want and then when we submit the ID it knows how
to convert and make a query to convert that back into the `User` object. So we're still
taking advantage of that. Was that man.