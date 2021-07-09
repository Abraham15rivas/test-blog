<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'status',
        'participant_data',
        'post_id'
    ];

    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function answer() {
        return $this->hasOne(Answer::class);
    }
}
