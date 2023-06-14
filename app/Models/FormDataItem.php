<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormDataItem extends Model
{
    use HasFactory;

    protected $fillable = ['label', 'type', 'position', 'properties', 'form_section_id'];

    public function section()
    {
        return $this->belongsTo(FormSection::class, 'form_section_id');
    }

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }
}
