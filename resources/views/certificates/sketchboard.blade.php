@extends('app')

@section('content')
<div class="container">
    <div class="row">
        {!! Form::open( array(
            'route' => 'certificates.preview',
            'method' => 'get',
            'target' => '_blank'
        ) ) !!}

        {!! textfield('name', 'Name', $errors) !!}
        {!! textfield('event_name', 'Event Name', $errors, $event ? $event->event_name : '') !!}
        {!! textfield('event_place', 'Event Location', $errors, $event ? $event->event_place : '') !!}
        {!! textfield('date', 'Event Date', $errors, $event ? $event->event_date : '') !!}
        {!! textfield('title', 'Certificate Title', $errors, $event ? $event->typeTitle : '') !!}
            
        {!! selectfield('theme', 'Theme', $themes, $errors, $event ? $event->theme : '') !!}

        <input type="submit" value="Preview" class="btn" />
        {!! Form::close() !!}     

    </div>
</div>
@endsection
