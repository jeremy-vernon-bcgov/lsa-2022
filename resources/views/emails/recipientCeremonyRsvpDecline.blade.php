@extends('emails.formatted')
@section('title', 'Ceremony Invitation')
@section('content')

      <div class="coat-of-arms">
           <img class="coat-of-arms" src="https://longserviceawards.gww.gov.bc.ca/wp-content/uploads/2022/05/coat-of-arms.png">
      </div>

      <p>This is an automated email. No response is required.</p>

      <p>{{ $first_name }} {{ $last_name }},</p>

      <p>Thank you for confirming that you will not be attending your Long Service Award ceremony.</p>

      <p>Long Service Awards are presented the year you register. <b>Your award will be mailed to
        your organization following your organizations 2022 ceremony, and your
        <a href="https://longserviceawards.gww.gov.bc.ca/contacts/">ministry/organization
        contact</a> will make arrangements to have your award presented to you.</b></p>

      <p>Note: You can defer your attendance at the ceremony for one year. You will need to register
        again in March/April 2023 to take advantage of the one-year deferral.</p>

      <p>If you have any questions, connect with your workplace
        <a href="https://longserviceawards.gww.gov.bc.ca/contacts/">Long Service Award contact</a> or email
        the
        <a href="mailto:longserviceawards@gov.bc.ca?subject=Question%20about%20LSA%20ceremony">
          Long Service Awards team</a>.</p>

@endsection
