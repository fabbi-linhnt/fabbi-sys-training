<?php

namespace Tests\Feature\Controller\Error;

use App\Enums\ResponseStatusCode;
use Tests\TestCase;

class CourseControllerTest extends TestCase
{
    protected $token;

    public function setUp(): void
    {
        parent::setUp();
        $email = 'nam@gmail.com';
        $password = '123456';
        $response = $this->post('api/auth/login', ['email' => $email, 'password' => $password])
            ->assertStatus(ResponseStatusCode::OK)
            ->assertJsonStructure(['token_type', 'expires_in', 'access_token']);
        $this->token = $response->getData()->access_token;
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_list_course_with_perPage_negative_error()
    {
        $perPage = "-5";
        $name = '';
        $courses = $this->get("/api/courses?perPage=$perPage&name=$name", [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Accept' => 'application/json',
            ],
        ]);
        $this->assertEquals(ResponseStatusCode::INTERNAL_SERVER_ERROR, $courses->getData()->error->code);
    }

    public function test_create_course_without_name_error()
    {
        $course = $this->post("/api/courses",
            [
                'name' => null,
                'description' => 'test',
                'is_active' => 1,
                'category_id' => 2,
                'path' => 'xyz.com'
            ],
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Accept' => 'application/json',
                ],
            ]);
        $course->assertStatus(ResponseStatusCode::INTERNAL_SERVER_ERROR);
    }

    public function test_create_course_with_category_id_is_negative_error()
    {
        $course = $this->post("/api/courses",
            [
                'name' => 'lorem as',
                'description' => 'test',
                'is_active' => 1,
                'category_id' => -2,
                'path' => 'xyz.com'
            ],
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Accept' => 'application/json',
                ],
            ]);
        $course->assertStatus(ResponseStatusCode::INTERNAL_SERVER_ERROR);
    }
}
