<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <style>

  @font-face {
    font-family: 'Cormorant-Garamond-Light';
    src: url({{storage_path('fonts/CormorantGaramond-Light.ttf')}}) format("truetype");
    font-style: normal;
  }

  @font-face {
    font-family: 'Cormorant-Garamond-Regular';
    src: url({{storage_path('fonts/CormorantGaramond-Regular.ttf')}}) format("truetype");
    font-style: normal;
  }

  @font-face {
    font-family: 'Cormorant-Garamond-Italic';
    src: url({{storage_path('fonts/CormorantGaramond-Italic.ttf')}}) format("truetype");
    font-style: normal;
  }

  @page {
    margin: 0px;
  }

  body {
    margin: 0px;
    background-image: url('https://longserviceawards.gww.gov.bc.ca/rsvp/assets/certificate-background.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
  }

  table {
    margin: auto;
    width: 400pt;
  }

  h2 {
    font-family: 'Cormorant-Garamond-Light';
    font-size: 24pt;
    font-weight: normal;
  }

  h3 {
    font-family: 'Cormorant-Garamond-Regular';
    font-size: 22px;
    font-weight: bold;
  }

  h4 {
    font-family: 'Cormorant-Garamond-Light';
    font-size: 20pt;
    font-weight: normal;
  }

  p, td {
    font-family: 'Cormorant-Garamond-Italic';
    font-size: 18pt;
  }

  div.certificate-body {
    text-align: center;
  }

  div.coat-of-arms {
    width: 131pt;
    height: 149pt;
    margin: auto;
  }

  img.coat-of-arms {
    width: 130pt;
    height: 148pt;
    margin-top: 96pt;
  }

  div.template-text {
    margin: 100pt auto 0 auto;
    width: 80%;
  }

  .recipient-name, .ceremony-date {
    font-size: 28pt;
  }

  div.recipient-name {
    width: 580pt;
    text-align: center;
    position: absolute;
    top: 325pt;
  }
  div.ceremony-date {
    width: 580pt;
    text-align: center;
    position: absolute;
    top: 450pt;
  }


  </style>

  <title>Long Service Awards: Printable Keepsake Invitation</title>
</head>
<body>
  <div class="certificate-body">

    <div class="coat-of-arms">
      <img class="coat-of-arms" src="https://longserviceawards.gww.gov.bc.ca/rsvp/assets/coat-of-arms.png">
    </div>
    <div class="template-text">

      <h2>The Government of British Columbia<br>is pleased to invite</h2>

      <h3>{{ $first_name }} {{ $last_name }}</h3>

      <h4>to the Long Service Awards ceremony on</h4>

      <h3>{{ $scheduled_date }}</h3>

      <h4>
        @if (!empty($location_name) && !empty($street_address) && !empty($community) && !empty($province))
            {{ $location_name }}<br>{{ $street_address }}<br>{{ $community }}, {{ $province }}
        @else
            TBD
        @endif
      </h4>

      <p>This invitation is for the intended recipient and one guest.</p>

      <table cellpadding="5" cellspacing="5">
        <tbody>
          <tr>
            <td>Dress: Business attire</td>
            <td style="text-align:right">Doors open at {{$scheduled_time}}</td>
          </tr>
        </tbody>
      </table>

  </div>

  </div>
</body>
</html>
