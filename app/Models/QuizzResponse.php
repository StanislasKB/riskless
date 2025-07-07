<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizzResponse extends Model
{
    protected $fillable = ['user_id', 'quizz_id', 'document_url', 'status', 'score'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function quizz()
    {
        return $this->belongsTo(Quizz::class, 'quizz_id');
    }
}
