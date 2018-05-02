# Owning Vs Inverse

Coming soon...

We need to talk about a very, very important in maybe most confusing part of doctrine relations, so stay with me, go into your. It's called owning versus size of the relationship and it's a concept that deals with the fact that you can look at a relationship always from two different directions. You can either look at the comment and say that this has a one article, this is many to one to article, or you can go to article and for that same one relationship you can say that this article has is one to many two comments. So what's confusing about that article fixtures? The question is can you set the relationship from both sides? 

Yeah, 

you can definitely read the relationship on both sides. You could say comment and get article or article. Arrow get comments. Question is, can you set it from both sides? We've proven that we can say comment Arrow set article and that will save in the database. So now I'm going to come at those out and instead I'm going to say, 

OK, 

article Arrow, add a comment, comment one, article, Arrow, comment, comment too. So I'm adding the comments to the collection on article. By the way, don't worry that this comes after the persist. That's actually fine. As long as these come before the flush. All right, so let's try it. Let's move over and run bin Console doctrine. Colin fixtures. Colin load. 

Yeah. 

Yes. No heirs. 

If we run bin Console doctrine, query sql, select star from comments. Yeah, you can see that behind the scenes it is still creating common objects and they are related to their specific article. So it seems that the question, the answer is yes. You can read and write a relationship from both sides, but actually that's wrong. See this add comments method that lives in article. I'm gonna, hold, command or control and click that method to jump into article. Now, look closely, this is code that was generated for us by the make entity command. What it does here is it checks to make sure that it checks to see if the comment is not already in the comments array, to avoid adding it twice. If it's not, this comment is not all related already related to this article. It adds it to our comments collection and then it calls comment Arrow set article. This is called synchronizing the other side of the relationship. It is making sure that this comment is comments. Article is set to this article. 

Yeah, 

so by calling add comments, we're actually setting both sides of the relationship, so I want you to come and outcome and Arrow set article for a second. Ben, go back, reload the fixtures again and they fail. They fail because they say they're trying to set the comment with article ID is at. No, basically it's not setting up the relationship correctly. So here is the key thing. Every relationship has two sides. One side is known as the owning side of the relation and the other is known as the inverse side of the relationship in a many to one or one to many relationship. I'll scroll back up to the top here. The owning side is always the [inaudible] side and it's easy to remember because the owning side is the side where the actual column appears in the database. So the comment has an article ID column in the database is the owning side. So then the article is the inverse side. The reason this is important is that when you set a data, when you set, when you relate entities together in save doctrine only looks at the owning side of the relationship to figure out what to do. So in this case, we're actually setting the inverse side via add comments. 

Yeah. 

And we're only setting the inverse side. So when it saves, it actually looks at the owning side. It looks at this comments object, sees the article, property is no them and therefore tries to save a comment what they know article. So the owning side is the only one where the data matters for setting purposes. In fact, the whole purpose of the inverse side of the relationship is just convenience. It's just there because it's handy for us to be able to say article, get comments, and quickly get those comments back. That was really handy for us because we're using it inside of our template. Heck, the inverse side is actually optional. Asked us if we wanted to generate the inverse, this side of the relationship, when we set up the relationship, we could actually delete all the comments stuff from article and the relationship would still exist in the database and we could still use it. Sure. We wouldn't have our shortcut anymore of article. Get comments, but everything else will work just fine. 

So I'm telling you this because this can be a big w t, f moment if you start trying to relate things to together and it's not saving the database the way you think it should. I'm gonna uncomment my comment set article method. When you use the generator, it takes care of almost all of this for you because it synchronizes the owning side of the relationship when you set the inverse side so it effectively allows you to get away with this, but I want you to be aware of it in case you ever get in a situation and something is not acting correctly. You need to think about the owning side of the relationship. 

Yeah. 

Later when we talk about many to many relationships, we'll talk about how you determine which side is the owning side. They're all right, so now that we've talked about that, let's refactor our code and our fixtures back. We are going to set the owning side via comment aerostat article, but we know that thanks to the code that was generated by the maker and bundle. We can also set the inverse side because it sets the owning side for us. Few that didn't make complete sense. Don't worry too much. Just file that away in the back of your mind. You may never need to worry about it, but if you ever come up with weird behavior, then you might need to look back up. The screencast.