# Query Logic Re-use & Shortcuts

Coming soon...

One of my favorite things about the query builder is that it allows you to reuse query logic, so for example, right now we only have one custom method in here, but eventually we might have more custom methods. This is a really good chance that we're going to want to reuse the logic to determine whether or not an article that's published across many methods. So to do that we can actually isolate just this part of the query logic into a new private function. For example, go down to the bottom and great private function add is published query builder an a and give us an argument, a querybuilder type end, the one from the doctrine or m Qb, 

OK, 

the idea is 

OK, 

that is that now we can go up, copy that part of the queries and just return qb Arrow and wear a. That polished is not known and we're returning, so that itself returns a querybuilder. Thinks this above. We can now say qb equals this Arrow, create querybuilder. Then below that we can say return. This arrow add is published querybuilder and pass it the qb. Alright, so if you look at this, we first create the career builder, we then allow an external method to modify that query builder and then we keep chanting off of it and ultimately return the result. One important thing here is that you need to consistently use these same alias here across all of your methods, 

so it's super simple, but it's a really nice trick, but you can actually get even a little bit fancier. Create another private method called get or create querybuilder. Give this a querybuilder argument just like before, but make it optional. The idea is that something can call this method and if the query builder is passed, we'll just return it. Otherwise we will create the querybuilder so we can say return huber the question Mark Colon. This arrow create querybuilder a. The syntax is not very common, so it means that if a querybuilder object is past return, that careerbuilder object. If a querybuilder object is not passed, then create one. This is cool because we can now make the argument to the AD is published querybuilder, also optional and instead we can just say return this Arrow, get or create querybuilder, passing it the and were a that published that is not known and thanks to this we can finally simplify on top and just say return. This arrow add is published querybuilder. So when we call this method, we're not passing a querybuilder, so it creates calls. This method to create the query builder and then we modify it. A little bit of clever coding there, but it means that we can just very easily change. We don't have to worry about creating the querybuilder up here. We can just start chatting and things just work. So let's make sure it works. We've over refreshed. Yes, still looks good. OK. One other very important shortcut trick that I absolutely love and it's this 

go to article controller and go down to show. So it's pretty common. Sometimes you want to query for an array of objects. So we get the repository, we call some method on it. Life is good, but it's also really common for us to want to query for just one object, like we use the slug and we find one article object. When you're queering for just one entity object, there's a shortcut you can use in your controller. 

Yeah, 

remove the slug arguments and replace it with a type of article and then article. Then I below instead, since there's no slug argument, I'll now say article Aeros aero get slug, and then I'm going to remove all of the code that does the query and the code that does the [inaudible]. 

All right, 

before I explain that, if you move over clicking on one of the articles. Yeah, that totally works. Whoa. So here's the deal. We already know that if you type hint, a service in symphony is going to pass you that service. In fact, now we can get rid of the entity manager service. If you type in an entity and symphony will attempt to automatically query for that entity for you, how does it do that? Well, it looks at all of the place holders in your route. In this case, we just have one place holder called slug. 

Yeah. 

If slug is a property on your SD, then it queries for the article where these slug property matches whatever value that is. In other words, it does the exact same querie that we were doing before and if there's not a slug that matches that, it automatically throws a four, four before our controller is ever called. 

OK, 

so for example, if we go over here, 

yeah, 

put in a bad slug, you'll get an error about article object, not fine found by the APP ran cover annotation. This is a little misleading that pram converters, the name of this feature internally. This is one of the reasons why I really, other than clarity, this is one of the reasons why I really like to make my wildcards match the property in my entity that corresponds to it. If you do that, you can just get free queries. If you have a more complex queries, then that's fine. You don't need to use the shortcut. Just back up, passing the article repository and then call what other whatever method you want to get your one article object. Yeah.