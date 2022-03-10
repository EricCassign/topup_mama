<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    public function index()
    {
        
        return response()->json(Character::orderBy('created_at', 'desc')->select(
            'name', 'gender', 'culture', 'born', 'died', 'father', 'mother',
            'spouse'
        )->get(), 200);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $data = $request->only('name', 'gender', 'culture', 
            'born', 'died', 'father', 'mother', 'spouse');
        Character::create($data);

        return response()->json([ 'message' => 'Character Saved Successfully.'], 201);
    }

    public function findOne($id) 
    {
        $character = Character::findOrFail($id);

        return response()->json($character, 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $data = $request->only('name', 'gender', 'culture', 
            'born', 'died', 'father', 'mother', 'spouse');
        
        $character = Character::findOrFail($id);

        $character->update($data);

        return response()->json([ 'message' => 'Character Updated Successfully.'], 201);
    }
}
