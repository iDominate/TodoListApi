<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class TaskTodoRelationTest extends TestCase
{
    use RefreshDatabase;
    private $todo;
    private $tasks;
    public function setUp() : void {
        parent::setUp();
        $this->todo = TodoList::factory()->create();
        $this->tasks = Task::factory(10)->create()->each(function($task, $key){
            $task->todo_list()->associate($this->todo);
        });

    }
    public function test_todo_has_many_tasks()
    {
        $this->assertDatabaseCount('tasks', 10);
        $this->assertInstanceOf(Collection::class, $this->todo->tasks);
        $this->assertInstanceOf(TodoList::class, $this->tasks->first()->todo_list);
    }
}
