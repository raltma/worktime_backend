<header class="header">
    <div class="headerImg">
        <img  src="{{URL::asset('/img/new_header.png')}}" alt="trendsetter 1912">
    </div>
    @auth
        <div class="links">
            <a 
            class="@if(request()->route()->uri==='users') selected @endif" 
            href="{{url('users')}}"
            >Test</a>
            <a href="{{url('logout')}}">Logi välja</a>
        </div>
    @endauth
    <hr>
</header>
<div class="headerFiller"></div>