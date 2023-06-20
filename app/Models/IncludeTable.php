<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncludeTable extends Model
{
    use HasFactory;

    protected $table = 'includes';

    protected $fillable = [
        'message_id',
        'conversation_id',
        'user_id'
    ];
}
