@extends('app')

@section('content')
<div class="container">
    <div class="row">

@if ($event)
{!! Form::model($event, ['route' => ['certificates.update', $event->id], 'method' => 'PUT']) !!}
@else
{!! Form::model($event, ['route' => ['certificates.store']]) !!}
@endif

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif        
        
        @if (!$event)
        {!! textfield('code', 'Unique Code', $errors) !!}
        @endif
        
        {!! textfield('event_name', 'Event Name', $errors, $event ? $event->event_name : '') !!}
        {!! textfield('event_place', 'Event Location', $errors, $event ? $event->event_place : '') !!}
        {!! textfield('event_date', 'Event Date', $errors, $event ? $event->event_date : '') !!}
        {!! textfield('title', 'Certificate Title', $errors, $event ? $event->typeTitle : '') !!}
        {!! textfield('filename_prefix', 'Filename Prefix', $errors, $event ? $event->filename_prefix : '') !!}
            
        {!! selectfield('cert_type', 'Type', $cert_types, $errors, $event ? $event->cert_type : '') !!}    
        {!! selectfield('theme', 'Theme', $themes, $errors, $event ? $event->theme : '') !!}

        <input type="submit" value="Save" class="btn" />
        {!! Form::close() !!}     

    </div>
</div>
@endsection
