@extends('layouts.app')

@section('content')
    <div class="center jumbotron">
        <div class="text-center">
            <h1>Welcome to the Microposts</h1>
        </div>
                        <div style="text-align:center">
                    @php
                        $now=date('Y/m/d H:i:s');
                        print $now;
                    @endphp
                </div>
    </div>
@endsection