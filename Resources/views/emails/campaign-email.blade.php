@extends('email::emails.layout', [
    'title' => $campaign->name
])

@section('content')
    {!! email()->replaceUserData($campaign->text, $user) !!}
@endsection
