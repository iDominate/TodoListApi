<?php

namespace App\Http\Controllers\Label;

use App\Http\Controllers\Controller;
use App\Http\Requests\LabelRequest;
use App\Models\Label;
use App\Models\Task;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LabelController extends Controller
{
    use ResponseTrait;
    /**
     * Display a listing of the resource.
     * @param App\Models\TodoList
     * @param App\Models\Task
     * @return \Illuminate\Http\Response
     */
    public function index(Task $task)
    {
        return response($this->returnMessage(message: "Shodwing Labels For Task With Id: {$task->id}", data: $task->labels));
    }

    /**
     * Store a newly created resource in storage.
     * @param  App\Models\Task
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Task $task,LabelRequest $request)
    {
        $label = Label::create($request->validated());
        $task->labels()->attach($label);

        return response($this->returnMessage(message:"Created Successfully",data: $label), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Label $label)
    {
        return response($this->returnMessage(message:"Showing Label With Id: {$label->id}",data: $label));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Label $label,LabelRequest $request)
    {
        $label->update($request->validated());
        return response($this->returnMessage(message:"Updated Label With Id: {$label->id}",data: $label));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        $label->delete();
        return response($this->returnMessage(message:"Deleted Label"));
    }
}
