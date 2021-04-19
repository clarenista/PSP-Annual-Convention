<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'questionnaire_id',
        'question_id',
        'answer',
        'correct',
        'sent_at'
    ];

    public function questionnaire()
    {

        return $this->belongsTo(Questionnaire::class);
    }

    public function question()
    {

        return $this->belongsTo(Question::class);
    }

    public function user()
    {

        return $this->belongsTo(User ::class);
    }
}
