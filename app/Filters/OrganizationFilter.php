<?php

// OrganizationFilter.php

namespace App\Filters;
use Illuminate\Support\Facades\Log;

class OrganizationFilter
{
    public function filter($builder, $value)
    {
      return $builder->where('organization_id', $value);
    }
}
