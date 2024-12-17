<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventDocument extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'path'
    ];

    /**
     * Get the event that owns the document
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
