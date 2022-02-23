<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;


class Recipient extends Model
{
    use HasFactory;

    protected $guarded = [];

    /* protected $fillable = [
        'idir',
        'guid',
        'employee_number',
        'full_name',
        'is_bcgeu_member',
        'award_id',
        'milestone',
        'retiring_this_year',
        'retirement_date',
        'survey_participation',
        'government_email',
        'government_phone_number',
        'organization_id',
        'branch_name',
        'personal_email',
        ''
        //TODO specify all mass-assignable fields.
        ];
    */

    public function personalAddress() {
        return $this->belongsTo(Address::class);
    }

    public function officeAddress() {
        return $this->belongsTo(Address::class);
    }

    public function supervisorAddress() {
        return $this->belongsTo(Address::class);
    }

    public function attendee() {
        return $this->morphOne(Attendee::class, 'attendable');
    }
    public function accommodations()
    {
        return $this->hasManyThrough(Accommodation::class, Attendee::class);
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function guest()
    {
        return $this->hasOne(Guest::class);
    }
    public function award()
    {
        return $this->belongsToMany(Award::class)->withPivot('options','status');
    }

    public function ceremony() {
        return $this->belongsTo(Ceremony::class, 'ceremony_id');
    }

    public function notes() {
        return $this->morphMany(Note::class, 'notable');
    }

}
