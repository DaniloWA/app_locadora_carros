@extends('layouts.app')

@section('content')
    @guest
        <index-component></index-component>
    @endguest
    @auth
        <home-component user-name="{{Auth::user()->name}}"></home-component>
    @endauth
@endsection
