@extends('layouts.app')

@section('content')
    <an-post-component
        :post="{{ $post }}"
        user="{{ $check == true ? 'auth' : 'guest' }}"
    ></an-post-component>
@endsection