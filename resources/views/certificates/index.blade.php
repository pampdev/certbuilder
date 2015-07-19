@extends('app')

@section('content')
<div class="container">
    <div class="row">
        {!! Form::open( array(
            'route' => 'certificates.preview',
            'method' => 'get',
            'target' => '_blank'
        ) ) !!}

        {!! textfield('name', 'Name', $errors, Input::get('name')) !!}
        {!! textfield('event_name', 'Event Name', $errors, Input::get('event_name')) !!}
        {!! textfield('event_place', 'Event Location', $errors, Input::get('event_place')) !!}
        {!! textfield('date', 'Event Date', $errors, Input::get('date')) !!}
        {!! textfield('title', 'Certificate Title', $errors, Input::get('title')) !!}
        
        {!! selectfield('theme', 'Theme', $themes, $errors) !!}

        <input type="submit" value="Preview" class="btn" />
        {!! Form::close() !!}     

    </div>
</div>
@endsection
