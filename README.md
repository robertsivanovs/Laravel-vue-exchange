# Laravel & vue.js - Exchange rate fetch from API (anyapi.io)

 ### To set up the project:

PHP 8.2
Composer 2.6.2

1. Git clone the repository
2. Run composer install from project root
3. In project root set up .env file and DB connection - You can create any blank DB
4. Generate application encription key [php artisan key:generate]
5. Run migrations [php artisan migrate]
6. The Laravel Console command to populate DB data with exchange rates - [php artisan get-currency-rates ]
7. Run the application [php artisan serve] 
8. Profit!

-- Might need to run npm run dev & npm run watch 

 ### Notes:

The console command class for fetching the data from anyapi.io is located at:
[Console CMD](https://github.com/robertsivanovs/trodo-test-task/blob/main/app/Console/Commands/GetCurrencyRates.php)

To avoid the 24hr check and add additional data for testing purposes simply add a "return false;" statement here:
[Console CMD](https://github.com/robertsivanovs/trodo-test-task/blob/main/app/Console/Commands/GetCurrencyRates.php#L130)

To change how many items should be displayed in the table on the frontend, change tha value located in:
[Vue.js component](https://github.com/robertsivanovs/trodo-test-task/blob/main/resources/js/components/ExchangeComponent.vue#L52)


 ### UI/UX:

Select one of the currencies from the dropdown to display the table & all other data:
![Index view](https://i.imgur.com/RGrWEGB.png "Index view")
