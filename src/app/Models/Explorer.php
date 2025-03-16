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

    public function inventories() {
        return $this->hasMany(Inventory::class, 'explorer_id');
    }

    public function items() {
        return $this->hasManyThrough(Item::class, Inventory::class, 'Explorer_id', 'id', 'id', 'item_id');
    }
}
