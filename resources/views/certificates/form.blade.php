@extends('app')

@section('content')
<div class="container">
    <div class="row">

@if ($event)
{!! Form::model($event, ['route' => ['certificates.update', $event->id], 'method' => 'PUT']) !!}
@else
{!! Form::model($event, ['route' => ['certificates.store']]) !!}
@endif

@include('partials/form_error')    

<fieldset>
    <legend>Basic</legend>

    {!! selectfield('cert_type', 'Type', $cert_types, $errors, $event ? $event->cert_type : '') !!}    
    {!! selectfield('theme', 'Theme', $themes, $errors, $event ? $event->theme : '') !!}

    @if (!$event)
    {!! textfield('code', 'Unique Code', $errors) !!}
    <p><small>Used for uniquely identifying this event. Accepts alphanumeric small characters only [a-z0-9])</small></p>
    @endif
</fieldset>     
    

<fieldset>
    <legend>Event</legend>
    {!! textfield('event_name', 'Name', $errors, $event ? $event->event_name : '') !!}
    {!! textfield('event_place', 'Location', $errors, $event ? $event->event_place : '') !!}
    {!! textfield('event_date', 'Date', $errors, $event ? $event->event_date : '') !!}
</fieldset>   

<fieldset>
    <legend>PDF settings</legend>
    {!! textfield('filename_prefix', 'Prefix (optional)', $errors, $event ? $event->filename_prefix : '') !!}
    <p><small>Used for filename of the pdf (e.g. ca_prefix_uniquenameofpdf.pdf)</small></p>
</fieldset>     
    
        
        
            
        
        

        <input type="submit" value="Save" class="btn" />
        {!! Form::close() !!}     

    </div>
</div>
@endsection
