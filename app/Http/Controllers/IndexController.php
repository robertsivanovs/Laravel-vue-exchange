<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        $currency_rates = json_decode($this->getCurrencyRates());
        return view('index')->with('currency_rates', $currency_rates);
    }

    public function getCurrencyRates() {
        
        $response = Http::get('https://anyapi.io/api/v1/exchange/rates', [
            'apiKey' => 'eji75455nvmra3k4jo6rolclbngnbotmcbv709h8ogqb5eqi8eto',
            'base' => 'EUR'
        ]);

        if ($response->failed()) {
            return "No data available at this time!";
        }

        return $response->body();

    }
}
