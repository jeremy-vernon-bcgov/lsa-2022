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

    public function getOptions($query)
    {
      return $query
      ->select(
        'recipients.id',
        'award_recipient.award_id AS award',
        'award_recipient.options->selections AS selections')
      ->flatten();
    }

}
