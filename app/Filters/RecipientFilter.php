<?php

// RecipientFilter.php

namespace App\Filters;

use App\Filters\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

class RecipientFilter extends AbstractFilter
{
    protected $filters = [
        'status' => StatusFilter::class,
        'historical' => HistoricalFilter::class,
        'organization' => OrganizationFilter::class,
        'retirement' => RetirementFilter::class,
        'milestone' => MilestoneFilter::class,
        'ceremony' => CeremonyFilter::class,
        'employee_number' => EmployeeNumberFilter::class,
        'name' => NameFilter::class,
        'sort' => SortByFilter::class,
    ];
}
