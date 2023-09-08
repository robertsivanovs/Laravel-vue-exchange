<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use App\Models\Rate;

class GetCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-currency-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the latest currency rates from anyapi.io';

    
    /**
     * Api endpoint for anyapi.io service
     *
     * @var string
     */
    protected $api_endpoint = 'https://anyapi.io/api/v1/exchange/rates';
    
    /**
     * Currencies used in the app and saved in the DB.
     * Populate this list if you'd like to add any other currency.
     *
     * @var array
     */
    protected $currencies_to_use = [
        'USD',
        'GBP',
        'AUD'
    ];
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * Fetches currency exchange rates from anyapi.io
     *
     * @return int
     */
    public function handle()
    {

        if ($this->isRatesUpdated()) {
            return $this->info("Rates are up to date. Nothing to update!"); 
        }

        $response = Http::get($this->api_endpoint, [
            'apiKey' => Config('constants.anyapi.API_KEY'),
            'base' => Config('constants.anyapi.BASE_CURRENCY')
        ]);

        // If data was fetched successfully save the rate data to DB
        if ($response->successful()) {
            $data = json_decode($response->body());
            $this->saveData($data);
            return $this->info($response->body());
        } else {
            // Log errors / issues to a log file
        }

        return false;
    }
    
    /**
     * saveData
     * 
     * Save exchange rates in DB
     *
     * @param  mixed $data
     * @return void
     */
    protected function saveData($data = null) {

        if (!$data) {
            return false;
        }

        // Iterate trough the used currencies array and save data to DB
        foreach ($this->currencies_to_use as $currency) {
            if (property_exists($data->rates, $currency)){
                $rates = new Rate();
                $rates->base_currency = $data->base;
                $rates->quote_currency = $currency;
                $rates->exchange_rate = $data->rates->$currency;
                $rates->save();
            }
        }
        
    }
    
    /**
     * isRatesUpdated
     * 
     * Check if 24 hours have passed since the last update
     *
     * @return void
     */
    protected function isRatesUpdated() {

        $updated = false;

        // Get date & time 24 hours ago
        $yesterday = Carbon::now();
        $yesterday->subHours(24);
        $yesterday->toDateTimeString();

        // Get latest rate update
        $rates = new Rate();
        $latest_update_date = Rate::latest('id')->first()->created_at;

        // Compare if 24 hours have passed since the latest update
        if (!$latest_update_date->lt($yesterday)) {
            $updated = true;
        }

        return $updated;
    }
}
