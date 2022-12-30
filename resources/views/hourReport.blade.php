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
        columns: [
            {title:"Esitaja nimi", field:"user.name", width:150, headerFilter:true},
            {title:"Kuupäev", field:"date_selected", headerFilter:true},
            {title:"Vahetus", field:"shift", headerFilter:true},
            {title:"Tunnid", field:"hours", headerFilter:true},
            {title:"Ületunnid", field:"overtime_hours", headerFilter:true, formatter:function(cell, formatterParams, onRendered){
                if(cell.getRow().getData().overtime === 1){
                    return cell.getValue();
                }
                return "-" //return the contents of the cell;
            }},
            {title:"Kinnitatud", field:"confirmed", formatter:"tickCross",width:175, headerFilter:true}
        ]
    })

    table.on("rowClick", function(e, row){
        console.log(adminSelect.getSelected());
        let rowData = row.getData()
        document.getElementById('selectedUser').value = rowData.id;
        document.getElementById('username').value = rowData.username;
        document.getElementById('saveButton').hidden = false;
        document.getElementById('selectedUserText').innerHTML = `Valitud on kasutaja <i><b>${rowData.name}<b></i>`
        document.getElementById('admin').checked = rowData.admin === 1
        adminDepartmentContainer.hidden = true
        if(rowData.admin === 1) adminDepartmentContainer.hidden = false 
        let adminDepartmentsSelect = document.getElementById('adminDepartments');
        adminSelect.setSelected(rowData.admin_departments.map((ad)=>String(ad.id)))
    });
</script>
@endsection