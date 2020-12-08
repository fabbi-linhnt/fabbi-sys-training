<?php

namespace Tests\Feature\Controller\Error;

use App\Enums\ResponseStatusCode;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function test_get_tasks_without_admin_role()
    {
        $perPage = "5";
        $name = '';
        $this->get("/api/courses?perPage=$perPage&name=$name")
            ->assertStatus(ResponseStatusCode::INTERNAL_SERVER_ERROR);
    }
}
