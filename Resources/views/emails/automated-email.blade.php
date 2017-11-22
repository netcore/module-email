@extends($config['layout'], [
    'title' => $automatedEmail->name
])

@section('content')
    {!! $automatedEmail->replaceVariables($job->user, $job->variable_list) !!}
@endsection
