<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table='subscriptions';
    protected $primaryKey = 'subscription_id';

    protected $fillable = [
        'client_token',
        'receipt_id',
        'status',
        'expire_date'
    ];
}
