<?php

namespace App\Models\Message;

use App\Models\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'to_user',
        'message'
    ];
}
