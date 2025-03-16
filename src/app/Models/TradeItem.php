<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'trade_id',
        'item_id',
        'explorer_id',
        'quantity'
    ];

    public function trade() {
        return $this->belongsTo(Trade::class);
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }

    public function explorer() {
        return $this->belongsTo(Explorer::class);
    }

}
