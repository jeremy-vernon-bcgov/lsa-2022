<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Organization extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function attendees() {
        return $this->hasManyThrough();
    }

    public function vips () {
        return $this->hasMany(Vip::class);
    }

    public function recipients () {
        return $this->hasMany(Recipient::class);
    }

    public function recipientCeremonies ()
    {
        return $this->hasMany(RecipientCeremony::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
