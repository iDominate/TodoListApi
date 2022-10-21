<?php

namespace Tests\Feature\TodoList;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    private $todo;

    function setUp() : void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->todo = TodoList::factory()->create();
        $this->todo->user()->associate($user);
        
        Sanctum::actingAs($user);
    }
    public function test_index()
    {
        $response = $this->getJson(route("todos.index"))
        ->assertOk()
        ->json();
        
       $this->assertArrayHasKey("title", $response["data"][0]);
    }

    public function test_store_on_success()
    {
        $response = $this->postJson(route("todos.store"), ["title" => "my title"])
        ->assertCreated()
        ->json();

        $this->assertArrayHasKey("title", $response["data"]);
        $this->assertEqualsIgnoringCase("todo list created", $response["message"]);

        $this->assertDatabaseHas("todo_lists", ["title" => "my title"]);
    }

    public function test_store_on_validation_error()
    {
        $response = $this->postJson(route("todos.store"))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(["title"])
        ->json();
    }

    public function test_show_on_error_not_found()
    {
        $response = $this->getJson(route("todos.show", 2))
        ->assertNotFound()
        ->json();

    }

    public function test_show_on_success()
    {
        $response = $this->getJson(route("todos.show", 1))
        ->assertOk()
        ->json();
        
        $this->assertNotNull($response["data"]);
    }

    public function test_update_on_validation_error()
    {
        $response = $this->putJson(route("todos.update", 1))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(["title"])
        ->json();
    }

    public function test_update_on_todo_list_not_found()
    {
        $response = $this->putJson(route("todos.update", 2), ["title" => "title"])
        ->assertNotFound()
        ->json();
    }

    public function test_update_on_success()
    {
        $response = $this->putJson(route("todos.update", 1), ["title" => "my title"])
        ->assertOk()
        ->json();

        $this->assertDatabaseHas("todo_lists", ["title" => "my title"]);
    }

    function test_delete_on_todo_not_found()
    {
        $response = $this->deleteJson(route("todos.update", 2))
        ->assertNotFound()
        ->json();
    }

    function test_delete_on_success()
    {
        $response = $this->deleteJson(route("todos.update", 1))
        ->assertOk()
        ->json();

        $this->assertDatabaseMissing("todo_lists", ["title"=>"password"]);
    }

}
