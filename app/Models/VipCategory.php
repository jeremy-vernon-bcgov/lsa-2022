<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VipCategory extends Model
{
    use HasFactory;

    public function vips ()
    {
        return $this->hasMany(Vip::class);
    }

}
