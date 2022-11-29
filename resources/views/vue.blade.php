<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="robots" content="noindex, nofollow"/>

    <!-- Title -->
    <title>Aktaa Dental</title>
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicon-16x16.png')}}">
    <!-- Style sheets -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"/>
    <link href="{{ mix('app.css') }}" rel="stylesheet" type="text/css"/>
</head>
<body>
<!-- Vue App-->
<div id="vapor-ui" class="antialiased min-h-screen flex overflow-hidden bg-gray-100" v-cloak>
    @include('layout.sidebar')
    <flash-message></flash-message>
    <div class="w-full">
        @include('layout.nav')
        <router-view></router-view>
    </div>
</div>
<script>
    var app;
    var user = @json(Auth::guard('api')->user());
    var lastFileNumber = +@json($lastFileNumber);
</script>
<script src="{{ mix('app.js') }}"></script>
</body>
</html>
