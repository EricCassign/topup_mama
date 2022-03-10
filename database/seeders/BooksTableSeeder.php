<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookAuthor;
use App\Models\BookCharacter;
use App\Models\Character;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        
        DB::transaction(function() {
            $book = Book::create([
                'name' => 'A Game of Thrones',
                'isbn' => '978-0553103540',
                'numberOfPages' => 694,
                'publisher' => 'Bantam Books',
                'country' => 'United States',
                'mediaType' => 'Hardcover',
                'released' => Carbon::parse('1996-08-01T00:00:00'),
            ]);
            $characters = Character::select('id')->get();
            
            foreach($characters as $character) {
                BookCharacter::create([ 'book_id' => $book->id, 'character_id' => $character->id ]);
            }
            $authors = Author::select('id')->get();
            foreach ($authors as $author ) {
                BookAuthor::create([ 'book_id' => $book->id, 'author_id' => $author->id, ]);
            }
        });
    }
}
