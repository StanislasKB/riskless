<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    protected $fillable = ['created_by','account_id' ,'title', 'img_url', 'document_url', 'description', 'status','visibility'];
     public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
