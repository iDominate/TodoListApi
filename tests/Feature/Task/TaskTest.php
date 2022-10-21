<?php

namespace Tests\Feature\Task;

use App\Models\Task;
use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private $todo;
    private $task;

    public function setUp() : void 
    {
        parent::setUp();
        $this->todo = TodoList::factory()->create();
        $user = User::factory()->create();
        $this->task = Task::factory()->create(["todo_list_id" => $this->todo->id]);
        Sanctum::actingAs($user);
        $this->todo->user()->associate($user);

    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index()
    {
        
        $this->getJson(route("todos.tasks.index", $this->todo->id))
        ->assertOk();

        $this->assertDatabaseHas("tasks", ["name" =>$this->task->name]);
    }

    public function test_store_on_validation_error()
    {
        $this->postJson(route("todos.tasks.store", 1))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(["name"]);
    }

    public function test_store_on_success()
    {
        $this->postJson(route("todos.tasks.store", 1), ["name" => "my name"])
        ->assertCreated()
        ->json();

        $this->assertDatabaseHas("tasks", ["name" => "my name"]);
    }

    public function test_show_on_task_not_found()
    {
        $this->getJson(route("tasks.show", 4))
        ->assertNotFound()
        ->json();
    }

    public function test_show_on_success()
    {
        $response = $this->getJson(route("tasks.update", 1))
        ->assertOk()
        ->json();

        $this->assertNotNull($response["data"]);
    }

    public function test_update_on_validation_error()
    {
        $this->putJson(route("tasks.update", 1))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(["name"])
        ->json();
    }

    public function test_update_on_task_not_found()
    {
        $this->putJson(route("tasks.update", 2), ["name" => "my name"])
        ->assertNotFound()
        ->json();
    }

    public function test_update_on_success()
    {
        $this->putJson(route("tasks.update", 1), ["name" => "my name"])
        ->assertOk()
        ->json();

        $this->assertDatabaseHas("tasks", ["name" => "my name"]);
    }

    public function test_delete_on_task_not_found()
    {
        $this->deleteJson(route("tasks.destroy", 2))
        ->assertNotFound()
        ->json();
    }

    public function test_delete_on_success()
    {
        $this->deleteJson(route("tasks.destroy", 1))
        ->assertOk()
        ->json();

        $this->assertDatabaseMissing("tasks", ["name" => $this->task->name]);
    }
}
