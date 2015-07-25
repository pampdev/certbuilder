@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <h1>Events List</h1>

        @if ($list->count())
        <a class="btn btn-primary" href="{!! url('certificates/create') !!}">new event</a>
        <table class="table table-striped">
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
                    <a class="btn btn-primary" href="{!! url('sandbox?setting=' . $item->id) !!}">preview</a>

                    {!! Form::open(['route' => ['certificates.destroy', $item->id], 'method' => 'delete']) !!}
                        <button type="submit" data-submit-confirm-text="Are you sure you want to delete this event?" class="btn btn-warning delete">delete</button>
                    {!! Form::close() !!}

                </td>
            </tr>
            @endforeach
        </table> 
        @else
        <p>No event found. <a href="{!! url('certificates/create') !!}">Want to create your first event?</a></p>
        @endif

    </div>
</div>

@endsection

@section('footer')
<script type="text/javascript">
    $(document).ready(function(){

        $(".delete").click(function(e){
            var $el = $(this);
            e.preventDefault();
            var confirmText = $el.attr('data-submit-confirm-text');
            var result = confirm(confirmText);
            if (result) {
                $el.closest('form').trigger('submit');
            }
        });

});
</script>
@endsection
