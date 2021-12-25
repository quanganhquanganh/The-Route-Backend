<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roadmap;
use App\Models\Milestone;
use Exception;
use Facade\FlareClient\Http\Exception\NotFound;
use Illuminate\Support\Facades\Validator;

class MilestoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        /*
        $roadmap = Roadmap::find($id);

        if(!$roadmap){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'Roadmap not found'
            ], 404);
        } else {
            $milestones = $roadmap->milestones()->get();
            return response()->json([
                'status' => 'success',
                'error' => false,
                'count' => count($milestones),
                'milestones' => $milestones
            ], 200);
        }*/
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
            'name' => 'required|string|max:100|min:3',
            'description' => 'required|string|max:255',
            'start_date' => 'required|date|date_format:Y-m-d|before:end_date',
            'end_date' => 'required|date|date_format:Y-m-d|after:start_date',
            'type' => 'required|string|in:daily,weekly,monthly,yearly',
        ]);

        if($validator->fails()){
            return $this->validationErrors($validator->errors());
        }

        try {
            $milestone = Milestone::create([
                'roadmap_id' => $request->roadmap_id,
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'type' => $request->type
            ]);
            return response()->json([
                'status' => 'success',
                'error' => false,
                'message' => 'Milestone created successfully',
                'milestone' => $milestone
            ], 201);
        }
        catch(Exception $e){
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
        $milestone = Milestone::find($id);

        if(!$milestone){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'Milestone not found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'success',
                'error' => false,
                'milestone' => $milestone
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
        $milestone = Milestone::find($id);

        if(!$milestone){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'Milestone not found'
            ], 404);
        } else {
            $validator = Validator::make($request->all(), [
                'roadmap_id' => 'required|integer|exists:roadmaps,id',
                'name' => 'required|string|max:100|min:3',
                'description' => 'required|string|max:255',
                'start_date' => 'required|date|date_format:Y-m-d|before:end_date',
                'end_date' => 'required|date|date_format:Y-m-d|after:start_date',
                'type' => 'required|string|in:daily,weekly,monthly,yearly',
            ]);

            if($validator->fails()){
                return $this->validationErrors($validator->errors());
            }
            
            try{
                $milestone->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'type' => $request->type
                ]);
                return response()->json([
                    'status' => 'success',
                    'error' => false,
                    'message' => 'Milestone updated successfully',
                    'milestone' => $milestone
                ], 200);
            }
            catch(Exception $e){
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
        $milestone = Milestone::find($id);
        if(!$milestone){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'Milestone not found'
            ], 404);
        } else {
            $milestone->delete();
            return response()->json([
                'status' => 'success',
                'error' => false,
                'message' => 'Milestone deleted successfully'
            ], 200);
        }
    }
}
