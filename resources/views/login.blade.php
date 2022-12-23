@extends('layouts.main')

@section('title', $title)

@section('content')
    
        @if($errors->any())
        <div class="error">
            {{$errors->first()}}
        </div>
        @else
        <div class="errorFiller"></div>
        @endif
    
    <form action="{{url('login')}}" method="post">
    @csrf
        <label>
            Kasutajanimi
            <input required name="username" type="text">
        </label>
        <label>
            Parool
            <input required name="password" type="password">
        </label>
        <input type="submit" value="Logi sisse">
    </form>
@endsection