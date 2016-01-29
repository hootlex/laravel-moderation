<?php

namespace Hootlex\Moderation\Tests;

use Hootlex\Moderation\Moderatable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Moderatable;

    protected $table = 'posts';

    public static $strictModeration = true;

    protected $fillable = ['moderated_at', 'status'];
}
