@extends('layouts.main')

@section('title', $title)

@section('head')
    <link href="{{asset('/tabulator-master/dist/css/tabulator_bootstrap5.css')}}" rel="stylesheet">
    <link href="{{asset('/slimselect/slimselect.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('/tabulator-master/dist/js/tabulator.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/slimselect/slimselect.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('/css/updateForms.css')}}">
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
<form id="userForm" autocomplete="off" action="{{url('user/update')}}" method="post">
    @csrf
    <input id="selectedUser" type="hidden" name="id">
    <section
        <div id="selectedUserText" class="centered"></div>
    </section>
    <div>
        <label>Kasutajanimi:</label>
        <input id="username" required name="username" type="text">
    </div>
    <div>
        <label>Parool:</label>
            <input placeholder="Jäta tühjaks kui ei soovi parooli vahetada" name="password" type="password">
        </div>
    <div>
    <label>Avavaade:</label>
        <select id="default_tab" name="default_tab">
            <option value="/hourReport">Tundide aruanded</option>
            <option value="/piecesReport">Tükkide aruanded</option>
            <option value="/absentReport">Puudumiste aruanded</option>
        </select>
    </div>
    <div>
        <label>Admin:</label>
        <input id="admin" name="admin" type="checkbox">
    </div>
    <section hidden id="adminDepartmentContainer" class="selectWrapper">
        <select id="adminDepartments" name="adminDepartments[]" multiple>
        @foreach ($departments as $department)
            <option value="{{$department->id}}">{{$department->name}}</option>
        @endforeach
        </select>
    </section>
    <section>
        <input class="submitButton"  id="saveButton" type="submit" value="Salvesta">
        <button type="button" onclick="clearForm()">Tühista valik</button>
    </section>
</form>
<div id="table"></div>

<script type="text/javascript">
    document.getElementById('userForm').style.display = "none";
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
            {title:"Kasutajanimi", field:"username", width:150, headerFilter:true},
            {title:"Nimi", field:"name", width:150, headerFilter:true},
            {title:"Email", field:"email", headerFilter:true},
            {title:"Taavi kood", field:"taavi_code", headerFilter:true},
            {title:"Osakond", field:"department.name", headerFilter:true},
            {title:"Avavaade", field:"default_tab", headerFilter:true, formatter:function(cell, formatterParams, onRendered){
                switch (cell.getValue()) {
                    case "/hourReport":
                        return "Tundide aruanded";
                    case '/piecesReport':
                        return 'Tükkide aruanded';
                    case '/absentReport':
                        return 'Puudumiste aruanded';
                    default:
                        return ""
                    }
                }
            },
            {title:"Admin", field:"admin", formatter:"tickCross",width:125, headerFilter:true},
            {title:"Admini osakonnad", field:"adminDepartments", headerFilter:true}
        ]
    })

    table.on("rowClick", function(e, row){
        let rowData = row.getData()
        document.getElementById('selectedUser').value = rowData.id;
        document.getElementById('username').value = rowData.username;
        document.getElementById('userForm').style.display = "block";
        document.getElementById('selectedUserText').innerHTML = `Valitud on kasutaja <i><b>${rowData.name}<b></i>`
        document.getElementById('admin').checked = rowData.admin === 1
        document.getElementById('default_tab').value = rowData.default_tab
        adminDepartmentContainer.hidden = true
        if(rowData.admin === 1) adminDepartmentContainer.hidden = false 
        let adminDepartmentsSelect = document.getElementById('adminDepartments');
        adminSelect.setSelected(rowData.admin_departments.map((ad)=>String(ad.id)))
    });

    function clearForm(){
        document.getElementById('userForm').style.display = "none";
    }
</script>
@endsection