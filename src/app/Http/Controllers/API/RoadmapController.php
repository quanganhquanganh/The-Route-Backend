<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roadmap;
use App\Models\Milestone;
use App\Models\Task;
use Exception;
use Facade\FlareClient\Http\Exception\NotFound;
use Illuminate\Support\Facades\Validator;

class RoadmapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
            'name' => 'required|string|max:30|min:3',
            'description' => 'required|string|max:255',
            'slug' => 'required|string|max:30|min:3',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if($validator->fails()){
            return $this->validationErrors($validator->errors());
        }

        try {
            $roadmap = Roadmap::create([
                'name' => $request->name,
                'description' => $request->description,
                'slug' => $request->slug,
                'user_id' => $request->user_id,
            ]);
            
            return response()->json([
                'status' => 'success',
                'error' => false,
                'message' => 'Roadmap created successfully',
                'roadmap' => $roadmap
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $roadmap = Roadmap::find($id);

        if(!$roadmap){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'Roadmap not found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'error' => false,
                'roadmap' => $roadmap
            ], 200);
        }
    }

    /**
     * Display all data about a roadmap.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function full($id)
    {
        //
        $roadmap = Roadmap::find($id);
        //Get milestones sorted by start date
        $milestones = $roadmap->milestones()->orderBy('start_date', 'asc')->get();
        $milestones = $milestones->map(function($milestone) {
            $milestone->tasks = Task::where('milestone_id', $milestone->id)->get();
            return $milestone;
        });

        if(!$roadmap){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'Roadmap not found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'error' => false,
                'roadmap' => $roadmap,
                'milestones' => $milestones
            ], 200);
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
        $roadmap = Roadmap::find($id);

        if(!$roadmap){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'Roadmap not found'
            ], 404);
        } else {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id',
                'name' => 'required|string|max:30|min:3',
                'description' => 'required|string|max:255',
                'slug' => 'required|string|max:30|min:3',
            ]);

            if($validator->fails()){
                return $this->validationErrors($validator->errors());
            }

            try {
                $roadmap->update([
                    'user_id' => $request->user_id,
                    'name' => $request->name,
                    'description' => $request->description,
                    'slug' => $request->slug,
                ]);

                return response()->json([
                    'status' => 'success',
                    'error' => false,
                    'message' => 'Roadmap updated successfully',
                    'roadmap' => $roadmap
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
        $roadmap = Roadmap::find($id);

        if(!$roadmap){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'Roadmap not found'
            ], 404);
        } else {
            $roadmap->delete();
            return response()->json([
                'status' => 'success',
                'error' => false,
                'message' => 'Roadmap deleted'
            ], 200);
        }
    }
}
