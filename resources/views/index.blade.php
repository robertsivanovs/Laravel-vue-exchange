<link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">

<div id="app">
  <exchange-component :new-rates = '@json($new_rates)' />
</div>

<script src="{{ mix('/js/app.js') }}"></script>
