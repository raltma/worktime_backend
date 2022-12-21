<html>
    <head>
        <title>App Name - @yield('title')</title>       
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="{{URL::asset('/img/favicon.ico')}}">
        <link rel="stylesheet" href="{{URL::asset('/css/styles.css')}}">
    </head>
    <body>
        @include('header')
        
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>