<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <title>Exchange rates</title>
    </head>
    <body>
        <div id="app">
            <exchange-component :new-rates = '@json($newRates)' />
        </div>
    </body>
</html>
