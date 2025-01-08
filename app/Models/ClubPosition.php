<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClubPosition extends Model
{
    protected $fillable = [
        'club_id',
        'position_name',
        'member_name',
        'email',
        'phone',
        'image_path',
        'description',
        'order'
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
