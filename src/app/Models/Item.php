<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
        'latitude',
        'longitude',
        'explorer_id'
    ];

    public function explorer() {
        return $this->belongsTo(Explorer::class, 'explorer_id');
    }

}
