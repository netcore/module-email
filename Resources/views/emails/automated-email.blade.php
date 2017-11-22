@extends($config['layout'], [
    'title' => $automatedEmail->name
])

@section('content')
    {!! $automatedEmail->replaceVariables($job->user) !!}
@endsection
