<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-slate-50 via-white to-emerald-50/20 font-body">

    <!-- SIDEBAR -->
    @include('layouts.sidebar')

    <!-- HEADER -->
    @include('layouts.header')

    <!-- CONTENT -->
    <main class="ml-64 pt-20 p-6">
        {{ $slot }}
    </main>

</body>
</html>