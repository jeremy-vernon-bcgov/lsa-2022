<?php

// MilestoneFilter.php

namespace App\Filters;
use Illuminate\Support\Facades\Log;

class CeremonyOptOutFilter
{
  public function filter($builder, $value)
  {
    $filteredValue = $value === 'true' ? 1 : 0;
    return $builder->where('ceremony_opt_out', $filteredValue);
  }

}
