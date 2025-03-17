<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Explorer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'latitude',
        'longitude'
    ];

    public function items() {
        return $this->hasMany(Item::class, 'explorer_id');
    }

    public function history() {
        return $this->hasMany(History::class, 'explorer_id');
    }
}
