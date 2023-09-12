<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Rate;
use Illuminate\Support\Collection;

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
            $new_rates[$currency->quote_currency]['exchange_rates'][$currency->created_at->format('Y-m-d')] = $currency->exchange_rate;
            // Since we are ordering by the latest, push only the latest update date
            $new_rates[$currency->quote_currency]['last_updated'] = $currency->created_at->format('Y-m-d');
        }

        return $new_rates;

    }
}
