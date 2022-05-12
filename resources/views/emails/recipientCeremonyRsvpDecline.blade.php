@extends('emails.formatted')
@section('title', 'Ceremony Invitation')
@section('content')
      <h2>RSVP Confirmation</h2>

      <p>{{ $first_name }} {{ $last_name }},</p>

      <p>This email confirms you have selected not to attend the Long Service Awards
        ceremony.</p>
      <br><br>
      <p>Thank you for your response!</p>
@endsection
