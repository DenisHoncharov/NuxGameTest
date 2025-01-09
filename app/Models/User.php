<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'phone_number',
    ];

    public function userRollHistories()
    {
        return $this->hasMany(UserRollHistory::class);
    }

    public function userTmpLinks()
    {
        return $this->hasMany(UserTmpLinks::class);
    }
}
