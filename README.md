# Laravel Moderation
A simple Moderation System for Laravel 5.* that allows you to Approve or Reject resources like posts, comments, users, etc.

Keep your application pure by preventing offensive irrelevant, obscene, or insulting content.

##Possible Use Case

1. User creates a resource (a post, a comment or any Eloquent Model).
2. The resource is pending and invisible in website (ex. `Post::all()` returns only approved posts).
3. Admin/Moderator/etc decides if the resource will be approved or rejected.

  1. **Approved**: Resource is now public and queryable.
  2. **Rejected**: Resource will be excluded from all queries. Rejected resources will be returned only if you scope a query to include them. (scope: `withRejected`)

4. You application is clean.
