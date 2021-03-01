<?php

namespace Tests\Feature;

use Tests\ApiTestCase;

class AppointmentControllerTest extends ApiTestCase
{
    /**
     * Test POST book an appointment
     *
     * @return void
     * @dataProvider postAppointmentProvider
     */
    public function testPostAppointment(array $prepare, array $expect)
    {
        if ($prepare['provider'] ?? false) {
            $provider = $this->postJson( '/api/provider', $prepare['provider'])['data'];
        }

        if ($prepare['slot']['provider_id'] ?? false) {
            $prepare['slot']['provider_id'] = $provider['id'];
        }

        if ($prepare['patient'] ?? false) {
            $patient = $this->postJson( '/api/patient', $prepare['patient'])['data'];
        }

        $availability = $this->postJson( '/api/availability', $prepare['slot'])['data'];

        if ($prepare['slot']['patient_id'] ?? false) {
            $availability['patient_id'] = $patient['id'];
        }

        $response = $this->postJson( '/api/appointment', array_intersect_key($availability, $prepare['book']));

        $response->assertStatus($expect['status']);
        $response->assertJsonStructure($expect['structure']);

        if ($expect['fragment'] ?? false) {
            $response->assertJsonFragment($expect['fragment']);
        }
    }

    public function postAppointmentProvider(): array {
        return [
            'success with slot id' => [
                [
                    'provider' => ['first_name' => 'Adam', 'last_name' => 'Smith'],
                    'patient' => ['first_name' => 'John', 'last_name' => 'Doe'],
                    'slot' => [
                        'provider_id' => true,
                        'patient_id' => true,
                        'slot_start' => '2021-02-28 21:00:00',
                        'slot_end' => '2021-02-28 21:15:00'
                    ],
                    'book' => [
                        'id' => true,
                        'patient_id' => true
                    ]
                ],
                [
                    'status' => 201,
                    'structure' => [
                        'data' => [
                            'id',
                            'provider_id',
                            'patient_id',
                            'available',
                            'slot_start',
                            'slot_end',
                        ]
                    ],
                    'fragment' => [
                        'available' => false,
                        'slot_start' => '2021-02-28 21:00:00',
                        'slot_end' => '2021-02-28 21:14:00'
                    ]
                ]
            ],
            'success with provider_id, slot_start, slot_end' => [
                [
                    'provider' => ['first_name' => 'Adam', 'last_name' => 'Smith'],
                    'patient' => ['first_name' => 'John', 'last_name' => 'Doe'],
                    'slot' => [
                        'provider_id' => true,
                        'patient_id' => true,
                        'slot_start' => '2021-02-28 21:00:00',
                        'slot_end' => '2021-02-28 21:15:00'
                    ],
                    'book' => [
                        'patient_id' => true,
                        'provider_id' => true,
                        'slot_start' => true,
                        'slot_end' => true
                    ]
                ],
                [
                    'status' => 201,
                    'structure' => [
                        'data' => [
                            'id',
                            'provider_id',
                            'patient_id',
                            'available',
                            'slot_start',
                            'slot_end',
                        ]
                    ],
                    'fragment' => [
                        'available' => false,
                        'slot_start' => '2021-02-28 21:00:00',
                        'slot_end' => '2021-02-28 21:14:00'
                    ]
                ]
            ],
            'missing patient_id' => [
                [
                    'provider' => ['first_name' => 'Adam', 'last_name' => 'Smith'],
                    'patient' => ['first_name' => 'John', 'last_name' => 'Doe'],
                    'slot' => [
                        'provider_id' => true,
                        'slot_start' => '2021-02-28 21:00:00',
                        'slot_end' => '2021-02-28 21:15:00'
                    ],
                    'book' => [
                        'id' => true,
                        'patient_id' => true
                    ]
                ],
                [
                    'status' => 422,
                    'structure' => [
                        'errors' => [
                            'patient_id'
                        ],
                        'message',
                        'status'
                    ],
                    'fragment' => [
                        "patient_id" =>["The patient id field is required."]
                    ]
                ]
            ],
        ];
    }
}
