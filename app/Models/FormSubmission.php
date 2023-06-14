<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'form_data_item_id', 'user_id'];

    public function dataItem()
    {
        return $this->belongsTo(FormDataItem::class, 'form_data_item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
