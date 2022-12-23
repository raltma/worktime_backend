<html>
    <head>
        <title>Worktime - @yield('title')</title>       
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="{{URL::asset('/img/favicon.ico')}}">
        <link rel="stylesheet" href="{{asset('/css/styles.css')}}">
        @yield('head')
    </head>
    <body>
        @include('header')
        @yield('content')
    </body>
</html>