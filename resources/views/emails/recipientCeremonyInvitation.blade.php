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
            </td>Issue: when you send an invitation (after assigning a ceremony night to Testrecipient) it doesn’t send the confirmation email and gives you this error message “Error occurred your action cannot be completed”. You don’t see the invite but it changes the status to invited.
We are holding off testing the decline function because we can’t get it to work currently due to this issue.

Confirmation screen:
Date/time format change: please update all dates to mirror this example: 5:45 p.m. on Wednesday, June 22, 2022 (Time on day, month date, year)
Missing dropdown? Text says: If you have an accessibility need not listed here – but nothing is listed?
Change text: Instead of I would like to bring one guest, change it to I would like to bring a guest

Invitation:
Should be on the blue template, not the black (Alisha and I will try and track this down for you)
Question: Name field will be First name space Last Name – yes? The system will make the font smaller for a name that is so long it will take up two lines – correct?
Date reads as Day, Month, Year (former versions did not include year but in this cycle it might be good.)
Ave changed to Avenue
Question/change: Change time to 5:45 p.m. (or will that update automatically when the ceremony nights are assigned in the system?)
          </tr>
          <tr>
            <th>Date<th>
            <td>{{ $scheduled_date }}</td>
          </tr>
        </tbody>
      </table>

      <p>This invitation is for the intended recipient and a guest.</p>

      <p>Dress: Business attire</p>

      <p>Doors open at {{ $scheduled_time }}</p>

      <p>Please click one of the RSVP links below to respond to this ceremony invitation.</p>

      <p>
        Please note that this RSVP link will expire at {{ $expiry }}.
        Once a link has been clicked, you will have one hour to complete and submit the RSVP form.
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
