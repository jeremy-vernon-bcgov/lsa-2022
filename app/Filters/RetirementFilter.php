<?php

// RetirementFilter.php

namespace App\Filters;
use Illuminate\Support\Facades\Log;

class RetirementFilter
{
    public function filter($builder, $value)
    {
      $filteredValue = $value === 'true' ? 1 : 0;
      return $builder->where('retiring_this_year', $filteredValue);
    }
}
