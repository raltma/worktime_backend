@extends('layouts.main')

@section('title', $title)

@section('head')
    <link href="{{asset('/tabulator-master/dist/css/tabulator_bootstrap5.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('/tabulator-master/dist/js/tabulator.min.js')}}"></script>
@endsection

@section('content')
<h1 class="title">Kasutajad</h1>
<div id="table">
</div>

<script type="text/javascript">
    let tableData = {{ Js::from($users) }};
    let table = new Tabulator("#table", {
        data: tableData,
        layout: "fitColumns",
        columns: [
            {title:"Nimi", field:"name", width:150, headerFilter:true},
            {title:"Email", field:"email", headerFilter:true},
            {title:"Taavi kood", field:"taavi_code", headerFilter:true},
            {title:"Department", field:"department.name", headerFilter:true}
        ]
    })
</script>
@endsection