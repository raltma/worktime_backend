@extends('layouts.main')

@section('title', $title)

@section('head')
<link href="{{asset('/slimselect/slimselect.css')}}" rel="stylesheet">
<script type="text/javascript" src="{{asset('/slimselect/slimselect.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('/css/updateForms.css')}}">
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
        <label>Esitaja:</label>
        <select id="users" name="user_id">
            @foreach ($users as $user)
                <option @if ($user->id === $report->user->id) selected @endif value="{{$user->id}}">{{$user->name}}</option>
            @endforeach
        </select>
        <input type="hidden" name="id" value="{{$report->id}}">
    </div>
    <div>
        <label>Kuupäev:</label>
        <input id="date" required name="date" type="date" value="{{$report->date_selected}}">
    </div>
    <div>
        <label>Vahetus:</label>
        <div>
            <label>
                2
                <input @if($report->shift === 2) checked @endif name="shift" type="radio" value="2">
            </label>
            <label>
                3
                <input @if($report->shift === 3) checked @endif name="shift" type="radio" value="3">
            </label>
        </div>
    </div>
    <div>
        <label>Osakond:</label>
        <div>
            <label>
                Puudub
                <input @if($report->department === "Puudub") checked @endif name="department" type="radio" value="Puudub">
            </label>
            <label>
                Tekk
                <input @if($report->department === "Tekk") checked @endif name="department" type="radio" value="Tekk">
            </label>
            <label>
                Padi
                <input @if($report->department === "Padi") checked @endif name="department" type="radio" value="Padi">
            </label>
        </div>
    </div>
    <div>       
        <label>Tunnid:</label>
        <input name="hours" type="number" min=0 value="{{$report->hours}}">
    </div>
    <div class="overtimeDiv">
        <label>Ületunnid:</label>
        <div>
            <input name="overtime_hours" type="number" min=0 max=99 value="{{floor($report->overtime_hours)}}">h
            <input name="overtime_minutes" type="number" min=0 max=59 value="{{round(($report->overtime_hours - floor($report->overtime_hours)) * 60 )}}">min
        </div>
    </div>
    <div>
        <label>Kinnitatud:</label>
        <input @if($report->confirmed === 1) checked @endif id="confirmed" name="confirmed" type="checkbox">
    </div>
    <input class="submitButton" type="submit" value="Salvesta">
</form>
<script type="text/javascript">
let userSelect = new SlimSelect({
    select: '#users',
    settings: {
        closeOnSelect: true,
    }})
</script>
@endsection