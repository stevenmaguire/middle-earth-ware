@extends('layouts.master')

@section('content')
    <div class="title">Welcome to Middle Earth</div>
    <p><a href="{{ route('map') }}">Explore the map</a> or <a href="{{ route('gallery') }}">See the sights</a></p>
@endsection
