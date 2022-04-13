<?php

// NameFilter.php

namespace App\Filters;
use Illuminate\Support\Facades\Log;

class EmployeeNumberFilter
{
    public function filter($builder, $value)
    {
      return $builder->where('recipients.employee_number', 'like', '%' . $value . '%');
    }
}
