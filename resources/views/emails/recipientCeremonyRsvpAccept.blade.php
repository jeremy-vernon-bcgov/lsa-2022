@extends('emails.formatted')
@section('title', 'RSVP Confirmation')
@section('content')

      <h2>RSVP Confirmation</h2>

      <p>{{ $first_name }} {{ $last_name }},</p>

      <p>This email confirms your attendance at the Long Service Awards
        ceremony with us at:</p>

      <table cellpadding="0" cellspacing="5" width="100%" bgcolor="#eeeeee">
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

      <p>Please find attached a PDF confirmation of this Award.</p>

      <p>We're looking forward to seeing you there!</p>

@endsection
