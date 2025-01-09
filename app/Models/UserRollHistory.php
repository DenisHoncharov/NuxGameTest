<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRollHistory extends Model
{
    protected $fillable = [
        'user_id',
        'number',
        'win',
        'roll_result'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
