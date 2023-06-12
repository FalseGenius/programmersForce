<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // TOKEN IS WORKING. IMPLEMENT SPATIE ROLES AND PERMISSIONS

        $user = JWTAuth::parseToken()->authenticate();

        // if ($user->hasRole('admin')) {
        if ($user->hasPermissionTo('read users')) {
            $users = User::all();
            return response()->json($users);
        } else {
            return response()->json(["message"=>"You do not have sufficient permissions"]);
        }
       
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'email'=>'required|email|unique:users',
                'password'=>'required|min:6',
                'user_role'=>'required|in:admin,user'
            ]);
    
            $user = User::create([
                'username'=>$request->username,
                'email'=>$request->email,
                'password'=>bcrypt($request->password),
                // 'user_role'=>$request->user_role
            ]);
    
            $role = $request->user_role;
            $user->assignRole($role);
            return response()->json($user, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error'=>$e->errors()], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $authUser = JWTAuth::parseToken()->authenticate();
        $user = User::findOrFail($id);
        if ($authUser->id == $user->id || $authUser->hasPermissionTo('read user')) {
            $data = $user->only(['username', 'email']);
            return response()->json($data);
        }

        return response()->json(['error'=>'Unauthorized'], 403);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $authUser = JWTAuth::parseToken()->authenticate();
        $user = User::findOrFail($id);
        
        if ($authUser->id == $user->id) {
            $request->validate([
                'username'=>'sometimes',
                'email'=>'sometimes|email|unique:users,email'.$id,
                'password'=>'sometimes|min:6'
            ]);
            
            $data = $request->only('username', 'email', 'password');
            if ($request->filled('password')) {
                $data['password'] = bcrypt($data['password']);
            }
            $user->update($data);
            return response()->json($user);
        } else if ($authUser->hasPermissionTo('update users')) {
            $request->validate(['username'=>'sometimes']);
            $data = $request->only('username');
            $user->update($data);
            return response()->json($user);
        }

        return response()->json(['error'=>'You are not authorized, or you do not have sufficient permissions'], 403);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $authUser = JWTAuth::parseToken()->authenticate();
        $user = User::findOrFail($id);

        if ($authUser->id == $user->id || $authUser->hasPermissionTo('delete users')) {
            $user->delete();
            return response()->json(['Status'=>'S', 'Message'=>'Successfully Deleted']);
        }

        return response()->json(['error'=>'You are unauthorized, or you do not have sufficient permisssions']);
    }
}
