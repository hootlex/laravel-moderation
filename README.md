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

##Installation

First, install the package through Composer.

```php
composer require hootlex/laravel-moderation
```

Then include the service provider inside `config/app.php`.

```php
'providers' => [
    ...
    Hootlex\Friendships\ModerationServiceProvider::class,
    ...
];
```
Lastly you publish the config file.

```
php artisan vendor:publish --provider="Hootlex\Friendships\ModerationServiceProvider" --tag=config
```


## Setup the Model(s)

To enable moderation for a model, use the `Hootlex\Moderation\Moderatable` trait on the model and add the `status` and `moderated_at` columns to your model's table.
```php
use Hootlex\Moderation\Moderatable;
class Post extends Model
{
    use Moderatable;
    ...
}
```

Create a migration to add the 2 new columns. [(You can use custom names for the moderation columns)](#configuration)

Example Migration:
```php
class AddModeratioColumnsToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->smallInteger('status')->default(0);
            $table->dateTime('moderated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function(Blueprint $table)
        {
            $table->dropColumn('status');
            $table->dropColumn('moderated_at');
        });
    }
}
```

**You are ready to go!**
