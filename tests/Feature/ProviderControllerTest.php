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

    /**
     * Test '/api/provider'
     *
     * @return void
     * @dataProvider searchDataProvider
     */
    public function testIndexSearch(array $search, array $expect)
    {
        $this->post('/api/provider', ['first_name' => 'John', 'last_name' => 'Smith']);
        $this->post('/api/provider', ['first_name' => 'Adam', 'last_name' => 'Smith']);
        $this->post('/api/provider', ['first_name' => 'Robert', 'last_name' => 'Adams']);
        $this->post('/api/provider', ['first_name' => 'John', 'last_name' => 'Doe']);


        $response = $this->getJson('/api/provider?'.http_build_query($search));

        $response->assertStatus(200);

        $response->assertJsonFragment($expect['fragment']);
    }

    public function searchDataProvider(): array {
        return [
            'first_name => jo' => [
                ['first_name' => 'jo'],
                [
                    'fragment' => [
                        'last_name' => 'Smith',
                        'last_name' => 'Doe',
                        'total' => 2
                    ]
                ]
            ],
            'last_name => ith' => [
                ['last_name' => 'ith'],
                [
                    'fragment' => [
                        'first_name' => 'John',
                        'first_name' => 'Adam',
                        'total' => 2
                    ]
                ]
            ],
            'first_name=> ad, last_name => ith' => [
                ['first_name' => 'ad', 'last_name' => 'ith'],
                [
                    'fragment' => [
                        'first_name' => 'Adam',
                        'last_name' => 'Smith',
                        'total' => 1
                    ]
                ]
            ],
        ];
    }
}
