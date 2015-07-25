@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <table class="table table-stripe">
            <tr>
                <th>#</th>
                <th>Event Name</th>
                <th>Certificate Type</th>
                <th>Action</th>
            </tr>
            @foreach ($list as $item)
            <tr>
                <td>{!! $item->id !!}</td>
                <td>{!! $item->event_name !!}</td>
                <td>{!! $item->typeTitle !!}</td>
                <td>
                    <a class="btn btn-primary" href="{!! url('certificates/' . $item->id) !!}">view</a>
                    <a class="btn btn-primary" href="{!! url('sketchboard?setting=' . $item->id) !!}">sketchboard</a>
                </td>
            </tr>
            @endforeach
        </table> 

    </div>
</div>
@endsection
