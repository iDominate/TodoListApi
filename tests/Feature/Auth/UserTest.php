<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    public function setUp() : void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        Sanctum::actingAs($this->user);

    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register_user_on_validation_error()
    {
        $response = $this->postJson(route("user.register"))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(["name","password","email"])
        ->json();
    }

    public function test_register_user_on_success()
    {
        $response = $this->postJson(route("user.register"), [
            "name" => "name",
            "email" => "email@email.com",
            "password" => "password",
            "password_confirmation" => "password"
        ])
        ->assertCreated()
        ->json();

        
        $this->assertTrue(Hash::check("password", $response["data"]["password"]));
    }

    public function test_login_on_validation_error()
    {
        $response = $this->postJson(route("user.login"))
        ->assertJsonValidationErrors(["email","password"])
        ->assertUnprocessable()
        ->json();
    }

    public function test_login_on_success()
    {
        $response = $this->postJson(route("user.login"), ["email" => $this->user->email, "password" => $this->user->password])
        ->assertOk()
        ->json();

        $this->assertSame("user", $response["data"]["user"]["role"]);
        $this->assertArrayHasKey("token", $response["data"]);
    }

    public function test_profile_on_auth_error()
    {
        $response = $this->getJson(route("user.profile"))
        ->assertOk()
        ->json();
        
        $this->assertSame("My Profile", $response["message"]);
    }

    public function test_logout_on_success()
    {
        $response = $this->postJson(route("user.logout"))
        ->assertOk()
        ->json();

        $this->assertSame("Logged Out Successfully", $response["message"]);
    }
}
