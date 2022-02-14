<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    public function recipients() {
        return $this->hasMany(Recipient::class, 'office_community_id');
    }

}
