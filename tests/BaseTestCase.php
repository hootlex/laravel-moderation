<?php

use Hootlex\Moderation\Tests\Post;
use Tests\TestCase;


abstract class BaseTestCase extends TestCase
{
    /**
     * @param array $overrides
     * @param int $amount
     *
     * @return \Hootlex\Moderation\Tests\Post
     */
    function createPost($overrides = [], $amount = 1)
    {
        $posts = new \Illuminate\Database\Eloquent\Collection;
        for ($i = 0; $i < $amount; $i++) {
            $post = Post::create(array_merge(['moderated_at' => \Carbon\Carbon::now()], $overrides));
            $posts->push($post);
        }

        return (count($posts) > 1) ? $posts : $posts[0];
    }


    function actingAsUser()
    {
        return $this->actingAs(\App\User::create(['name' => 'tester', 'email' => mt_rand(1,9999).'tester@test.com', 'password' => 'password']));
    }
}
