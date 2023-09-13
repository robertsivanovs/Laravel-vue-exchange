<style>
table, th, td {
  border:1px solid black;
}
</style>

<div id="app">
  <exchange-component :new-rates = '@json($new_rates)' />
</div>

<script src="{{ mix('/js/app.js') }}"></script>
