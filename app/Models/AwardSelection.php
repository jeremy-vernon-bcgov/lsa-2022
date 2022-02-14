<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardSelection extends Model
{
    use HasFactory;

    public function awardOption ()
    {
        return $this->belongsTo(AwardOption::class);
    }
    public function award()
    {
        return $this->belongsTo(Award::class);
    }
    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }
}
