<?php

declare (strict_types=1);
namespace App\Services;

use App\Models\Rate;
use App\Contracts\RateDataProviderInterface;

/**
 * RateService
 */
class RateService implements RateDataProviderInterface
{    
    /**
     * getRatesForCurrencies
     * 
     * Process currency data
     *
     * @param array $currencies
     * @return array
     */
    public function getRatesForCurrencies(array $currencies): array
    {
        // Fetch currency rate data from the database
        $currencyRates = Rate::whereIn('quote_currency', $currencies)
            ->orderBy('created_at', 'asc')
            ->get();

        $newRates = [];

        // Reorder and sort the currency data
        foreach ($currencyRates as $currency) {
            $quoteCurrency = $currency->quote_currency;
            $exchangeRate = (float)$currency->exchange_rate;
            $date = $currency->created_at->format('d.m.Y');
        
            // Check if the quote_currency entry already exists in $newRates
            if (!isset($newRates[$quoteCurrency])) {
                // If not, initialize it with the first exchange rate found
                $newRates[$quoteCurrency] = [
                    'exchange_rates' => [$date => number_format($exchangeRate, 4)],
                    'last_updated' => $date,
                    'lowest_rate' => $exchangeRate,
                    'highest_rate' => $exchangeRate,
                    'average_rate' => $exchangeRate, 
                    'rate_count' => 1,
                    'quote_currency' => $quoteCurrency
                ];
            } else {
                // If it already exists, update the exchange rate and last_updated date
                $newRates[$quoteCurrency]['exchange_rates'][$date] = $exchangeRate;
                $newRates[$quoteCurrency]['last_updated'] = $date;
 
                // Find the lowest rate
                $newRates[$quoteCurrency]['lowest_rate'] = min($exchangeRate, $newRates[$quoteCurrency]['lowest_rate']);
                // Find the highest rate
                $newRates[$quoteCurrency]['highest_rate'] = max($exchangeRate, $newRates[$quoteCurrency]['highest_rate']);

                // Update the average rate and rate count
                $newRates[$quoteCurrency]['average_rate'] =
                    ($newRates[$quoteCurrency]['average_rate'] * $newRates[$quoteCurrency]['rate_count'] + $exchangeRate) /
                    ($newRates[$quoteCurrency]['rate_count'] + 1);

                $newRates[$quoteCurrency]['rate_count']++;
            }
        }

        return $newRates;

    }
}
