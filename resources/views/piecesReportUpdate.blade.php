@extends('layouts.main')

@section('title', $title)
@section('head')
<link rel="stylesheet" href="{{asset('/css/piecesReportUpdate.css')}}">
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
<form method="post" autocomplete="off" action="{{url('piecesReport/update')}}">
@csrf
    <div>
        <label>Esitaja:</label>
        <select name="user_id">
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
        <label>Töökoht:</label>
        <select id="workplace" name="workplace">
        @foreach($workplaces as $w)
            <option @if($w->name === $report->workplace) selected @endif value="{{$w->name}}">{{$w->name}}</option>
        @endforeach
        </select>
    </div>
    <section class="classificationsContainer">
        <div class="classificationsHeader">
            <div>Liigitused</div>
            <div>Tükid</div>
            <div>Ületunnid</div>
            <div><button type="button" onclick="addRow()">+</button></div>
        </div>
        <div class="classificationsRowContainer">
            @foreach($report->classifications as $row)
            <div class="classificationsRow" id="row{{$loop->index}}">
                <div>
                    <select id="reasons" name="classification[{{$loop->index}}]">
                    @foreach($classifications as $c)
                        <option @if($c->id === $row->classification->id) selected @endif value="{{$c->id}}">{{$c->name}}</option>
                    @endforeach
                    </select>
                </div>
                <div><input type="number" name="count[{{$loop->index}}]" value="{{$row->quantity}}"></div>
                <div><input type="checkbox" name="overtime[{{$loop->index}}]"@if($row->overtime === 1) checked @endif></div>
                <div>@if(!$loop->first)<button type="button" onclick="deleteRow({{$loop->index}})" class="removeButton">-</button>@endif</div>
            </div>
            @endforeach
        </div>
    </section>
    <div>
        <label>Kinnitatud:</label>
        <input @if($report->confirmed === 1) checked @endif id="confirmed" name="confirmed" type="checkbox">
    </div>
    <input class="submitButton" type="submit" value="Salvesta">
</form>
<script>
let rowCount = {{sizeof($report->classifications)}} - 1
function addRow(){
    rowCount = rowCount + 1;
    const rowList = document.getElementsByClassName("classificationsRowContainer")[0];
    let clone = rowList.firstElementChild.cloneNode(true)
    clone.id = "row"+rowCount;
    clone.children[0].firstElementChild.name=`classification[${rowCount}]`;
    clone.children[0].firstElementChild.value=1;
    clone.children[1].firstElementChild.name=`count[${rowCount}]`;
    clone.children[1].firstElementChild.value=0;
    clone.children[2].firstElementChild.name=`overtime[${rowCount}]`;
    clone.children[2].firstElementChild.checked=false;

    let btn = document.createElement("button")
    btn.innerHTML = "-"
    btn.type = "button";
    btn.onclick = (function(count) {
        return function() {
            deleteRow(count);
        }
    })(rowCount);
    btn.classList.add("removeButton");
    clone.lastElementChild.appendChild(btn);
    rowList.appendChild(clone);
}

function deleteRow(rowId){
    document.getElementById("row"+rowId).remove();
}

</script>
@endsection