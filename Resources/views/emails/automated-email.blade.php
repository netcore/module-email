@extends($config['layout'], [
    'title' => $automatedEmail->name
])

@section('content')
    {!! $automatedEmail->replaceVariables($user, $variables) !!}
@endsection
