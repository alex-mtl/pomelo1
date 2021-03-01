<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\ApiTestCase;

class ProviderControllerTestTest extends ApiTestCase
{
    /**
     * Test POST new provider
     *
     * @return void
     */
    public function testPostProvider()
    {
        $response = $this->postJson('/api/provider', ['first_name' => 'John', 'last_name' => 'Smith']);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'first_name',
                'last_name'
            ]
        ]);

        $response->assertJsonFragment(['first_name' => 'John', 'last_name' => 'Smith']);
    }

    /**
     * Test POST duplicate provider fails with 409 conflict
     */
    public function testPostProviderDuplicate()
    {
        $response = $this->postJson('/api/provider', ['first_name' => 'John', 'last_name' => 'Smith']);
        $response = $this->postJson('/api/provider', ['first_name' => 'John', 'last_name' => 'Smith']);
        $response->assertStatus(409);
    }

    /**
     * Test '/api/provider'
     *
     * @return void
     */
    public function testIndex()
    {
        $this->post('/api/provider', ['first_name' => 'John', 'last_name' => 'Smith']);
        $response = $this->getJson('/api/provider');

        $response->assertStatus(200);

        $body = $response->json();

        $this->assertEquals(1, count($body['data'] ?? []));
        $this->assertEquals('John', $body['data'][0]['first_name']);
        $this->assertEquals('Smith', $body['data'][0]['last_name']);
    }
}
