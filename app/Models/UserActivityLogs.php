<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivityLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'authenticated_user_id',
        'route_name',
        'http_method'
    ];
}
