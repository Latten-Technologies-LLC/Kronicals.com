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
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="alternate" hreflang="en" href="https://anonuss.com/" />

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href=""/>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">

    <!-- Specific page stylesheet -->
    <link href="{{ asset('css/'.$stylesheet.'.css') }}" rel="stylesheet" type="text/css"/>

    <!-- Script -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.2/js/all.js"></script>
</head>
<body>
<div class="main-website">
    <div class="website">
        @include('templates.logged.header')
        <div class="innerWebsite">
            @yield('content')
        </div>
        @include('templates.logged.footer')
    </div>
    @include('templates.sidebar')
</div>
<!-- Javascript -->
<script src="{{ asset('js/jquery.js') }}" type="application/javascript" language="JavaScript"></script>

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
<script src="{{ asset('js/materialize.js') }}" type="application/javascript" language="JavaScript"></script>
<script src="{{ asset('js/bootstrap.js') }}" type="application/javascript" language="JavaScript"></script>
<script src="{{ asset('js/logged-main.js') }}" type="application/javascript" language="JavaScript"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-115069808-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-115069808-1');
</script>


@yield('scripts')

</body>
</html>