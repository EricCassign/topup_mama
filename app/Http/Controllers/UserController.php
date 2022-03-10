<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        return response()->json(User::orderBy('created_at', 'desc')->select('name', 'email', 'id')->get(), 200);
    }

    public function findOne($id)
    {
        $user = User::select('name', 'email', 'id')->findOrFail($id);

        return response()->json($user, 200);
    }

    public function create(Request $request) 
    {
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $data = $request->only('name', 'email');
        $data['password'] = Hash::make($request->input('password'));

        User::create($data);

        return response()->json(['message' => 'User Created Successfully.'], 201);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $data = $request->only('name', 'email');
        $data['password'] = Hash::make($request->input('password'));
        if(!$request->input('password')) {
            unset($data['password']);
        }

        $user = User::findOrFail($id);

        $user->update($data);

        return response()->json(['message' => 'User Updated Successfully.'], 201);
    }
}
