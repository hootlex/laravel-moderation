<?php

namespace Hootlex\Moderation\Tests;

use Hootlex\Moderation\Moderation;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Moderation;

    protected $table = 'posts';

    public static $strictModeration = true;

    protected $fillable = ['moderated_at', 'status'];
}
