<?php

namespace Tests\Unit\Controller\Success;

use App\Enums\ResponseStatusCode;
use App\Http\Controllers\Api\UserController;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    protected $userMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->userMock = \Mockery::mock(UserRepositoryInterface::class);
        $this->userMock->id = config('configusertest.id_ok');
    }

    public function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }

    public function test_show_detail_user()
    {
        $userRepository = App::make(UserRepository::class, array($this->userMock));
        $userController = new UserController($userRepository);
        $data = $userController->getUserInfo(config('configusertest.id_ok'));
        $this->assertEquals(ResponseStatusCode::OK, $data->getStatusCode());
    }
}
