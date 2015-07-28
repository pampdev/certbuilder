<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="{{ asset('templates/'.$theme.'/css/style.css') }}" rel="stylesheet">
<title>{{ $title }}</title>
</head>
<body>
    <div id="border-top"><span>&nbsp;</span></div>
    <p id="title">Certificate of Attendance</p>
    <p>This is to certify that</p>
    <p id="name">{{ $name }}</p>
    <p>has successfully completed the course requirements for</p>
    <p id="certification">{{ $event_name }}</p>
    <p>On the 
        <span id="date_month">{{ $date_month }}</span> 
        <span id="date_day">{{ $date_day }},</span> 
        <span id="date_year">{{ $date_year }}</span>
    </p>
    <p>At: <span id="location">{{ $event_place }}</span>.</p>
    <p class="signed">Signed,</p>
    <p class="signature"><img src="{{ asset('templates/'.$theme.'/images/signature.png') }}" height="100" width="208"></p>
    <div id="border-bottom"><span>&nbsp;</span></div>

    @if (!isset($_GET['download']))
    <a style="position: absolute; right: 10px; top: 10px;" href="{{ url('certificates/preview?' . $_SERVER['QUERY_STRING']) }}&download=1">Download</a>
    @endif
</html>