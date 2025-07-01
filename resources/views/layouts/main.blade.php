<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Laravel</title>
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-5.3.7/css/bootstrap.min.css')}}">
</head>
<body>
    @yield('main_container')
    <script sec="{{ asset('vendor/bootstrap-5.3.7/js/bootstrap.bundle.min.js')}}"></script>
</body>
</html>