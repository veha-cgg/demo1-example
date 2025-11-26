<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => ($appearance ?? 'system') == 'dark'])>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat</title>
    @vite(['resources/css/app.css'])
    @livewireStyles
</head>
<body class="font-sans antialiased m-0 p-0 overflow-hidden">
    {{ $slot }}
    @livewireScripts
</body>
</html>

