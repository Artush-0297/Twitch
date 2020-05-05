<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class AuthProvider extends Model
{
    protected $table = 'auth_providers';

    protected $fillable = [
        'user_id',
        'provider',
        'access_token',
        'expires_in',
        'refresh_token',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
