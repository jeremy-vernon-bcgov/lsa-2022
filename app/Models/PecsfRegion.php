<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PecsfRegion extends Model
{
    use HasFactory;

    public function pecsfCharities()
    {
        return $this->hasMany(PecsfCharity::class);
    }
}
