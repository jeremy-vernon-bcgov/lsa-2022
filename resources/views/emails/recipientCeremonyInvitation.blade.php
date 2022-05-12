@extends('emails.formatted')
@section('title', 'Ceremony Invitation')
@section('content')

      <h2>Invitation</h2>

      <p>{{ $first_name }} {{ $last_name }},</p>

      You are hereby cordially invited to celebrate the Long Service Awards with us at:

      <table cellpadding="5" cellspacing="5" width="100%" bgcolor="#eeeeee">
        <tbody>
          <tr>
            <th>Location<th>
            <td>TBD</td>
          </tr>
          <tr>
            <th>Date and Time<th>
            <td>{{ $scheduled_datetime }}</td>
          </tr>
        </tbody>
      </table>

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
