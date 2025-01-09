<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTmpLinks extends Model
{
    protected $fillable = [
        'user_id',
        'link',
        'unique_id',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
