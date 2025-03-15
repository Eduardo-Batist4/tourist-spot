<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;

    protected $fillable = [
        'explorer_from_id',
        'explorer_to_id',
        'status'
    ];

    public function explorer_from_id() {
        return $this->belongsTo(Explorer::class, 'explorer_from_id');
    }

    public function explorer_to_id() {
        return $this->belongsTo(Explorer::class, 'explorer_to_id');
    }

    public function trade_items() {
        return $this->hasMany(TradeItem::class);
    }
}
