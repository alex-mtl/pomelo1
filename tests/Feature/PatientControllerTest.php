<?php

namespace Tests\Feature;

use Tests\ApiTestCase;

class PatientControllerTest extends ApiTestCase
{
    /**
     * Test POST new patient
     *
     * @return void
     */
    public function testPostPatient()
    {
        $response = $this->postJson('/api/patient', ['first_name' => 'John', 'last_name' => 'Smith']);
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
     * Test PUT patient
     *
     * @return void
     * @dataProvider putPatientProvider
     */
    public function testPutPatient(array $put, array $expect)
    {
        $response = $this->postJson('/api/patient', ['first_name' => 'John', 'last_name' => 'Smith']);
        $id = $response->json()['data']['id'];
        $response = $this->putJson('/api/patient/'.$id, $put);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'first_name',
                'last_name'
            ]
        ]);

        $response->assertJsonFragment($expect);
    }

    public function putPatientProvider(): array {
        return [
            'first_name => William' => [
                ['first_name' => 'William'],
                ['first_name' => 'William', 'last_name' => 'Smith']
            ],
            'last_name => Adams' => [
                ['last_name' => 'Adams'],
                ['first_name' => 'John', 'last_name' => 'Adams']
            ],
            'first_name => Juan, last_name => Rodriguez' => [
                ['first_name' => 'Juan', 'last_name' => 'Rodriguez'],
                ['first_name' => 'Juan', 'last_name' => 'Rodriguez']
            ],
        ];
    }

    /**
     * Test POST duplicate patient fails with 409 conflict
     */
    public function testPostPatientDuplicate()
    {
        $response = $this->postJson('/api/patient', ['first_name' => 'John', 'last_name' => 'Smith']);
        $response = $this->postJson('/api/patient', ['first_name' => 'John', 'last_name' => 'Smith']);
        $response->assertStatus(409);
    }

    /**
     * Test GET patient by $id
     */
    public function testGetPatient()
    {
        $response = $this->postJson('/api/patient', ['first_name' => 'John', 'last_name' => 'Smith']);
        $id = $response->json()['data']['id'];
        $response = $this->getJson('/api/patient/'.$id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'first_name',
                'last_name'
            ]
        ]);
    }

    /**
     * Test GET patient by wrong $id fails with 404 Not found
     */
    public function testGetPatient404()
    {
        $response = $this->getJson('/api/patient/1000');

        $response->assertStatus(404);
        $response->assertJsonFragment([
            'message' => 'Not Found',
            'status' => 404
        ]);
    }

    /**
     * Test '/api/patient'
     *
     * @return void
     */
    public function testIndex()
    {
        $this->postJson('/api/patient', ['first_name' => 'John', 'last_name' => 'Smith']);
        $response = $this->getJson('/api/patient');

        $response->assertStatus(200);

        $body = $response->json();

        $this->assertEquals(1, count($body['data'] ?? []));
        $this->assertEquals('John', $body['data'][0]['first_name'] ?? '');
        $this->assertEquals('Smith', $body['data'][0]['last_name'] ?? '');
    }
}
