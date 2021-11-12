<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWebinar extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registrant_id',
        'webinar_id',
        'topic',
        'role',
        'join_url',
    ];

    public function user()
    {

        return $this->belongsTo(User::class);
    }
}
