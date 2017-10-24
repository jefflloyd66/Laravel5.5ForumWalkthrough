<?php

namespace Tests\Feature;

use App\Thread;
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
}
