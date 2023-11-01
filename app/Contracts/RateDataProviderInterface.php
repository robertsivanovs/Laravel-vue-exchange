<?php

namespace App\Contracts;

interface RateDataProviderInterface {
    public function getRatesForCurrencies(array $currencies): array;
}
