<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function followers()
    {
        return $this->hasMany(Follower::class);
    }

    public function following()
    {
        return $this->hasMany(Follower::class, 'follower_id');
    }
    
    public function publicPosts()
    {
        return $this->hasMany(Post::class)->where('privacy', 'public');
    }

    public function friendsPosts()
    {
        return $this->hasMany(Post::class)->where('privacy', 'friends');
    }

    public function onlyMePosts()
    {
        return $this->hasMany(Post::class)->where('privacy', 'only_me');
    }

    public function friend_requests_sent()
    {
        return $this->hasMany(FriendRequest::class, 'friend_id');
    }

    public function friend_requests_received()
    {
        return $this->hasMany(FriendRequest::class, 'user_id');
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id');
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group', 'group_user');
    }

    public function todos()
    {
        return $this->hasMany(Todo::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

}
