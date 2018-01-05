<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo config('app.name'); ?></title>

    <!-- Meta -->
    <meta charset="UTF-8">

    <meta name="description" content="<?php echo config('app.description'); ?>">
    <meta name="keywords" content="<?php echo config('app.keywords'); ?>">
    <meta name="author" content="<?php echo config('app.author'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="alternate" hreflang="en" href="https://anonuss.com/" />

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href=""/>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">

    <!-- Specific page stylesheet -->
    <link href="{{ asset('css/'.$stylesheet.'.css') }}" rel="stylesheet" type="text/css"/>

    <!-- Script -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.2/js/all.js"></script>
</head>
<body>
<div class="main-website">
    <div class="website">
        @include('templates.not-logged.header')
        <div class="innerWebsite">
            @yield('content')
        </div>
        @include('templates.not-logged.footer')
    </div>

    @include('templates.sidebar')
</div>
<!-- Javascript -->
<script src="{{ asset('js/jquery.js') }}" type="application/javascript" language="JavaScript"></script>
<script src="{{ asset('js/materialize.js') }}" type="application/javascript" language="JavaScript"></script>
<script src="{{ asset('js/bootstrap.js') }}" type="application/javascript" language="JavaScript"></script>
<script src="{{ asset('js/main.js') }}" type="application/javascript" language="JavaScript"></script>
</body>
</html>