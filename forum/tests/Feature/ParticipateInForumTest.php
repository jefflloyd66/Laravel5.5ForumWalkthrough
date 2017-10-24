<?php

namespace Tests\Feature;

use Illuminate\Routing\Route;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForumTest extends TestCase
{
    protected $thread;

    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn($user = factory('App\User')->create());
        $reply = factory('App\Reply')->make();

        $this->post($this->thread->path() . '/replies', $reply->toArray());

        $this->get($this->thread->path())
            ->assertSee(e($reply->body));
    }

    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {
        $response = $this->post('/threads/channel/1/replies', []);

        $response->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_sees_a_reply_form()
    {
        $this->signIn($user = factory('App\User')->create());

        $reponse = $this->get($this->thread->path());

        $reponse->assertSee('id="reply_form"');
    }

    /** @test */
    public function unauthenticated_user_does_not_see_reply_form()
    {
        $response = $this->get($this->thread->path());

        $response->assertDontSee('id="reply_form"');
        $response->assertSee('Please <a href="'.route('login').'">sign in</a>');
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->signIn($user = factory('App\User')->create());

        $reply = factory('App\Reply')->make(['body' => null]);

        $response = $this->post($this->thread->path() . '/replies', $reply->toArray());
        $response->assertSessionHasErrors('body');
    }
}
