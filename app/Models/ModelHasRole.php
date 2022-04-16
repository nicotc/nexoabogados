<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHasRole extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'model_type',
        'model_id',
        'role_id',
    ];

    public function model()
    {
        return $this->belongsTo(User::class);
    }




}
