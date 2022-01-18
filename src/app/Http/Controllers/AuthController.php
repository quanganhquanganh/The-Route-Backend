<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function createUniqueUsername($name) {
        $username = Str::slug($name);
        $user = User::where('username', $username)->first();
        if($user) {
            $username = $username . '_' . rand(1, 100);
            return $this->createUniqueUsername($name);
        }
        return $username;
    }

    //login
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = Auth::attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = $validator->validated();
        return response()->json([
            'message' => 'User successfully login',
            'token' => $this->createNewToken($token),
            'user' => $user,
        ], 201);
    }

    //register
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'username' => $this->createUniqueUsername($request->name)
        ]);

        $token = Auth::attempt($validator->validated());

        return response()->json([
            'message' => 'User successfully registered',
            'token' => $this->createNewToken($token),
            'user' => $user,
        ], 201);
    }

    //logout
    public function logout() {
        Auth::logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    // Refresh a token.
    public function refresh() {
        return $this->createNewToken(Auth::refresh());
    }

    //dùng để custom token đi cùng với các trường khác
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ]);
    }
}
