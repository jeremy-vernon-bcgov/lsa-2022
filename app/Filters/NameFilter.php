<?php

// NameFilter.php

namespace App\Filters;
use Illuminate\Support\Facades\Log;

class NameFilter
{
    public function filter($builder, $value)
    {
      return $builder
      ->where('first_name', 'like', '%' . $value . '%')
      ->orWhere('last_name', 'like', '%' . $value . '%');
    }
}
