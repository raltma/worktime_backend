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
        initialHeaderFilter:[{field:"confirmed", value:false}],
        columns: [
            {title:"Esitaja nimi", field:"user.name", width:150, headerFilter:true},
            {title:"Kuupäev", field:"date_selected", headerFilter:true},
            {title:"Vahetus", field:"shift", headerFilter:true},
            {title:"Töökoht", field:"workplace", headerFilter:true},
            {title:"Tükid/Liigitused", variableHeight:true, formatter:function(cell, formatterParams, onRendered){
                cell.getElement().style.whiteSpace = "pre-wrap";
                return cell.getRow().getData().classifications
                .map((c)=> c.quantity +"tk" + "\t" + c.classification.name)
                .join("\n");
            }},
            {title:"Kinnitatud", field:"confirmed",width:175, headerFilter:"tickCross", formatter:function(cell, formatterParams, onRendered){
                if(cell.getValue() === 0){
                    return `<form autocomplete="off" action="{{url('piecesReport/confirm')}}" method="post">
                    @csrf
                    <input type="hidden" name="reportId" value="${cell.getRow().getData().id}">
                    <input type="submit" value="Kinnita"/>
                    </form>`;
                }
                return "Kinnitatud";
            }}
        ]
    })

    table.on("rowClick", function(e, row){
        let rowData = row.getData()
    });
</script>
@endsection