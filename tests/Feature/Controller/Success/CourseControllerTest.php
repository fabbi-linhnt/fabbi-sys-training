<?php

namespace Tests\Feature\Controller\Success;

use App\Enums\ResponseStatusCode;
use Tests\TestCase;

class CourseControllerTest extends TestCase
{
    public function test_list_course()
    {
        $email = 'nam@gmail.com';
        $password = '123456';
        $response = $this->post('api/auth/login', ['email' => $email, 'password' => $password])
            ->assertStatus(ResponseStatusCode::OK)
            ->assertJsonStructure(['token_type', 'expires_in', 'access_token']);
        $perPage = 2;
        $name = '';
        $courses = $this->get("/api/courses?perPage=$perPage&name=$name", [
            'headers' => [
                'Authorization' => 'Bearer ' . $response->getData()->access_token,
                'Accept' => 'application/json',
            ],
        ]);
        $courses->assertStatus(ResponseStatusCode::OK);
    }

    public function test_create_course()
    {
        $email = 'nam@gmail.com';
        $password = '123456';
        $response = $this->post('api/auth/login', ['email' => $email, 'password' => $password])
            ->assertStatus(ResponseStatusCode::OK)
            ->assertJsonStructure(['token_type', 'expires_in', 'access_token']);

        $this->post("/api/courses",
            [
                'name' => "test",
                'description' => "test",
                'is_active' => 1,
                'category_id' => 4,
                'path' => "xyz.com"
            ],
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $response->getData()->access_token,
                    'Accept' => 'application/json',
                ],
            ])
            ->assertStatus(ResponseStatusCode::OK)
            ->assertJson(['data' => true]);
    }
}
