<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany('App\User', 'group_user');
    }
    
    public function creator()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
