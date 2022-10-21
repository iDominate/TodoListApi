<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
{
    use HasFactory;

    protected $fillable = ["title", "user_id"];

    public function tasks()
    {
        return $this->hasMany(Task::class, "todo_list_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
