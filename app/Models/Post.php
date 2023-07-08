<?php

namespace App\Models;
use  App\Models\User;
use  App\Models\Tag;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'body',
        'cover_image',
        'pinned',
        'tag_id',
        'user_id',
        'is_delete'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

}
