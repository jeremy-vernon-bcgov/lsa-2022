<?php

// StatusFilter.php

namespace App\Filters;
use Illuminate\Support\Facades\Log;

class StatusFilter
{
    public function filter($builder, $value)
    {
      $filteredValue = $value === 'true' ? 1 : 0;
      return $builder->where('is_declared', $filteredValue);
    }
}
