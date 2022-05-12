<?php

// CeremonyFilter.php

namespace App\Filters;
use Illuminate\Support\Facades\Log;

class CeremonyFilter
{

    public function filter($builder, $value)
    {
      $statuses = ['assigned', 'waitlisted', 'invited', 'attending', 'declined', 'expired'];

      // apply ceremony opt-out filter
      if ($value === 'true' || $value === 'false') {
        $filteredValue = $value === 'true' ? 1 : 0;
        return $builder->where('ceremony_opt_out', $filteredValue);
      }
      // apply ceremony status filter
      elseif (in_array($value, $statuses)) {
        Log::info('Ceremony Status', array(
          'Filter By' => $value,
        ));
        return $builder->whereHas('attendee', function($q) use($value) {
          $q->where('status', '=', $value);
        });
      }
      // apply ceremony ID filter
      else {
        return $builder->whereHas('attendee', function($q) use($value) {
          $q->where('ceremonies_id', '=', $value);
        });
      }
    }
}
