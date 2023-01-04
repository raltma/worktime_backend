@extends('layouts.main')

@section('title', $title)

@section('head')
@endsection

@section('content')
<h1 class="title">{{$title}}</h1>
    @if($errors->has('error'))
        <div class="error">{{$errors->first()}}</div>
    @elseif($errors->has('message'))
        <div class="message">{{$errors->first()}}</div>
    @else
        <div class="errorFiller"></div>
    @endif
<form method="post" autocomplete="off" action="{{url('hourReport/update')}}">
@csrf
    <div>
        <label>Kasutaja:</label>
        <span>{{$report->user->name}}</span>
        <input id="selectedUser" type="hidden" name="id" value="{{$report->id}}">
    </div>
    <div>
        <label>Kuupäev:</label>
        <input id="date" required name="date" type="date" value="{{$report->date_selected}}">
    </div>
    <div>
        <span>Vahetus:</span>
        <label>
            2
            <input @if($report->shift === 2) checked @endif name="shift" type="radio" value="2">
        </label>
        <label>
            3
            <input @if($report->shift === 3) checked @endif name="shift" type="radio" value="3">
        </label>
    </div>
    <div>       
        <label>Tunnid:</label>
        <input name="hours" type="number" min=0 value="{{$report->hours}}">
    </div>
    <div>
        <label>Ületunnid:</label>
        <input name="overtime" type="number" value="{{$report->overtime_hours}}">
    </div>
    <div>
        <label>Kinnitatud:</label>
        <input @if($report->confirmed === 1) checked @endif id="confirmed" name="confirmed" type="checkbox">
    </div>
    <input type="submit" value="Salvesta">
</form>

@endsection