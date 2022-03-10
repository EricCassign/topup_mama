<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    
    public function index()
    {
        return response()->json(Author::orderBy('created_at', 'desc')->select('name', 'id')->get(), 200);
    }

    public function findOne($id)
    {
        $author = Author::select('name', 'id')->findOrFail($id);

        return response()->json($author, 200);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $data = $request->only('name');

        Author::create($data);

        return response()->json([ 'message' => 'Author Created Successfully.'], 201);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $data = $request->only('name');

        $author = Author::findOrFail($id);

        $author->update($data);

        return response()->json([ 'message' => 'Author Updated Successfully.'], 201);
    }
}
