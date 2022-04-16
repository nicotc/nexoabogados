<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{

    use HasFactory;
    use SoftDeletes;
    

    protected $fillable = [
        'user_id',
        'plan',
        'amount',
        'status',
        'ends_at',
    ];




    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }


}
