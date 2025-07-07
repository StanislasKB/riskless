<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quizz extends Model
{
    protected $fillable = ['created_by','account_id', 'title', 'img_url', 'document_url', 'description', 'status', 'visibility', 'formation_id'];
     public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function formation()
    {
        return $this->belongsTo(Formation::class, 'formation_id');
    }
     public function responses()
    {
        return $this->hasMany(QuizzResponse::class,'quizz_id');
    }
}
