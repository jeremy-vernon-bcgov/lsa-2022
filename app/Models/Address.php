<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    public function recipient() {
        return $this->belongsTo(Recipient::class, 'office_address_id')
            ->orWhere('recipient.personal_address_id', $this->id)
            ->orWhere('recipient.supervisor_address_id', $this->id);
    }
}
