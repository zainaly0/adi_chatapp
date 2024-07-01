<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        .user_admin{
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 500;
            font-size: 3rem;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>

</head>

<body class="antialiased font-sans bg-dark">
    <div class="user_admin">
        <a href="/login"><button type="button" class="btn btn-info">Log in</button></a>
        <a href="/register"><button type="button" class="btn btn-light">Register User</button></a>
    </div>
</body>

</html>
