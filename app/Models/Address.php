<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['prefix', 'street_address', 'postal_code', 'community'];

    // public function recipient() {
    //     return $this->belongsTo(Recipient::class, 'office_address_id')
    //         ->orWhere('recipient.personal_address_id', $this->id)
    //         ->orWhere('recipient.supervisor_address_id', $this->id);
    // }
    //
    // public function personalAddress()
    // {
    //     return $this->belongsTo(Account::class, 'from_account_id', 'id');
    // }
}
