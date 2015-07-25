@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <h1>{!! $event->event_name !!}</h1>

        Type: {!! $event->typeTitle !!}
        <br/>
        Place: {!! $event->event_place !!}
        <br/>
        Date: {!! $event->event_date !!}
        <br/>
        Theme: {!! $event->theme !!}
        <br/>
        Filename Prefix: {!! $event->filename_prefix !!}
        <br/>
        <a class="btn btn-primary" href="{!! url('certificates/' . $event->id . '/edit') !!}">edit</a>
        <a class="btn btn-primary" href="{!! url('sketchboard?setting=' . $event->id) !!}">sketchboard</a>

    </div>
</div>
@endsection
