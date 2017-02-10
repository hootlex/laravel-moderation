<?php

use Hootlex\Moderation\Status;
use Hootlex\Moderation\Tests\Post;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class ModerationTraitTest extends BaseTestCase
{
    use DatabaseTransactions;

    protected $status_column;
    protected $moderated_at_column;

    public function setUp()
    {
        parent::setUp();

        $this->status_column = 'status';
        $this->moderated_at_column = 'moderated_at';

        Post::$strictModeration = true;
    }

    /** @test */
    public function it_returns_only_rejected_stories()
    {
        $this->createPost([$this->status_column => Status::REJECTED], 5);

        $posts = Post::rejected()->get();

        $this->assertNotEmpty($posts);

        foreach ($posts as $post) {
            $this->assertEquals(Status::REJECTED, $post->{$this->status_column});
        }
    }

    /** @test */
    public function it_returns_only_pending_stories()
    {
        $this->createPost([$this->status_column => Status::PENDING], 5);

        $posts = Post::pending()->get();

        $this->assertNotEmpty($posts);

        foreach ($posts as $post) {
            $this->assertEquals(Status::PENDING, $post->status);
        }
    }

    /** @test */
    public function it_returns_only_postponed_stories()
    {
        $this->createPost([$this->status_column => Status::POSTPONED], 5);

        $posts = Post::postponed()->get();

        $this->assertNotEmpty($posts);

        foreach ($posts as $post) {
            $this->assertEquals(Status::POSTPONED, $post->status);
        }
    }

    /** @test */
    public function it_approves_a_story_by_id()
    {
        $post = $this->createPost([$this->status_column => Status::PENDING]);

        Post::approve($post->id);

        $this->assertDatabaseHas('posts',
            ['id' => $post->id, $this->status_column => Status::APPROVED, $this->moderated_at_column => \Carbon\Carbon::now()]);
    }

    /** @test */
    public function it_rejects_a_story_by_id()
    {
        $post = $this->createPost([$this->status_column => Status::PENDING]);

        Post::reject($post->id);

        $this->assertDatabaseHas('posts',
            ['id' => $post->id, $this->status_column => Status::REJECTED, $this->moderated_at_column => \Carbon\Carbon::now()]);
    }

    /** @test */
    public function it_postpones_a_story_by_id()
    {
        $post = $this->createPost([$this->status_column => Status::PENDING]);

        Post::postpone($post->id);

        $this->assertDatabaseHas('posts',
            ['id' => $post->id, $this->status_column => Status::POSTPONED, $this->moderated_at_column => \Carbon\Carbon::now()]);
    }

    /** @test */
    public function it_pendings_a_story_by_id()
    {
        $post = $this->createPost([$this->status_column => Status::APPROVED]);

        Post::pend($post->id);

        $this->assertDatabaseHas('posts',
            ['id' => $post->id, $this->status_column => Status::PENDING, $this->moderated_at_column => \Carbon\Carbon::now()]);
    }

    /** @test */
    public function it_determines_if_story_is_approved()
    {
        $postApproved = $this->createPost([$this->status_column => Status::APPROVED]);
        $postPending = $this->createPost([$this->status_column => Status::PENDING]);
        $postRejected = $this->createPost([$this->status_column => Status::REJECTED]);

        $this->assertTrue($postApproved->isApproved());
        $this->assertFalse($postPending->isApproved());
        $this->assertFalse($postRejected->isApproved());
    }

    /** @test */
    public function it_determines_if_story_is_rejected()
    {
        $postApproved = $this->createPost([$this->status_column => Status::APPROVED]);
        $postPending = $this->createPost([$this->status_column => Status::PENDING]);
        $postRejected = $this->createPost([$this->status_column => Status::REJECTED]);

        $this->assertFalse($postApproved->isRejected());
        $this->assertFalse($postPending->isRejected());
        $this->assertTrue($postRejected->isRejected());
    }

    /** @test */
    public function it_determines_if_story_is_pending()
    {
        $postApproved = $this->createPost([$this->status_column => Status::APPROVED]);
        $postPending = $this->createPost([$this->status_column => Status::PENDING]);
        $postRejected = $this->createPost([$this->status_column => Status::REJECTED]);

        $this->assertFalse($postApproved->isPending());
        $this->assertTrue($postPending->isPending());
        $this->assertFalse($postRejected->isPending());
    }

    /** @test */
    public function it_determines_if_story_is_postponed()
    {
        $postApproved = $this->createPost([$this->status_column => Status::APPROVED]);
        $postPending = $this->createPost([$this->status_column => Status::PENDING]);
        $postRejected = $this->createPost([$this->status_column => Status::REJECTED]);
        $postPostponed = $this->createPost([$this->status_column => Status::POSTPONED]);

        $this->assertFalse($postApproved->isPostponed());
        $this->assertFalse($postPending->isPostponed());
        $this->assertFalse($postRejected->isPostponed());
        $this->assertTrue($postPostponed->isPostponed());
    }

    /** @test */
    public function it_casts_moderated_at_attribute_as_a_date(){
        $post = $this->createPost();
        Post::approve($post->id);

        //reload the instance
        $post = Post::find($post->id);

        $this->assertInstanceOf(\Carbon\Carbon::class, $post->{$this->moderated_at_column});
    }

    /** @test */
    public function it_deletes_rejected_resources(){
        $post = $this->createPost([$this->status_column => Status::REJECTED]);

        $postDel = Post::withRejected()->where('id', $post->id)->first();
        $postDel->delete();

        $this->assertDatabaseMissing('posts',['id' => $post->id]);
    }

    /** @test */
    public function it_deletes_resources_of_any_status(){
        $posts = $this->createPost([], 4);
        Post::approve($posts[0]->id);
        Post::reject($posts[1]->id);
        Post::postpone($posts[2]->id);

        foreach ($posts as $post) {
            $post->delete();
        }

        $this->assertDatabaseMissing('posts',['id' => $posts[0]->id]);
        $this->assertDatabaseMissing('posts',['id' => $posts[1]->id]);
        $this->assertDatabaseMissing('posts',['id' => $posts[2]->id]);
    }

    /** @test */
    public function it_marks_as_approved_an_instance()
    {
        $post = $this->createPost([$this->status_column => Status::PENDING]);

        $post->markApproved();

        $this->assertEquals(Status::APPROVED, $post->status);

        $this->assertDatabaseHas('posts',
            ['id' => $post->id, $this->status_column => Status::APPROVED, $this->moderated_at_column => \Carbon\Carbon::now()]);
    }

    /** @test */
    public function it_marks_as_rejected_an_instance()
    {
        $post = $this->createPost([$this->status_column => Status::PENDING]);

        $post->markRejected();

        $this->assertEquals(Status::REJECTED, $post->status);

        $this->assertDatabaseHas('posts',
            ['id' => $post->id, $this->status_column => Status::REJECTED, $this->moderated_at_column => \Carbon\Carbon::now()]);
    }

    /** @test */
    public function it_marks_as_postponed_an_instance()
    {
        $post = $this->createPost([$this->status_column => Status::PENDING]);

        $post->markPostponed();

        $this->assertEquals(Status::POSTPONED, $post->status);

        $this->assertDatabaseHas('posts',
            ['id' => $post->id, $this->status_column => Status::POSTPONED, $this->moderated_at_column => \Carbon\Carbon::now()]);
    }

    /** @test */
    public function it_marks_as_pending_an_instance()
    {
        $post = $this->createPost([$this->status_column => Status::PENDING]);

        $post->markPending();

        $this->assertEquals(Status::PENDING, $post->status);

        $this->assertDatabaseHas('posts',
            ['id' => $post->id, $this->status_column => Status::PENDING, $this->moderated_at_column => \Carbon\Carbon::now()]);
    }

}
