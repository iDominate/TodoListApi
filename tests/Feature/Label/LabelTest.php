<?php

namespace Tests\Feature\Label;

use App\Models\Label;
use App\Models\Task;
use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    private $todo, $task, $label;

    public function setUp() : void
    {
        parent::setUp();
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $this->todo = TodoList::factory()->create();
        $this->task = Task::factory()->create(["todo_list_id" => $this->todo->id]);
        $this->label = Label::factory()->create();
        $this->task->labels()->attach($this->label);
    }

    public function test_index()
    {
        $this->getJson(route("tasks.labels.index",$this->task->id))
        ->assertOk();
    }

    public function test_store_label_on_validation_error()
    {
        $this->postJson(route("tasks.labels.store",$this->task->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(["name"]);
    }

    public function test_store_on_task_not_found()
    {
        $this->postJson(route("tasks.labels.store",3), ['name' => "my name"])
        ->assertNotFound();
    }

    public function test_store_on_success()
    {
        $this->postJson(route("tasks.labels.store",1), ['name' => "my name"])
        ->assertCreated()
        ->json();

        $this->assertDatabaseHas("labels", ["name" => "my name"]);
    }

    public function test_show_on_label_not_found()
    {
        $this->getJson(route("labels.show",2))
        ->assertNotFound()
        ->json();
    }

    public function test_show_on_success()
    {
        $response = $this->getJson(route("labels.show",1))
        ->assertOk()
        ->json();

        $this->assertNotNull($response["data"]);
    }

    public function test_update_on_label_not_found()
    {
        $this->putJson(route("labels.update",2),["name" => "my name"])
        ->assertNotFound()
        ->json();
    }

    public function test_update_label_on_validation_error()
    {
        $this->putJson(route("labels.update",$this->task->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(["name"]);
    }

    public function test_update_on_success()
    {
        $response = $this->putJson(route("labels.update",1), ["name" => "my name"])
        ->assertOk()
        ->json();

        $this->assertNotNull($response["data"]);
        $this->assertDatabaseHas("labels", ["name" => "my name"]);
    }

    public function test_delete_on_label_not_found()
    {
        $this->deleteJson(route("labels.destroy",2))
        ->assertNotFound()
        ->json();
    }

    public function test_delete_on_success()
    {
        $response = $this->deleteJson(route("labels.destroy",1))
        ->assertOk()
        ->json();

        $this->assertNull($response["data"]);
        $this->assertDatabaseMissing("labels", ["name" => $this->label->name]);
    }
}
