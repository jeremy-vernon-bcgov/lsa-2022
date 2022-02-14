<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesfCharity extends Model
{
    use HasFactory;

    public function region()
    {
        return $this->hasOne(PecsfRegion::class);
    }
}
