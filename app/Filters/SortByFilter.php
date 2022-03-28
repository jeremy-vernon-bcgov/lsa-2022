<?php

// SortByFilter.php

namespace App\Filters;
use Illuminate\Support\Facades\Log;

class SortByFilter
{
    public function filter($builder, $value)
    {

      Log::info('Sort By', array(
        'sort' => $value,
      ));
      $values = explode(" ", $value);
      $order = isset($values[1]) && $values[1] === 'desc' ? 'desc' : 'asc';
      $sortCol = $values[0];

      return $order === 'asc' ? $builder->orderBy($sortCol) : $builder->orderByDesc($sortCol);
    }
}
