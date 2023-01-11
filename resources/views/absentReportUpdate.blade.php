@extends('layouts.main')

@section('title', $title)
    <link href="{{asset('/slimselect/slimselect.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('/slimselect/slimselect.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('/css/updateForms.css')}}">
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
<form method="post" autocomplete="off" action="{{url('absentReport/update')}}">
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
    <div class="dateInputContainer">
        <label>Kuupäev:</label>
        <div>
            <div>
                <div>Algus</div>
                <input id="date" required name="date_start" type="date" value="{{$report->date_start}}">
            </div>
            <div>
                <div>Lõpp</div>
                <input id="date" required name="date_end" type="date" value="{{$report->date_end}}">
            </div>
        </div>
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
        <label>Tunnid:</label>
        <input name="hours" type="number" min=0 value="{{$report->hours}}">
    </div>
    <div>
        <label>Põhjus:</label>
        <div class="selectWrapper">
        <select id="reasons" name="reason">
        @foreach($reasons as $reason)
            <optgroup label="{{$reason->header}}">
            @foreach ($reason->items as $item)
                <option @if($item->code === $report->reason) selected @endif value="{{$item->code}}">{{$item->name}}</option>
            @endforeach
            </optgroup>
        @endforeach
        </select>
    </div>
    </div>
    <div>
        <label>Kinnitatud:</label>
        <input @if($report->confirmed === 1) checked @endif id="confirmed" name="confirmed" type="checkbox">
    </div>
    <input type="submit" value="Salvesta">
</form>
<script type="text/javascript">

let reasonSelect = new SlimSelect({
        select: '#reasons',
        settings: {
            closeOnSelect: true,
        }})
let userSelect = new SlimSelect({
    select: '#users',
    settings: {
        closeOnSelect: true,
    }})
</script>
@endsection