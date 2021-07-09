<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'title',
       'slug',
       'author',
       'visits',
       'description',
       'image',
       'video',
       'comment',
       'published',
       'user_id'
    ];

    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
