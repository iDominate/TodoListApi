<?php

namespace App\Http\Controllers\TodoList;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTodoListRequest;
use App\Models\TodoList;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TodoListController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response($this->returnMessage(data: TodoList::all()), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CreateTodoListRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTodoListRequest $request)
    {
        $todo = TodoList::create(["title" => $request->title, "user_id" => auth()->user()->id]);
        return response($this->returnMessage(message: "Todo List Created",data: $todo), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TodoList $todo)
    {

        return response($this->returnMessage(message: "Showing Todo List With Id: {$todo->id}", data:$todo), Response::HTTP_OK);    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\CreateTodoListRequest   $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateTodoListRequest $request,TodoList $todo)
    {
        $todo->update($request->validated());
        return $this->returnMessage(message: "Updated Todo List With Id: {$todo->id}", data:$todo);  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TodoList $todo)
    {
        $todo->delete();
        return $this->returnMessage(message: "Deleted Todo List Successfully", data:null);  
    }
}
