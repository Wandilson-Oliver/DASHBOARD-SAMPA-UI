<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatFaq extends Model
{
    protected $table = 'chat_faqs';
    
    protected $fillable = [
        'question',
        'answer',
        'question_frequency',
    ];
}
