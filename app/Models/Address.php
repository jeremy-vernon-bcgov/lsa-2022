<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = ['prefix', 'pobox', 'street_address', 'postal_code', 'community'];

    public function recipients () {
        return $this->hasMany(Recipient::class);
    }

    public function ceremonies () {
        return $this->hasMany(Ceremony::class);
    }
}
