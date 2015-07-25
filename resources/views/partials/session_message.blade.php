@if(Session::has('message'))
    <div class="alert alert-info">
      {{Session::get('message')}}
    </div>
@endif

@if(Session::has('error'))
    @if (count(Session::get('error')) > 0 && !is_string(Session::get('error')))
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach (Session::get('error')->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @else
    <div class="alert alert-danger">
      {{Session::get('error')}}
    </div>
    @endif
    
@endif

@if(Session::has('success'))
    <div class="alert alert-success">
      {{Session::get('success')}}
    </div>
@endif