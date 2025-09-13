<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Transferencias</title>

    @livewireStyles
    <wireui:scripts />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-200 antialiased">

    <x-notifications />

    <div class="min-h-screen flex flex-col items-center justify-center">
        {{ $slot }}
    </div>

    @livewireScripts
    @wireUiScripts
</body>

</html>