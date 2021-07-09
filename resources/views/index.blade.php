@extends('layouts.app')

@section('content')
    <an-post-component
        :event="{{ $event }}"
        user="{{ $check == true ? 'auth' : 'guest' }}"
    ></an-post-component>
@endsection