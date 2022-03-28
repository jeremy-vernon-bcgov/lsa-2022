<?php

// HistoricalFilter.php

namespace App\Filters;
use Illuminate\Support\Facades\Log;

class HistoricalFilter
{
    public function filter($builder, $value)
    {
      return $value === 'true'
      ? $builder->whereNotNull('historical_recipients.id')
      : $builder->whereNull('historical_recipients.id');
    }
}
