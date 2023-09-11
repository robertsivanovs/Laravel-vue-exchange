<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Rate;

class IndexController extends Controller
{
    
    /**
     * index
     * 
     * Return index view
     *
     * @return void
     */
    public function index() {

        $currency_rates = $this->getCurrencyRates();
        return view('index')->with('currency_rates', $currency_rates);
    }

    public function getCurrencyRates() {

        $currency_rates = Rate::where('quote_currency', 'USD')
            ->orderBy('created_at', 'desc')
            ->get();

    //         $currencies = ['USD', 'GBP', 'AUD'];

    // $currencyRates = Rate::whereIn('quote_currency', $currencies)
    //     ->orderBy('created_at', 'desc')
    //     ->get();


        return $currency_rates;

    }
}
