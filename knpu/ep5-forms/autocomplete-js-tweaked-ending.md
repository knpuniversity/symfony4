# Autocomplete Js Tweaked Ending

Coming soon...

So I'll copy that role and in our `AdminUtilityController` above my method I'm going
to add add `isGranted()` and we'll use that same thing. Now that is going to be a slight
problem because in `ArticleAdminController`, you can edit an `Article` to edit the
article. We're using this special security `Voter` rule which actually allows non
admins to edit articles if they are the author. So it's possible right now that you
could be the author of an article and you actually won't be able to hit this Ajax end
point. We're going to fix that later by actually disabling this field in the edit
mode so that it needs to be set when it's created and that will be done by an admin.
Then it will be modified later. So it's going to be an issue that will fix in the
future.