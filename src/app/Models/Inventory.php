<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'explorer_id',
        'item_id',
        'quantity'
    ];

    public function explorer() {
        return $this->belongsTo(Explorer::class, 'explorer_id');
    }

    public function item() {
        return $this->belongsTo(Item::class, 'item_id');
    }

}
