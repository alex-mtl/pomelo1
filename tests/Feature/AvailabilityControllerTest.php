<?php

namespace Tests\Feature;

use Tests\ApiTestCase;

class AvailabilityControllerTest extends ApiTestCase
{
    /**
     * Test POST new availability
     *
     * @return void
     * @dataProvider postAvailabilityProvider
     */
    public function testPostAvailability(array $prepare, array $expect)
    {
        if ($prepare['provider'] ?? false) {
            $provider = $this->postJson( '/api/provider', $prepare['provider'])['data'];
        }

        if ($prepare['slot']['provider_id'] ?? false) {
            $prepare['slot']['provider_id'] = $provider['id'];
        }

        $response = $this->postJson( '/api/availability', $prepare['slot']);
        $response->assertStatus($expect['status']);
        $response->assertJsonStructure($expect['structure']);

        if ($expect['fragment'] ?? false) {
            $response->assertJsonFragment($expect['fragment']);
        }
    }

    public function postAvailabilityProvider(): array {
        return [
            'normal' => [
                [
                    'provider' => ['first_name' => 'Adam', 'last_name' => 'Smith'],
                    'slot' => [
                        'provider_id' => true,
                        'slot_start' => '2021-02-28 21:00:00',
                        'slot_end' => '2021-02-28 21:15:00'
                    ],
                ],
                [
                    'status' => 201,
                    'structure' => [
                        'data' => [
                            'id',
                            'provider_id',
                            'available',
                            'slot_start',
                            'slot_end',
                        ]
                    ],
                    'fragment' => [
                        'available' => true,
                        'slot_start' => '2021-02-28 21:00:00',
                        'slot_end' => '2021-02-28 21:14:00'
                    ]
                ]
            ],
        ];
    }

    /**
     * Test massive slot post availability/provide
     *
     * @return void
     * @dataProvider provideAvailabilityProvider
     */
    public function testProvideAvailability(array $prepare, array $expect)
    {
        if ($prepare['provider'] ?? false) {
            $provider = $this->postJson( '/api/provider', $prepare['provider'])['data'];
        }

        if ($prepare['slot']['provider_id'] ?? false) {
            $prepare['slot']['provider_id'] = $provider['id'];
        }

        $response = $this->postJson( '/api/availability/provide', $prepare['slot']);
        $response->assertStatus($expect['status']);
        $response->assertJsonStructure($expect['structure']);

        $this->assertEquals($expect['slotsCount'], count($response->json()['data']));
        if ($expect['fragment'] ?? false) {
            $response->assertJsonFragment($expect['fragment']);
        }
    }

    public function provideAvailabilityProvider(): array {
        return [
            'post massive' => [
                [
                    'provider' => ['first_name' => 'Adam', 'last_name' => 'Smith'],
                    'slot' => [
                        'provider_id' => true,
                        'slot_start' => '2021-02-28 21:00:00',
                        'slot_end' => '2021-02-28 22:15:00'
                    ],
                ],
                [
                    'status' => 200,
                    'structure' => [
                        'data' => [
                            [
                                'id',
                                'provider_id',
                                'available',
                                'slot_start',
                                'slot_end',
                            ]
                        ]
                    ],
                    'fragment' => [
                        'available' => true,
                        'slot_start' => '2021-02-28 21:00:00',
                        'slot_end' => '2021-02-28 21:14:00',
                        'slot_start' => '2021-02-28 22:00:00',
                        'slot_end' => '2021-02-28 22:14:00'
                    ],
                    'slotsCount' => 5
                ]
            ],
        ];
    }
}
