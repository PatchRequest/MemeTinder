<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meme extends Model
{
    use HasFactory;
    public $fillable = [
        'title',
        'url',
        'postLink',
        'subreddit',
        'nsfw',
        'spoiler',
        'author',
        'ups',
    ];

    public function users(){
        return $this->belongsToMany(User::class);
    }
}
