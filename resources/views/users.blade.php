@extends('layouts.main')

@section('title', $title)

@section('content')
    <h1>Kasutajad</h1>
    <pre>{{auth()->user()}}</pre>
@endsection