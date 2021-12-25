<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roadmap;
use App\Models\Milestone;
use Exception;
use Facade\FlareClient\Http\Exception\NotFound;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'roadmap_id' => 'required|integer|exists:roadmaps,id',
            'milestone_id' => 'required|integer|exists:milestones,id',
            'user_id' => 'required|integer|exists:users,id',
            'content' => 'required|string|max:100|min:3',
            'start_date' => 'required|date|date_format:Y-m-d|before:end_date',
            'end_date' => 'required|date|date_format:Y-m-d|after:start_date'
        ]);

        if ($validator->fails()) {
            return $this->validationErrors($validator->errors());
        }

        try {
            $task = Task::create([
                'roadmap_id' => $request->roadmap_id,
                'milestone_id' => $request->milestone_id,
                'user_id' => $request->user_id,
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'completed' => false
            ]);

            return response()->json([
                'status' => 'success',
                'error' => false,
                'message' => 'Task created successfully',
                'task' => $task
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $task = Task::find($id);

        if(!$task){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'Task not found'
            ], 404);
        } else {
            $validator = Validator::make($request->all(), [
                'roadmap_id' => 'required|integer|exists:roadmaps,id',
                'milestone_id' => 'required|integer|exists:milestones,id',
                'user_id' => 'required|integer|exists:users,id',
                'content' => 'required|string|max:100|min:3',
                'start_date' => 'required|date|date_format:Y-m-d|before:end_date',
                'end_date' => 'required|date|date_format:Y-m-d|after:start_date',
                'completed' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return $this->validationErrors($validator->errors());
            }
            
            try {
                $task->update([
                    'roadmap_id' => $request->roadmap_id,
                    'milestone_id' => $request->milestone_id,
                    'user_id' => $request->user_id,
                    'content' => $request->content,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'completed' => $request->completed
                ]);

                return response()->json([
                    'status' => 'success',
                    'error' => false,
                    'message' => 'Task updated successfully',
                    'task' => $task
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'error' => true,
                    'message' => $e->getMessage()
                ], 404);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $task = Task::find($id);

        if(!$task){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'Task not found'
            ], 404);
        } else {
            try {
                $task->delete();

                return response()->json([
                    'status' => 'success',
                    'error' => false,
                    'message' => 'Task deleted successfully'
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'error' => true,
                    'message' => $e->getMessage()
                ], 404);
            }
        }
    }
}
