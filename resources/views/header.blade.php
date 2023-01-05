<header class="header">
    <div class="headerImg">
        <img  src="{{URL::asset('/img/new_header.png')}}" alt="trendsetter 1912">
    </div>
    @auth
        <div class="links">
            @if(in_array("0",array_map(function($d){return $d['bs_id'];},auth()->user()->adminDepartments->toArray())))
                <a 
                class="@if(request()->route()->uri==='user') selected @endif" 
                href="{{url('user')}}"
                >Kasutajad</a>
            @endif
            <a 
            class="@if(request()->route()->uri==='hourReport') selected @endif" 
            href="{{url('hourReport')}}"
            >Tundide aruanded</a>

            <a 
            class="@if(request()->route()->uri==='piecesReport') selected @endif" 
            href="{{url('piecesReport')}}"
            >Tükkide aruanded</a>

            <a 
            class="@if(request()->route()->uri==='absentReport') selected @endif" 
            href="{{url('absentReport')}}"
            >Puudumiste aruanded</a>

            <a href="{{url('logout')}}">Logi välja</a>
        </div>
    @endauth
    <hr>
</header>
<div class="headerFiller"></div>