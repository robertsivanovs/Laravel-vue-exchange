<?php

declare(strict_types=1);
namespace App\Http\Controllers;

use App\Contracts\RateDataProviderInterface;

/**
 * IndexController
 */
class IndexController extends Controller
{
    /**
     * @var array
     * 
     * Currencies used on the frontend of the app
     * Populate this list if you'd like to add any other currency.
     */
    protected array $currencies = [
        'USD',
        'GBP',
        'AUD',
    ];
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        protected RateDataProviderInterface $rateDataProvider
    ) {}

     /**
     * Return the index view with currency rates.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $newRates = $this->rateDataProvider->getRatesForCurrencies($this->currencies);
        return view('index')->with('newRates', $newRates);
    }
    
}
