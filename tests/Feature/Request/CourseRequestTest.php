<?php

namespace Tests\Feature\Request;

use App\Enums\ResponseStatusCode;
use App\Http\Requests\Courses\CourseStoreRequest;
use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CourseRequestTest extends TestCase
{
    private $header;

    public function setUp(): void
    {
        parent::setUp();
        $email = 'nam@gmail.com';
        $password = '123456';
        $response = $this->post('api/auth/login', ['email' => $email, 'password' => $password])
            ->assertStatus(ResponseStatusCode::OK)
            ->assertJsonStructure(['token_type', 'expires_in', 'access_token']);
        $this->header = [
            'headers' => [
                'Authorization' => 'Bearer ' . $response->getData()->access_token,
                'Accept' => 'application/json'
            ]];
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_without_required_all_field_store_course()
    {
        $emptyData = [];
        $response = $this->post('/api/courses', $emptyData, $this->header)
            ->assertJsonStructure([
                'code',
                'message',
                'errors'
            ]);
        $this->assertEquals(ResponseStatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    public function test_is_active_field_invalid_store_course()
    {
        $data = [
            'name' => 'asdasd',
            'description' => 'asdasdasd',
            'is_active' => -6,
            'category_id' => 3,
            'path' => 'asdasd.com'
        ];
        $response = $this->post('/api/courses', $data, $this->header)
            ->assertJsonStructure([
                'code',
                'message',
                'errors'
            ]);
        $this->assertEquals(ResponseStatusCode::INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }
}
