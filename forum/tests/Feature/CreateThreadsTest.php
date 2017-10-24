<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());

        $postedThread = Thread::first();

        $response = $this->get($postedThread->path());

        $response->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    public function guests_may_not_create_threads()
    {
        $thread = make('App\Thread');
        $response = $this->post('/threads', $thread->toArray());
        $response->assertRedirect('/login');
    }

    /** @test */
    public function guests_may_not_view_create_threads_form()
    {
        $this->get('/threads/create')
            ->assertRedirect('/login');
    }
    
    /** @test */
    public function a_thread_requires_a_title()
    {
        $response = $this->publishThread(['title' => null]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $response = $this->publishThread(['body' => null]);

        $response->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel',2)->create();

        $response = $this->publishThread(['channel_id' => null]);
        $response->assertSessionHasErrors('channel_id');

        $response = $this->publishThread(['channel_id' => 9999]);
        $response->assertSessionHasErrors('channel_id');
    }

    /**
     * @param $overrides
     * @return TestResponse
     */
    protected function publishThread($overrides): TestResponse
    {
        $this->signIn();

        $thread = factory('App\Thread')->make($overrides);

        return $this->post('/threads', $thread->toArray());
    }
}
