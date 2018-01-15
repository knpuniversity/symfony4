# MakerBundle: Create a Console Command

You've done it. You've made it almost through the entire first symphony to
Tauriel. Let's celebrate by doing something. Far. Because so far.

We've been doing everything like creating controllers by hand. But there's a
tool that can help us get work done more quickly. The tool that we're going to
use quite a bit. Find your terminal. And run composer require. Maker. This
installs a new package called The Maker Andong. Which is just. Which gives us.
Which. Doesn't give us new. Service tools. Like many packages. But gives us a
bunch of new Bing console commands. Check it out. Run in console. And the M.T.
you can see a whole bunch of new make commands. These are code generators.
There's one for a controller. And other ones for things that we're going to use
in the future king security event subscribers forms and doctrine entities for
the database. Let's use it to create a new kust our own. New custom console
command.

To see how easy that is. Run. Bin cons.. Make. Call on command. That's all I
need to run. And then the commands will start asking you whatever questions you
need. So. Let's make a new bin console command called Article. Call and stats.
Something where we can. Run this to get stats about one of our articles. We had
that. And done just like that it created a new article stats command. It's
checking out. Command article stats command. This isn't already functional
class that gives us a new console command. Don't believe me. Ron been counsel.
Article underscore Colwin stats. And that's. And that's it. And it works. It's
not much. That message is actually coming from our class. Swedes.

So let's configure this to make it our own. I'll change the description to
returns some article stats. And then we can add arguments and options.
Arguments are values that come after the command and options have a dash. A
dash in front of them. For example. I'm going to make this article so that we
can. Make this command so that when we call it we have to pass the article slug
as the first argument. And then we can also pass a format option. As the second
argument. So in form as text or Jaison. This first part is called the argument
and then because this has a dash dash after it it's called an option. So let's
call the first argument slug. Can wait and rename it to.

The articles. Slug. And you can't have multiple you can have multiple arguments
and there they just need to be done in the order that they're passed.

And here will say format. Don't change that description. To the output format.

And we can even give this a default value which will say text. So there is no
flag pass that we use text. This case we value none. We use value required.
Value acquired means of always be dasht hash format equals text but you can
also create flags that are just like dash dash help. That don't need any value.
As soon as we do that. So configures how we configure a command and then for
command is action call that calls execute. This passes us as input output
inputs useful because we get access to our arguments and options and our output
lets us right to the screen. This is also wrapped into an I O object that's
basically. An object that helps you build pretty things on the screen you'll
see in the second. So it's clear this out and say.

Slug equals input Arrow. Good argument. SLUGGO gives the first arguments value.
And they'll say data equals will just create some data about this article.

Slug is the slug and hearts is some random number for now two in ten and a
hundred. Next want to read the format option. Will do a switch statement. On.

Input error. Good option. Format. Say case text. In. Case of text. That I 0 and
then has a nice listing methadone which is a really easy way to create a list
so I'll pass that data and say break. Will also support Jaison as case we'll
just write. Use the right function to write to the screen will say J sign code
data. And. Break. Otherwise why did the fault which threw it was a very clear
exception. Perfect. Let's try it. Flip over. We're passing our Sluggo as the
first argument. We have a format. And there is. Also past dash s. For me it was
Jaison. Or Alene the formant off and get text. There's.

Now this list. My problem this list it doesn't actually tell us what these
numbers mean. So maybe a table would be better.

Let's go back and inside text add a new rows array. Loop over. Data as key.
Val. Will. Add a new row. To this or creating. An array with a key and value
for every road. We're doing that because the iho also has a table method. The
first argument is the headers the table so pass an array with about key and.
Value. Then we pass the array of rows that we want after that will past rows.
Now go back and run it. Much.

Better. There's a lot more they can do with custom console commands you can
even ask users things interactively do progress bars. The sky's the limit for
what I really wanted to show you was the maker bundle.

Which gave us a functional class and just one command the maker bundle is going
to be huge for it. It's going to allow us to code features really really fast
and really really clearly generating working code that we can then just modify.

Our guys. I hope you are as excited about Symphonie as I am. We have so much
more cool work to do. So join us on the next tutorial or really dive into
configuration environments and those really important things called services.
That's going to pave the way for everything else we do after that. All right
guys. See you next time.
