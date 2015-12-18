@if (Session::has('alerts'))
    @foreach (Session::get('alerts') as $alert)
        @include ('forum::partials.alert', $alert)
    @endforeach
@endif

@if ($errors->has())
    @foreach ($errors->all() as $error)
        @include ('forum::partials.alert', ['type' => 'danger', 'message' => $error])
    @endforeach
@endif
