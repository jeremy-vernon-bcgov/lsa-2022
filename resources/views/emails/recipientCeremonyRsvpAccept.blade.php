@extends('emails.formatted')
@section('title', 'RSVP Confirmation')
@section('content')

      <p>This is an automated email. No response is required.</p>

      <p>{{ $first_name }} {{ $last_name }},</p>

      <p>Thank you for confirming your attendance for your Long Service Awards ceremony.
        If you need to make changes to your RSVP information or cancel your attendance,
        please email the
        <a href="mailto:LongServiceAwards@gov.bc.ca?subject=RSVP%20to%20ceremony">Long Service Awards</a>
        team as soon as possible.</p>

      <p>The ceremony will take place on <strong>{{ $scheduled_date }}</strong> at
        <strong>
          @if (!empty($location_name) && !empty($street_address) && !empty($community) && !empty($province))
            {{ $location_name }}, {{ $street_address }}, {{ $community }}, {{ $province }}
          @else
            (TBD)
          @endif
        </strong>
        Dress: Business attire. <b>Doors open at {{ $scheduled_time }}</b>.</p>

      <p>For information about travel reimbursement and taking time off, visit the
        <a href="https://longserviceawards.gww.gov.bc.ca/travel/">Long Service Awards website</a>
        or contact your workplace
        <a href="https://longserviceawards.gww.gov.bc.ca/contacts/">Long Service Awards contact</a>.
        If you have questions about the ceremony, email the
        <a href="mailto:longserviceawards@gov.bc.ca?subject=Question%20about%20LSA%20ceremony">
          Long Service Awards team</a>.</p>

      <p>Please find confirmation of this award attached.</p>

      <p>We look forward to celebrating your important milestone at the ceremony.</p>

      <p>Thank you,<br /><br />Long Service Awards Team</p>


@endsection
