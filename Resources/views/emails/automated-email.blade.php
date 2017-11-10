@extends($config['layout'], [
    'title' => $automatedEmail->name
])

@section('content')
    {!! email()->replaceUserData($automatedEmail->text, $user) !!}
@endsection
