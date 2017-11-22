@extends($config['layout'], [
    'title' => $campaign->name
])

@section('content')
    {!! $campaign->replaceVariables($user) !!}
@endsection
