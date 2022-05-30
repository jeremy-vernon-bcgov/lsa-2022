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
        and business attire is recommended.
        <b>Doors open at {{ $scheduled_time }}</b>. Your invitation is not required for entry to the ceremony.</p>

      <p>For information about travel reimbursement and taking time off, visit the
        <a href="https://longserviceawards.gww.gov.bc.ca/travel/">Long Service Awards website</a>
        or contact your workplace
        <a href="https://longserviceawards.gww.gov.bc.ca/contacts/">Long Service Awards contact</a>.
        If you have questions about the ceremony, email the
        <a href="mailto:longserviceawards@gov.bc.ca?subject=Question%20about%20LSA%20ceremony">
          Long Service Awards team</a>.</p>

      <p>Please find attached a confirmation of this award.</p>

      <p>We look forward to welcoming you to {{ $location_name }} in the fall!

      <p>Thank you,<br />Long Service Awards Team</p>


@endsection
