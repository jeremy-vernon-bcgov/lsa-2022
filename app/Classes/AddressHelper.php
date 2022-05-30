<?php
namespace App\Classes;
use App\Models\Recipient;
use App\Models\Address;
use App\Models\Ceremony;
use Illuminate\Support\Facades\Log;

class AddressHelper
{

  /**
  * Check if address data is empty
  *
  * @param  array  $data
  * @return Boolean
  */
  private function isEmpty($data)
  {
    return isset($data['street_address']) && empty($data['street_address'])
    && isset($data['postal_code']) && empty($data['postal_code'])
    && isset($data['community']) && empty($data['community']);
  }

  /**
  * Create address record
  *
  * @param  \App\Models\Recipient  $recipient
  * @return \Illuminate\Http\Response
  */
  public function create($data)
  {
    $address = new Address($data);
    $address->save();
    return $address;
  }

  /**
  * Update address record
  *
  * @param  $id
  * @param  $data
  * @return \Illuminate\Http\Response
  */
  public function update($id, $data)
  {
    $address = Address::find($id);

    // update existing address record
    if (!empty($address)){
      $address->prefix = $data['prefix'];
      $address->pobox = array_key_exists("pobox", $data) ? $data['pobox'] : '';
      $address->street_address = $data['street_address'];
      $address->postal_code = $data['postal_code'];
      $address->community = $data['community'];
      $address->save();
    }

    return $address;
  }

  /**
  * Associate Address record with recipient
  *
  * @param Recipient $recipient
  * @param array $data
  */

  public function attachRecipient(Recipient $recipient, Array $data) {

    // check if input address data is empty
    $remove = self::isEmpty($data);

    // create/update and associate new address record
    switch ($data['type']) {

      case 'personal':
      $address = Address::find($recipient->personal_address_id);
      if ($remove) $recipient->personalAddress()->dissociate();

      Log::info('Address Update', array('remove' => $remove, 'existing' => $address, 'data' => $data));

      else empty($address)
        ? $recipient->personalAddress()->associate(self::create($data))
        : self::update($recipient->personal_address_id, $data);
      break;

      case 'office':
      $address = Address::find($recipient->office_address_id);
      if ($remove) $recipient->officeAddress()->dissociate();
      else empty($address)
        ? $recipient->officeAddress()->associate(self::create($data))
        : self::update($recipient->office_address_id, $data);
      break;

      case 'supervisor':
      $address = Address::find($recipient->supervisor_address_id);
      if ($remove) $recipient->supervisorAddress()->dissociate();
      else empty($address)
        ? $recipient->supervisorAddress()->associate(self::create($data))
        : self::update($recipient->supervisor_address_id, $data);
      break;
    }
  }

  /**
  * Associate Address record with ceremony
  *
  * @param Ceremony $ceremony
  * @param array $data
  */

  public function attachCeremony(Ceremony $ceremony, Array $data) {

    // check if input address data is empty
    $remove = self::isEmpty($data);

    $address = Address::find($ceremony->location_address_id);
    if ($remove) $ceremony->locationAddress()->dissociate();
    else empty($address)
      ? $ceremony->locationAddress()->associate(self::create($data))
      : self::update($ceremony->location_address_id, $data);
  }
}
