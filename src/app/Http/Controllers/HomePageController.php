<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roadmap;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomePageController extends Controller
{
    public function highlight () {
        //Count the likes from the likes table and return the 5 most liked roadmaps and all it's data
        $highlightedRoadmaps = DB::table('roadmaps')
            ->join('likes', 'roadmaps.id', '=', 'likes.roadmap_id')
            ->select('roadmaps.*', DB::raw('count(likes.roadmap_id) as likes'))
            ->groupBy('roadmaps.id')
            ->orderBy('likes', 'desc')
            ->limit(5)
            ->get()->toArray();
        
        return response()->json(
            [
                'status' => 'success',
                'error' => false,
                'message' => 'successfully',
                'data' => $highlightedRoadmaps
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
