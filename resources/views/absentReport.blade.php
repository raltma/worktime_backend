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

<script type="module">
    let absentReasons = {{Js::from(json_decode(File::get(resource_path("json/absentReasons.json"))))}}
    absentReasons = absentReasons.map((d)=>d.items).flat(1)
    let tableData = {{ Js::from($reports) }};
    console.log(tableData)
    let table = new Tabulator("#table", {
        data: tableData,
        layout: "fitColumns",
        initialHeaderFilter:[{field:"confirmed", value:false}],
        columns: [
            {title:"Esitaja nimi", field:"user.name", width:150, headerFilter:true},
            {title:"Alguse kuupäev", field:"date_start", headerFilter:true},
            {title:"Lõpu kuupäev", field:"date_end", headerFilter:true},
            {title:"Vahetus", field:"shift", headerFilter:true},
            {title:"Tunnid", field:"hours", headerFilter:true},
            {title:"Kood/Põhjus", field:"reason", headerFilter:true, formatter:function(cell, formatterParams, onRendered){
                if(cell.getRow().getData().overtime === 1){
                    return cell.getValue();
                }
                if(cell.getValue() === "null") return "";
                let name = absentReasons.find((x)=>x.code === cell.getValue()).name
                return cell.getValue() + " / " + name //return the contents of the cell;
            }},
            {title:"Manus", field:"filepath",  headerFilter:true, 
                formatter:"link", formatterParams:{
                target:"_blank",
                labelField:"filename"
            }},
            {title:"Kinnitatud",headerSort:false, field:"confirmed",width:175, headerFilter:"tickCross", formatter:function(cell, formatterParams, onRendered){
                if(cell.getValue() === 0){
                    return `<form autocomplete="off" action="{{url('absentReport/confirm')}}" method="post">
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
                    return `<form autocomplete="off" action="{{url('absentReport/delete/${id}')}}" method="post">
                    @csrf
                    <input class="deleteButton" type="submit" value="Kustuta"/>
                    </form>`;
            }}
        ]
    })

    table.on("rowClick", function(e, row){
        let rowData = row.getData()
        window.location.href = "{{URL::to('absentReport/update')}}"+ "/"+ rowData.id
    });
</script>
@endsection