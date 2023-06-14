<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSection extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'form_page_id'];

    public function page()
    {
        return $this->belongsTo(FormPage::class, 'form_page_id');
    }

    public function dataItems()
    {
        return $this->hasMany(FormDataItem::class);
    }
}
