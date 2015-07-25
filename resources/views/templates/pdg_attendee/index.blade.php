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
        <h2>OF ATTENDANCE</h2>

        <p id="awarded-to" class="raleway200">This is to certify that</p>
        <p id="name" {{ $name_style }}>{{ $name }}</p>
        <p class="smaller">has attended the</p>
        <p id="event">{{ $event_name }}</p>
        <p class="raleway200">
            Held at {{ $event_place }}<br/>on 
            <span id="date_month">{{ $date_month }}</span> 
            <span id="date_day">{{ $date_day }},</span> 
            <span id="date_year">{{ $date_year }}</span></p>
        </div>
    </div>

    <div id="border-bottom"><span>&nbsp;</span></div>
    @if (!isset($_GET['download']))
    <a style="position: absolute; right: 10px; top: 10px;" href="{{ url('certificates/preview?' . $_SERVER['QUERY_STRING']) }}&download=1">Download</a>
    @endif
</html>