@extends('layouts.app')

@section('content')
    <all-post-component
        user="{{ $check == true ? 'auth' : 'guest' }}"
    ></all-post-component>
@endsection