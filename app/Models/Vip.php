<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vip extends Model
{
    use HasFactory;

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    public function dietaryRestrictions ()
    {
        return $this->hasManyThrough(DietaryRestriction::class, Attendee::class);
    }
    public function accessibilityOptions ()
    {
        return $this->hasManyThrough(AccessibilityOption::class, Attendee::class);
    }
    public function guests ()
    {
        return $this->hasMany(Guest::class);
    }
}
