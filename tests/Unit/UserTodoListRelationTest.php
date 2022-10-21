<?php

namespace Tests\Unit;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class UserTodoListRelationTest extends TestCase
{
    use RefreshDatabase;
    private $user, $todoLists;
    public function setUp() : void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->todoLists = TodoList::factory(10)->create()->each(function($todo, $key){
            $todo->user()->associate($this->user);
        });

    }
    public function test_user_has_many_todos()
    {
        $this->assertDatabaseCount("todo_lists", 10);
        $this->assertInstanceOf(Collection::class, $this->user->todo_lists);
        $this->assertInstanceOf(User::class, $this->todoLists->first()->user);
    }
}
