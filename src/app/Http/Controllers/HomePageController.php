<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roadmap;
use Illuminate\Support\Facades\Auth;
class HomePageController extends Controller
{
    public function highlight () {
        $response = Roadmap::select('name', 'slug', 'description', 'image')
                            ->where('id', '<=', 4)
                            ->get();
        return response()->json(
            [
                'status' => 'success',
                'error' => false,
                'message' => 'successfully',
                'data' => $response
            ], 200
        );
    }

    public function menu () {
        $response = Roadmap::select('name', 'slug', 'description', 'image')->get();
        return response()->json(
            [
                'status' => 'success',
                'error' => false,
                'message' => 'successfully',
                'data' => $response
            ], 200
        );
    }

    public function myMenu(){
        $user_id = Auth::user()->id;
        $response = Roadmap::select('name', 'slug', 'description', 'image')
                            ->where('user_id', '=', $user_id)
                            ->get();
        return response()->json(
            [
                'status' => 'success',
                'error' => false,
                'message' => 'successfully',
                'data' => $response
            ], 200
        );
    }

}
