<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Club;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'description',
        'amount',
        'status',
        'transaction_date',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
