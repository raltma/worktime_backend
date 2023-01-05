@extends('layouts.main')

@section('title', $title)

@section('head')
<link rel="stylesheet" href="{{asset('/css/login.css')}}">
@endsection
@section('content')
    
        @if($errors->any())
        <div class="error">
            {{$errors->first()}}
        </div>
        @else
        <div class="errorFiller"></div>
        @endif
    
    <form autofill action="{{url('login')}}" method="post">
    @csrf
        <label>Kasutajanimi</label>
        <input required name="username" type="text">
        <label>Parool</label>
        <input required name="password" type="password">
        <input class="submitButton" type="submit" value="Logi sisse">
    </form>
@endsection