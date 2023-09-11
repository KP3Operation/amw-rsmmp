<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=375, initial-scale=1, maximum-scale=1">
    <title>{{ env('APP_NAME') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    @vite('resources/css/style.css')
    <link rel="apple-touch-icon" sizes="57x57"
        href="{{ Vite::asset('resources/static/images/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60"
        href="{{ Vite::asset('resources/static/images/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72"
        href="{{ Vite::asset('resources/static/images/favicon/apple-icon-72x72.png') }}/">
    <link rel="apple-touch-icon" sizes="76x76"
        href="{{ Vite::asset('resources/static/images/favicon/apple-icon-76x76.png') }}/">
    <link rel="apple-touch-icon" sizes="114x114"
        href="{{ Vite::asset('resources/static/images/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120"
        href="{{ Vite::asset('resources/static/images/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144"
        href="{{ Vite::asset('resources/static/images/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152"
        href="{{ Vite::asset('resources/static/images/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="152x152"
        href="{{ Vite::asset('resources/static/images/favicon/android-icon-144x144.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"
        href="{{ Vite::asset('resources/static/images/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ Vite::asset('resources/static/images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96"
        href="{{ Vite::asset('resources/static/images/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ Vite::asset('resources/static/images/favicon/favicon-16x16.png') }}">
    {{-- <link rel="manifest" href="{{ Vite::asset('resources/static/images/favicon/manifest.json') }}"> --}}
    <meta name="msapplication-TileColor" content="#0D6EFD;">
    <meta name="msapplication-TileImage"
        content="{{ Vite::asset('resources/static/images/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#0D6EFD;">
</head>

<body class="bg-gray-200">
    <main class="bg-white pb-7">
        <div id="app" class="h-100"></div>
    </main>
    @vite('resources/js/patient/patient.js')
</body>

</html>
