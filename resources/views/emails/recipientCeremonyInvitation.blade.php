@extends('emails.formatted')
@section('title', 'Ceremony Invitation')
@section('content')

      <h3>The Government of British Columbia<br>is pleased to invite</h3>

      <h2>{{ $first_name }} {{ $last_name }}</h2>

      <h4>to the Long Service Awards Ceremony</h4>

      <table cellpadding="5" cellspacing="5" width="100%" bgcolor="#eeeeee">
        <tbody>
          <tr>
            <th>Location<th>
            <td>
              @if (!empty($location_name) && !empty($street_address) && !empty($community) && !empty($province))
                  {{ $location_name }}<br>{{ $street_address }}<br>{{ $community }}, {{ $province }}
              @else
                  TBD
              @endif
            </td>
          </tr>
          <tr>
            <th>Date<th>
            <td>{{ $scheduled_date }}</td>
          </tr>
        </tbody>
      </table>

      <p>This invitation is for the intended recipient and a guest.</p>

      <p>Dress: Business attire</p>

      <p>Doors open at <strong>{{ $scheduled_time }}</strong></p>

      <p>Please click one of the RSVP links below to respond to this ceremony invitation.</p>

      <p>
        Please note that this RSVP link will expire at <strong>{{ $expiry }}</strong>.
        Once a link has been clicked, you will have <strong>one hour</strong> to complete and submit the RSVP form.
      </p>

      <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
        <tr>
          <td style="background:#D84A38;text-align:center;">
            <div>
              <a
              href="{{ $attendingURL }}"
              style="background-color:#D84A38;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:26px;text-align:center;text-decoration:none;-webkit-text-size-adjust:none;">YES, I wish to attend</a>
            </div>
          </td>
          <td width="25">&nbsp;</td>
          <td style="background:#D84A38;text-align:center">
            <div>
              <a
              href="{{ $declinedURL }}"
              style="background-color:#D84A38;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:26px;text-align:center;text-decoration:none;-webkit-text-size-adjust:none;">NO, I will not be attending</a>
            </div>
          </td>
        </tr>
      </table>

@endsection
