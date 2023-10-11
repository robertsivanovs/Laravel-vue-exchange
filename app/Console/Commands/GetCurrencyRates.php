<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use App\Models\Rate;

/**
 * GetCurrencyRates
 * 
 * Fetches currency data from anyapi.io
 * Saves data in DB
 * Performs a date check to update only once every 24 hrs
 * 
 * Run "php artisan get-currency-rates" from project root to execute
 * 
 */
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
     * Currencies used in the app and saved in the DB.
     * Populate this list if you'd like to add any other currency.
     *
     * @var array
     */
    protected array $currenciesToUse = [
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
    public function handle(): int {

        if ($this->isRatesUpdated()) {
            $this->info("Rates are up to date. Nothing to update!");
            return 0;
        }

        // Execute request to anyapi.io to fetch currency data
        $response = Http::get(Config('currency.anyapi.api_endpoint'), [
            'apiKey' => Config('currency.anyapi.api_key'),
            'base' => Config('currency.anyapi.base_currency')
        ]);

        // If data was fetched successfully save the rate data to DB
        if ($response->successful()) {
            $data = json_decode($response->body());
            $this->saveData($data);
            $this->info('API Data fetched successfully');
            $this->info($response->body());
            return 1;
        } else {
            $this->error('Bad or invalid API response.');
            return 0;
        }
    }
            
    /**
     * saveData
     * 
     * Save rate data to DB
     *
     * @param  mixed $data
     * @return void
     */
    protected function saveData(?object $data): void {
        
        if (!$data) {
            return;
        }
    
        foreach ($this->currenciesToUse as $currency) {
            if (property_exists($data->rates, $currency)) {
                try {
                    Rate::create([
                        'base_currency' => $data->base,
                        'quote_currency' => $currency,
                        'exchange_rate' => $data->rates->$currency,
                    ]);
                } catch (\Exception $e) {
                    // Handle database error, log it, or throw a custom exception.
                    $this->error('Failed to save currency rates.');
                    $this->error($e->getMessage());
                }
            }
        }
    }
    
    /**
     * isRatesUpdated
     * 
     * Check if 24 hours have passed since the last update
     *
     * @return bool
     */
    protected function isRatesUpdated(): bool {

        $updated = false;

        // Check if an entry exists in Rates DB table
        if (!is_object(Rate::latest('id')->first())) {
            // If no entries found skip time checks & populate data
            return $updated;
        }
        
        // Check if the needed property exists for the DB entry
        if (!isset(Rate::latest('id')->first()->created_at)) {
            return $updated;
        }
        
        // Get date & time 24 hours ago
        $yesterday = Carbon::now()->subHours(24);
        $latest_update_date = Rate::latest('id')->first()->created_at;

        // Compare if 24 hours have passed since the latest update
        return !$latest_update_date->lt($yesterday);

    }
}
