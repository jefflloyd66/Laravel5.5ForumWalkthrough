<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NavbarTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function nav_bar_should_have_link_to_all_threads()
    {
        $response = $this->get('/threads');

        $response->assertSee('<a href="/threads">All Threads</a>');
    }
}
