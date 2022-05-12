@extends('layouts.reports')
@section('content')
<h1>Long Service Awards: Confirmation</h1>
<p>{{ $first_name }} {{ $last_name }},</p>

You are hereby confirmed to attend the Long Service Awards with us at:

<table cellpadding="5" cellspacing="5" width="100%" bgcolor="#eeeeee">
  <tbody>
    <tr>
      <th>Location<th>
      <td>{{ $location }}</td>
    </tr>
    <tr>
      <th>Date and Time<th>
      <td>{{ $scheduled_datetime }}</td>
    </tr>
  </tbody>
</table>

@endsection
