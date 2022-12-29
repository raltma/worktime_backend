@extends('layouts.main')

@section('title', $title)

@section('head')
    <link href="{{asset('/tabulator-master/dist/css/tabulator_bootstrap5.css')}}" rel="stylesheet">
    <link href="{{asset('/slimselect/slimselect.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('/tabulator-master/dist/js/tabulator.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/slimselect/slimselect.min.js')}}"></script>
@endsection

@section('content')
<h1 class="title">Kasutajad</h1>
    @if($errors->has('error'))
        <div class="error">{{$errors->first()}}</div>
    @elseif($errors->has('message'))
        <div class="message">{{$errors->first()}}</div>
    @else
        <div class="errorFiller"></div>
    @endif
<form autocomplete="off" action="{{url('update_user')}}" method="post">
    @csrf
    <div id="selectedUserText"></div>
    <input id="selectedUser" type="hidden" name="id" value="1">
    <label>
        Kasutajanimi
        <input id="username" required name="username" type="text">
    </label>
    <label>
        Parool
        <input placeholder="Jäta tühjaks kui ei soovi parooli vahetada" name="password" type="password">
    </label>
    <label>
        Admin
        <input id="admin" name="admin" type="checkbox">
    </label>

    <div hidden id="adminDepartmentContainer" class="selectWrapper">
        <select id="adminDepartments" name="adminDepartments[]" multiple>
        @foreach ($departments as $department)
            <option value="{{$department->id}}">{{$department->name}}</option>
        @endforeach
        </select>
    </div>
    <input  id="saveButton" type="submit" value="Salvesta">
</form>
<div id="table"></div>

<script type="text/javascript">
    document.getElementById('saveButton').hidden = true;
    let adminSelect = new SlimSelect({
        select: '#adminDepartments',
        settings: {
            closeOnSelect: false,
        }}
        )
    let adminDepartmentContainer = document.getElementById('adminDepartmentContainer')

    let tableData = {{ Js::from($users) }};
    document.getElementById('admin').addEventListener('change',(event)=>{
        adminDepartmentContainer.hidden = true
        if(event.target.checked) adminDepartmentContainer.hidden = false 
    })
    tableData = tableData.map((user)=>{
        user.adminDepartments = user.admin_departments.map((d)=>d.name).join(", ")
        return user
    })
    let table = new Tabulator("#table", {
        data: tableData,
        layout: "fitColumns",
        columns: [
            {title:"Nimi", field:"name", width:150, headerFilter:true},
            {title:"Email", field:"email", headerFilter:true},
            {title:"Taavi kood", field:"taavi_code", headerFilter:true},
            {title:"Osakond", field:"department.name", headerFilter:true},
            {title:"Admin", field:"admin", formatter:"tickCross",width:125, headerFilter:true},
            {title:"Admini osakonnad", field:"adminDepartments", headerFilter:true}
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