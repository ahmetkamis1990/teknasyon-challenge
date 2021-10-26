<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = 'devices';
    protected $primaryKey = 'device_id';

    protected $fillable = [
        'uid',
        'appId',
        'language',
        'operating_system',
        'client_token'
    ];
}
