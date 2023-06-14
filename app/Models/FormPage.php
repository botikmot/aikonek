<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormPage extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'form_id'];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function sections()
    {
        return $this->hasMany(FormSection::class);
    }
}
