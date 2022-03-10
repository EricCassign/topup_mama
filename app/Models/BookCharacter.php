<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookCharacter extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'character_id', 'book_id', ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function character() 
    {
        return $this->belongsTo(Character::class, 'character_id');
    }

}
