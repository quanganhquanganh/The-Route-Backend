<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tests = Test::all();
        return response()->json([
            'status' => 'success',
            'error' => false,
            'message' => 'Tests retrieved successfully',
            'tests' => $tests
        ], 200);
    }

    public function store(Request $request)
    {
        $params = $request->all();
        $params->slug = str_slug($test->test);
        $test = Test::create($params);
        return response()->json([
            'status' => 'success',
            'error' => false,
            'message' => 'Test created successfully',
            'test' => $test
        ], 200);
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function show(Test $test)
    {
        return response()->json([
            'status' => 'success',
            'error' => false,
            'message' => 'Test retrieved successfully',
            'test' => $test
        ], 200);
        //
    }
    
    public function update(Request $request, Test $test)
    {
        $test->update($request->all());
        return response()->json([
            'status' => 'success',
            'error' => false,
            'message' => 'Test updated successfully',
            'test' => $test
        ], 200);
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function destroy(Test $test)
    {
        $test->delete();
        return response()->json([
            'status' => 'success',
            'error' => false,
            'message' => 'Test deleted successfully'
        ], 200);
        //
    }
}
