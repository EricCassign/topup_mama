<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'url', 'isbn', 'numberOfPages', 'publisher', 
        'country', 'mediaType', 'released',
    ];

    public function authors()
    {
        return $this->hasMany(BookAuthor::class, 'book_id');
    }

    public function characters()
    {
        return $this->hasMany(BookCharacter::class, 'book_id');
    }
    public function chars() 
    {
        return $this->hasManyThrough(Character::class, BookCharacter::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'book_id');
    }
}
