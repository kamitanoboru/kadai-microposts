@extends('layouts.app')

@section('content')

@if ($count_favorite > 0)
@include('microposts.microposts', ['microposts' => $favorites])
@endif
            
@endsection