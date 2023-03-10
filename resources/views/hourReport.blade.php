@extends('layouts.main')

@section('title', $title)

@section('head')
    <link href="{{asset('/tabulator-master/dist/css/tabulator_bootstrap5.css')}}" rel="stylesheet">
    <link href="{{asset('/slimselect/slimselect.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('/tabulator-master/dist/js/tabulator.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/slimselect/slimselect.min.js')}}"></script>
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
<div id="table"></div>

<script type="text/javascript">
    let tableData = {{ Js::from($reports) }};
    let table = new Tabulator("#table", {
        data: tableData,
        layout: "fitColumns",
        pagination: true,
        height:"70vh",
        initialHeaderFilter:[{field:"confirmed", value:false}],
        initialSort:[
            {column:'date_selected', dir:"desc"},
        ],
        columns: [
            {title:"Esitaja nimi", field:"user.name", width:150, headerFilter:true},
            {title:"Kuupäev", field:"date_selected", headerFilter:true},
            {title:"Vahetus", field:"shift", headerFilter:true},
            {title:"Osakond", field:"department", headerFilter:true},
            {title:"Tunnid", field:"hours", headerFilter:true},
            {title:"Ületunnid", field:"overtime_hours", headerFilter:true, formatter:function(cell, formatterParams, onRendered){
                if(cell.getRow().getData().overtime === 1){
                    let time = cell.getValue();
                    let hours = Math.floor(time);
                    let minutes = Math.round((time % 1)*60)
                    return hours + "h " + minutes + "min" 
                }
                return "-" //return the contents of the cell;
            }},
            {title:"Biostari kontroll", field:"biostar_check", headerFilter:true, formatter:function(cell, formatterParams, onRendered){
                    let id = cell.getValue()
                    if(cell.getValue() === "Polnud majas"){
                        cell.getElement().style.backgroundColor = "#EE7C71";
                    }else if(cell.getValue()==="Oli majas"){
                        cell.getElement().style.backgroundColor = "#b7f1b7";
                    }else if(cell.getValue()==="Pole tehtud"){
                        return cell.getValue();
                    }else{
                        cell.getElement().style.backgroundColor = "#b7f1b7";
                    }
                    return cell.getValue();
            }},
            {title:"Kinnitatud", headerSort:false, field:"confirmed",width:175, headerFilter:"tickCross", formatter:function(cell, formatterParams, onRendered){
                if(cell.getValue() === 0){
                    return `<form autocomplete="off" action="{{url('hourReport/confirm')}}" method="post">
                    @csrf
                    <input type="hidden" name="reportId" value="${cell.getRow().getData().id}">
                    <input class="submitButton" type="submit" value="Kinnita"/>
                    </form>`;
                }
                return "Kinnitatud";
            }},
            {title:"Kinnitaja", field:"confirmer.name", headerFilter:true},
            {title:"Kinnitamise kuupäev ", field:"confirmed_at", headerFilter:true},
            {title:"", headerSort:false, width:175, formatter:function(cell, formatterParams, onRendered){
                    let id = cell.getRow().getData().id
                    return `<form autocomplete="off" action="{{url('hourReport/delete/${id}')}}" method="post">
                    @csrf
                    <input class="deleteButton" type="submit" value="Kustuta"/>
                    </form>`;
            }}
        ]
        
    })

    table.on("rowClick", function(e, row){
        let rowData = row.getData()
        window.location.href = "{{URL::to('hourReport/update')}}"+ "/"+ rowData.id
    });
</script>
@endsection