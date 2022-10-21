<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    public function setUp() : void
    {
        parent::setUp();
        $this->admin = User::factory()->create(["role" => "admin"]);
        
        Sanctum::actingAs($this->admin);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login_on_validation_error()
    {
        $response = $this->postJson(route("admin.login"))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(["email", "password"])
        ->json();
    }

    public function test_login_on_success()
    {
        $response = $this->postJson(route("admin.login"), ["email" => $this->admin->email, "password" =>$this->admin->password])
        ->assertOk()
        ->json();

        $this->assertArrayHasKey("token", $response["data"]);
    }

    function test_logout_on_success()
    {
        $response = $this->postJson(route("admin.logout"))
        ->assertOk()
        ->json();

        $this->assertEqualsIgnoringCase("logged out successfully", $response["message"]);
    }
}
