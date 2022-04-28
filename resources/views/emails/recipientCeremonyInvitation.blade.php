<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>

<p>Good afternoon (or morning) {{ $first_name }} {{ $last_name }},</p>

<p>Please RSVP for the following Long Service Award ceremony date:</p>

<p><strong>{{ $scheduled_datetime }}</strong></p>

<p><strong><a href="{{ $attendingURL }}">YES, I wish to attend</a></strong></p>

<p><strong><a href="{{ $declinedURL }}">NO, I do not want to attend the ceremony</a></strong></p>

<p></p>

</body>
</html>
