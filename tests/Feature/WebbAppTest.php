<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebbAppTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHomePageTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Pomelo');
        $response->assertDontSee('Laravel');
    }
}
