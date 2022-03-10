<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookAuthor;
use App\Models\BookCharacter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index()
    {
        $data = Book::orderBy('created_at', 'desc')->with(
                     
                    [
                        'characters.character' => function($query) {
                            return $query->select(['name', 'id']);
                        }
                    ]
                )->with(
                    [
                        'authors.author' => function($query) {
                            return $query->select(['name', 'id']);
                        }
                    ]
                )->withCount('comments')->orderBy('released', 'desc')->get();

        $books = $data->map(function($book) {
            return [
                'name' => $book->name,
                'isbn' => $book->isbn,
                'numberOfPages' => $book->numberOfPages,
                'publisher' => $book->publisher,
                'country' => $book->country,
                'mediaType' => $book->mediaType,
                'comments' => $book->comments_count,
                'released' => $book->released,
                'authors' => $book->authors->map(function($author) { return $author->author ? $author->author->name : null; }),
                'characters' => $book->characters->map(function($character) { return $character->character ? $character->character->name : null; }),
            ];
        });

        return response()->json($books, 200);
    }

    public function findOne($id)
    {
        $data = Book::orderBy('created_at', 'desc')->with(
                     
            [
                'characters.character' => function($query) {
                    return $query->select(['name', 'id']);
                }
            ]
        )->with(
            [
                'authors.author' => function($query) {
                    return $query->select(['name', 'id']);
                }
            ]
        )->withCount('comments')->findOrFail($id);

        $book = [
            'name' => $data->name,
            'isbn' => $data->isbn,
            'numberOfPages' => $data->numberOfPages,
            'publisher' => $data->publisher,
            'country' => $data->country,
            'mediaType' => $data->mediaType,
            'comments' => $data->comments_count,
            'released' => $data->released,
            'authors' => $data->authors->map(function($author) { return $author->author ? $author->author->name : null; }),
            'characters' => $data->characters->map(function($character) { return $character->character ? $character->character->name : null; }),
        ];

        return response()->json($book, 200);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'numberOfPages' => 'required|max:255',
            'publisher' => 'required|max:255',
            'released' => 'required|max:255',
        ]);

        $data = $request->only(
                    'name', 'url', 'isbn', 'numberOfPages', 
                    'publisher', 'country', 'mediaType', 'released'
                );

        $data['releasead'] = Carbon::parse($request->input('released'));

        $transaction = DB::transaction(function() use($data, $request){
            $book = Book::create($data);

            if($request->input('authors')) {
                $authors = $request->input('authors');
                foreach($authors as $author) {
                    BookAuthor::create(['author_id' => $author, 'book_id' => $book->id, ]);
                }
            }

            if($request->input('characters')) {
                $characters = $request->input('characters');
                foreach ($characters as $character) {
                    BookCharacter::create(['book_id' => $book->id, 'character_id' => $character, ]);
                }
            }

            return true;
        });

        if(!$transaction) {

            return response()->json(['message' => 'Error Occurred!'], 500);
        }

        return response()->json(['message' => 'Book Saved Successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'numberOfPages' => 'required|max:255',
            'publisher' => 'required|max:255',
            'released' => 'required|max:255',
        ]);

        $data = $request->only(
                    'name', 'url', 'isbn', 'numberOfPages', 
                    'publisher', 'country', 'mediaType', 'released'
                );

        $data['releasead'] = Carbon::parse($request->input('released'));

        $transaction = DB::transaction(function() use($data, $request, $id) {
            $book = Book::findOrFail($id);

            $book->update($data);

            $book->authors()->delete();
            $book->characters()->delete();

            if($request->input('authors')) {
                $authors = $request->input('authors');
                foreach($authors as $author) {
                    BookAuthor::create(['author_id' => $author, 'book_id' => $book->id, ]);
                }
            }

            if($request->input('characters')) {
                $characters = $request->input('characters');
                foreach ($characters as $character) {
                    BookCharacter::create(['book_id' => $book->id, 'character_id' => $character, ]);
                }
            }

            return true;
        });

        if(!$transaction) {

            return response()->json(['message' => 'Error Occurred!'], 500);
        }

        return response()->json(['message' => 'Book Updated Successfully'], 201);  
    }
}
