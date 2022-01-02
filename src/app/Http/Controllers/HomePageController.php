<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roadmap;
use Illuminate\Support\Facades\Auth;
class HomePageController extends Controller
{
    public function highlight () {
        $response = Roadmap::select('name', 'slug', 'description', 'path_img')
                            ->where('id', '<=', 4)
                            ->get();
        return $response;
    }

    public function menu () {
        $response = Roadmap::select('name', 'slug', 'description', 'path_img')->get();
        return $response;
    }

    public function myMenu(){
        $user_id = Auth::user()->id;
        $response = Roadmap::select('name', 'slug', 'description', 'path_img')
                            ->where('user_id', '=', $user_id)
                            ->get();
        return $response;
    }

}