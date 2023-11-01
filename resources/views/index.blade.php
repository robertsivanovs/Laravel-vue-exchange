<link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">
@vite(['resources/css/app.css', 'resources/js/app.js'])

<div id="app">
  <exchange-component :new-rates = '@json($newRates)' />
</div>

