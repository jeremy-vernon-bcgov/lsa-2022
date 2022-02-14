<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessibilityOption extends Model
{

    public function attendees () {
        return $this->belongsTo(Attendee::class);
    }
}
