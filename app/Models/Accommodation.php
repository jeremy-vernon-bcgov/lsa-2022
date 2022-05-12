<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'short_name',
        'type',
        'description'
    ];

    public function attendees() {
        return $this->belongsToMany(Attendee::class);
    }
}
