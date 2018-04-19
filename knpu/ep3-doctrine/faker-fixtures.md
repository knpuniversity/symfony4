# Using Faker for Seeding Data

Coming soon...

Ok, 

the problem is that our dummy data is super duper boring. It's all the same stuff. Obviously as good php developers, you guys know that we could put some random code in here to help. I mean we already have a random published that date. We can just do the same thing for everything else, but there's a much cooler way. We're going to use a library called faker google for faker php to finally get hub page from Francois. Zena, no tow the original symphony documentation, master fun fact. This library is all about creating dummy data. Check this out. You can use it to generate random names, random addresses, random texts, a random letters, numbers between this and that. Paragraphs, street codes. Telephone number is pretty much anything you can dream up. There's a function to generate random fake data. It's awesome. So let's get this installed. Copy the composer require line, move over and paste, but at the Dash, Dash Dem, because we're only going to be using this in our fixtures so we don't need an on production. 

Cool. When that finishes, let's look back over it. How to use the library. OK, so we just need to say failure equals victor fakers, last factory, colon, colon, create. So let's go to our base fixture class and set this up in just one place. So I'll create a new protected faker property. And then down below I'll say this Arrow faker equals, and then I'll look for a class called factory. There's one from finger colon, colon create. Now we should also add some documentation above this. So I'm gonna hold, command or control clicking a factory in this apparently returns a generator. So let's go up here and say at Bart Generator and find one from finger. Perfect. This makes using fager. Awesome. All right, so let's do a couple of things. First. Right now we have a little random function to set to make most articles published. If you want, you can say this Arrow, faker, arrow, boolean, and then the first argument here is chance of getting true. So let's say 70 percent chance that we're going to publish the article. Awesome. Down here, this long string here to make a random function. Let's random time that's replaced that with this Arrow Arrow date time between say between negative 100 days and negative one days. 

Perfect. 

Finally, down here for the heart count, same thing. We can use this Arrow figure arrow number between five and 100. So a couple of simple things that make life easier to make sure it works so far. Let's go back and run bin Console doctrine. Colon fixtures, cooling load. 

Yeah. 

Yes. Awesome. No errors back on the browser. We can refresh and it's still works. Of course, the big problem is that the title is always the same. The author is always the same and the article image is also the same as you saw. Faker has methods that can generate random names, random titles, even things related to random images that will give you full you where else and we could use those, but I kind of want to keep the images, the names, the articles, and even the authors a little bit more realistic for our application. 

So here's what I'm going to do. Go back to article fixtures at the top. I'm actually going to paste in a couple of static properties. What I've done here is I've created three realistic article titles, three article images that exists in two realistic article authors, so instead of making completely random authors, images, titles, images, and authors, we're going to randomly choose from that list and faker even makes this easy, so our under under that title, we can say this Arrow, faker, arrow, random element, and just pass itself, Colin Colin article titles. We'll let it do the work of pulling out random element down here for set slug. We could continue to do this, but we can also say this Arrow, faker air arrow slug. The slug will be different than the article title, but honestly, who cares? And finally down here for the author will do the same thing. This arrow figure arrow, random elements. Pass that the article authors, and I'll copy that and we'll do the same thing for the image file. Name this time using article images. OK, awesome. All right, let's read. Let our fixtures one more time. No heirs 

and refreshed. 

Oh, so much better. 

OK. 

Random article titles, random images in random names for us to actually play with. This seems like a small step, but having rich fake data that you can load in one command is going to increase the velocity that you create new features, so take advantage of it.