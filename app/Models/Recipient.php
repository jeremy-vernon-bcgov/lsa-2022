<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\RecipientFilter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class Recipient extends Model
{
    use HasFactory;

    protected $guarded = [];

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
    public function awards() {
        return $this->belongsToMany(Award::class)
        ->withPivot('qualifying_year', 'options', 'status')->withTimestamps();
    }

    public function notes() {
        return $this->morphMany(Note::class, 'notable');
    }

    // filters
    public function scopeFilter(Builder $builder, $request)
    {
        return (new RecipientFilter($request))->filter($builder);
    }

    // filter out draft (undeclared) recipient registrations
    // - applies to non-super-administrators
    public function scopeDeclared($query, $user)
    {
      return $user->hasRole('super-admin') ? $query : $query->where('is_declared', 1);
    }

    // filter user-associated organizations
    public function scopeUserOrgs($query, $user)
    {
      $orgs = [];
      foreach ($user->organizations()->get() as $org){
        $orgs[] = $org->id;
      }
      return count($orgs) !== 0
        ? $query->whereIn('organization_id', $orgs)
        : $query;
    }

    // include historical recipient boolean
    public function scopeHistorical($query)
    {
      return $query
      ->leftJoin('historical_recipients', 'recipients.employee_number','=','historical_recipients.employee_number')
      ->select('recipients.*', 'historical_recipients.id AS historical');
    }

}
