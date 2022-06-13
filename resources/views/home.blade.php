@extends('layouts.app')

@section('content')
    <home-component user-name="{{Auth::user()->name}}"></home-component>
@endsection
