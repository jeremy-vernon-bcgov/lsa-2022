<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    use HasFactory;

    public function recipients()
    {
        return $this->belongsToMany(Recipient::class)->withPivot('options','status');
    }

}
