<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'privacy'];

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id');
    }
}
