<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="{{ asset('templates/'.$theme.'/css/style.css') }}" rel="stylesheet">
<title>{{ $title }}</title>
</head>
<body>
    <div id="main-content">
        <div class="inner">
        <h1>CERTIFICATE</h1>
        <h2>OF APPRECIATION</h2>

        <p id="awarded-to" class="raleway200">to</p>
        <p id="name" {{ $name_style }}>{{ $name }}</p>
        <p class="smaller">for supporting the</p>
        <p id="event">{{ $event_name }}
        <p class="raleway200">
            By being one of the organizers on 
            <span id="date_month">{{ $date_month }}</span> 
            <span id="date_day">{{ $date_day }},</span> 
            <span id="date_year">{{ $date_year }}</span></p>
        <p>Held at {{ $event_place }}</p>
        </div>
    </div>

    <div id="border-bottom"><span>&nbsp;</span></div>
</html>