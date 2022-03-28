<?php

// MilestoneFilter.php

namespace App\Filters;
use Illuminate\Support\Facades\Log;

class MilestoneFilter
{
    public function filter($builder, $value)
    {
      return $builder->where('milestones', $value);
    }
}
