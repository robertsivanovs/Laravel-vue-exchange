<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Rate;

class IndexController extends Controller
{

    public $currencies = [
        'USD', 
        'GBP', 
        'AUD'
    ];
    
    /**
     * index
     * 
     * Return index view
     *
     * @return void
     */
    public function index() {

        $new_rates = $this->getCurrencyRates();
        return view('index')->with('new_rates', $new_rates);
    }

    public function getCurrencyRates() {

        $currency_rates = Rate::whereIn('quote_currency', $this->currencies)
            ->orderBy('created_at', 'asc')
            ->get();

        $new_rates = [];

        foreach ($currency_rates as $currency) {

            $quoteCurrency = $currency->quote_currency;
            $exchangeRate = $currency->exchange_rate;
            $date = $currency->created_at->format('Y-m-d');
        
            // Check if the quote_currency entry already exists in $new_rates
            if (!isset($new_rates[$quoteCurrency])) {
                // If not, initialize it with the first exchange rate found
                $new_rates[$quoteCurrency] = [
                    'exchange_rates' => [$date => $exchangeRate],
                    'last_updated' => $date,
                    'lowest_rate' => $exchangeRate,
                    'highest_rate' => $exchangeRate,
                    'average_rate' => $exchangeRate, 
                    'rate_count' => 1
                ];
            } else {
                // If it already exists, update the exchange rate and last_updated date
                $new_rates[$quoteCurrency]['exchange_rates'][$date] = $exchangeRate;
                $new_rates[$quoteCurrency]['last_updated'] = $date;
        
                // Update the lowest rate if the current rate is lower
                if ($exchangeRate < $new_rates[$quoteCurrency]['lowest_rate']) {
                    $new_rates[$quoteCurrency]['lowest_rate'] = $exchangeRate;
                }

                // Update the highest rate if the current rate is higher
                if ($exchangeRate > $new_rates[$quoteCurrency]['highest_rate']) {
                    $new_rates[$quoteCurrency]['highest_rate'] = $exchangeRate;
                }

                // Update the average rate and rate count
                $new_rates[$quoteCurrency]['average_rate'] =
                    ($new_rates[$quoteCurrency]['average_rate'] * $new_rates[$quoteCurrency]['rate_count'] + $exchangeRate) /
                    ($new_rates[$quoteCurrency]['rate_count'] + 1);

                $new_rates[$quoteCurrency]['rate_count']++;
            }
        }

        return $new_rates;

    }
}
