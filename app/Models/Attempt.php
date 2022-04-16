<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'success',
        'attempts',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    
}
