<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'latitude',
        'longitude',
        'explorer_id'
    ];

    public function explorer() {
        return $this->belongsTo(Explorer::class, 'explorer_id');
    }
}
