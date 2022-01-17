<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Roadmap;
use App\Models\Milestone;
use App\Models\Task;
use App\Models\User;
use Exception;
use Facade\FlareClient\Http\Exception\NotFound;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
//use Storage

function createUniqueSlug($slug, $id = null)
{
    $slug = Str::slug($slug);
    $roadmaps = Roadmap::where('slug', 'like', $slug . '%')->get();
    $count = 0;
    foreach ($roadmaps as $roadmap) {
        $slugParts = explode('-', $roadmap->slug);
        $newCount = (int)$slugParts[count($slugParts) - 1] + 1;
        if ($newCount > $count) {
            $count = $newCount;
        }
    }
    if($count > 0 && $id != null){
        $count = Roadmap::where('slug', $slug)->where('id', '!=', $id)->count();
    }
    if($count > 0){
        $slug = $slug.'-'.$count;
    }
    return $slug;
}

class RoadmapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $roadmaps = $user->roadmaps;
        $roadmaps = $roadmaps->map(function ($roadmap) {
            $roadmap->likes_count = $roadmap->likes()->count();
            $roadmap->follows_count = $roadmap->followers()->count();
            $roadmap->is_roadmap_owner = $roadmap->user_id == Auth::id();
            return $roadmap;
        });
        return response()->json(
            [
                'status' => 'success',
                'error' => false,
                'message' => 'Roadmaps retrieved successfully',
                'same_user' => $user->id == Auth::id(),
                'data' => $roadmaps
            ], 200
        );
    }

    public function search($query) {
        $roadmaps = Roadmap::where('name', 'like', '%'.$query.'%')->get();
        return response()->json(
            [
                'status' => 'success',
                'error' => false,
                'message' => 'Roadmaps retrieved successfully',
                'data' => $roadmaps
            ], 200
        );
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
            'name' => 'required|string|max:30|min:1',
            'description' => 'required|string|max:255',
            'image' => 'base64image',
        ]);

        if($validator->fails()){
            return $this->validationErrors($validator->errors());
        }

        try {
            $imageName = null;
            if($request->has('image')) {
                $image = $request->image;  // your base64 encoded
                $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
                $imageName = time().'.'.'png';
                $destinationPath = public_path('/images').'/'.$imageName;
                file_put_contents($destinationPath, $image);
            } else {
                $imageName = 'default.png';
            }

            $roadmap = Roadmap::create([
                'name' => $request->name,
                'description' => $request->description,
                'slug' => createUniqueSlug($request->name),
                'image' => $imageName,
                'user_id' => Auth::user()->id,
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

    public function duplicate(Roadmap $roadmap)
    {
        $newRoadmap = $roadmap->replicate();
        $newRoadmap->slug = createUniqueSlug($newRoadmap->name);
        $newRoadmap->user_id = Auth::user()->id;
        $newRoadmap->save();

        $milestones = $roadmap->milestones;
        foreach ($milestones as $milestone) {
            $newMilestone = $milestone->replicate();
            $newMilestone->roadmap_id = $newRoadmap->id;
            $newMilestone->user_id = Auth::user()->id;
            $newMilestone->save();
        }

        return response()->json([
            'status' => 'success',
            'error' => false,
            'message' => 'Roadmap duplicated successfully',
            'roadmap' => $newRoadmap
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Roadmap $roadmap)
    {
        //
        $roadmap->likes_count = $roadmap->likes()->count();
        $roadmap->follows_count = $roadmap->followers()->count();
        $roadmap->is_roadmap_owner = $roadmap->user_id == Auth::id();
        return response()->json([
            'status' => 'success',
            'error' => false,
            'message' => 'Roadmap retrieved successfully',
            'roadmap' => $roadmap
        ], 200);
    }

    /**
     * Display all data about a roadmap.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function full(Roadmap $roadmap)
    {
        $roadmap->likes_count = $roadmap->likes()->count();
        $roadmap->follows_count = $roadmap->followers()->count();
        //Get milestones sorted by start date
        $milestones = $roadmap->milestones()->orderBy('start_date', 'asc')->get();
        
        $user = Auth::user();
        if($user) {
            if($user->id == $roadmap->user_id){
                $milestones = $milestones->map(function($milestone) {
                    $milestone->tasks = Task::where('milestone_id', $milestone->id)->get();
                    return $milestone;
                });
            }
        }
        return response()->json([
            'status' => 'success',
            'error' => false,
            'message' => 'Full roadmap retrieved successfully',
            'roadmap' => $roadmap,
            'milestones' => $milestones,
            'is_roadmap_owner' => $user->id == $roadmap->user_id
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Roadmap $roadmap)
    {
        //
        $user = Auth::user();
        //Check if no user is logged in
        if(!$user){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'You must be logged in to update a roadmap'
            ], 401);
        }
        //Check if roadmap is belong to user
        if($roadmap->user_id != $user->id){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'You are not authorized to update this roadmap'
            ], 404);
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:30|min:3',
                'description' => 'required|string|max:255',
                'image' => 'base64image',
            ]);

            if($validator->fails()){
                return $this->validationErrors($validator->errors());
            }

            try {
                $imageName = null;
                if($request->has('image')) {
                    $image = $request->image;  // your base64 encoded
                    $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
                    $imageName = time().'.'.'png';
                    $destinationPath = public_path('/images').'/'.$imageName;
                    file_put_contents($destinationPath, $image);
                    if($roadmap->image != 'default.png'){
                        $oldImage = public_path('/images/'.$roadmap->image);
                        if(file_exists($oldImage)){
                            unlink($oldImage);
                        }
                    }
                } else {
                    $imageName = $roadmap->image;
                }

                $roadmap->update([
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'description' => $request->description,
                    'image' => $imageName,
                    'current' => $request->current,
                    'slug' => createUniqueSlug($request->name, $roadmap->id),
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
    public function destroy(Roadmap $roadmap)
    {
        $user = Auth::user();
        //Check if no user is logged in
        if(!$user){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'You must be logged in to delete a roadmap'
            ], 401);
        }
        //Check if roadmap is belong to authUser
        if($roadmap->user_id != $user->id){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'You are not authorized to delete this roadmap'
            ], 404);
        } else {
            try {
                $roadmap->delete();
                return response()->json([
                    'status' => 'success',
                    'error' => false,
                    'message' => 'Roadmap deleted successfully',
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

    //Function for user to like a roadmap
    public function like(Roadmap $roadmap)
    {
        $user = Auth::user();
        //Check if no user is logged in
        if(!$user){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'You must be logged in to like a roadmap'
            ], 401);
        }
        //Check if roadmap already liked
        if($roadmap->likes->contains($user->id)){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'You already liked this roadmap'
            ], 404);
        } else {
            try {
                $roadmap->likes()->attach($user->id);
                return response()->json([
                    'status' => 'success',
                    'error' => false,
                    'message' => 'Roadmap liked successfully',
                    'likes' => $roadmap->likes()->count()
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

    //Function for user to unlike a roadmap
    public function unlike(Roadmap $roadmap)
    {
        $user = Auth::user();
        //Check if no user is logged in
        if(!$user){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'You must be logged in to unlike a roadmap'
            ], 401);
        }
        //Check if roadmap already liked
        if(!$roadmap->likes->contains($user->id)){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'You did not like this roadmap'
            ], 404);
        } else {
            try {
                $roadmap->likes()->detach($user->id);
                return response()->json([
                    'status' => 'success',
                    'error' => false,
                    'message' => 'Roadmap unliked successfully',
                    'likes' => $roadmap->likes()->count()
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

    //Function for user to follow a roadmap
    public function follow(Roadmap $roadmap)
    {
        $user = Auth::user();
        //Check if no user is logged in
        if(!$user){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'You must be logged in to follow a roadmap'
            ], 401);
        }
        //Check if roadmap already followed
        if($roadmap->followers->contains($user->id)){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'You already followed this roadmap'
            ], 404);
        } else {
            try {
                $roadmap->followers()->attach($user->id);
                return response()->json([
                    'status' => 'success',
                    'error' => false,
                    'message' => 'Roadmap followed successfully',
                    'followers' => $roadmap->followers()->count()
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

    //Function for user to unfollow a roadmap
    public function unfollow(Roadmap $roadmap)
    {
        $user = Auth::user();
        //Check if no user is logged in
        if(!$user){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'You must be logged in to unfollow a roadmap'
            ], 401);
        }
        //Check if roadmap already followed
        if(!$roadmap->followers->contains($user->id)){
            return response()->json([
                'status' => 'error',
                'error' => true,
                'message' => 'You did not follow this roadmap'
            ], 404);
        } else {
            try {
                $roadmap->followers()->detach($user->id);
                return response()->json([
                    'status' => 'success',
                    'error' => false,
                    'message' => 'Roadmap unfollowed successfully',
                    'followers' => $roadmap->followers()->count()
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

    //Function for getting all the roadmaps that the user liked
    public function liked(User $user)
    {
        $likedRoadmaps = $user->likedRoadmaps;
        $likedRoadmaps = $likedRoadmaps->map(function($roadmap){
            $roadmap->likes_count = $roadmap->likes()->count();
            $roadmap->follows_count = $roadmap->followers()->count();
            $roadmap->is_roadmap_owner = $roadmap->user_id == Auth::id();
            return $roadmap;
        });
        return response()->json([
            'status' => 'success',
            'error' => false,
            'message' => 'Liked roadmaps',
            'data' => $likedRoadmaps
        ], 200);
    }

    //Function for getting all the roadmaps that the user followed
    public function followed(User $user)
    {
        $followedRoadmaps = $user->followedRoadmaps;
        $followedRoadmaps = $followedRoadmaps->map(function($roadmap){
            $roadmap->likes_count = $roadmap->likes()->count();
            $roadmap->follows_count = $roadmap->followers()->count();
            $roadmap->is_roadmap_owner = $roadmap->user_id == Auth::id();
            return $roadmap;
        });
        return response()->json([
            'status' => 'success',
            'error' => false,
            'message' => 'Followed roadmaps',
            'data' => $followedRoadmaps
        ], 200);
    }

    public function createUniqueSlug($slug, $id = null)
    {
        $slug = Str::slug($slug);
        $count = Roadmap::where('slug', $slug)->count();
        if($count > 0 && $id != null){
            $count = Roadmap::where('slug', $slug)->where('id', '!=', $id)->count();
        }
        if($count > 0){
            $slug = $slug.'-'.$count;
        }
        return $slug;
    }
}
