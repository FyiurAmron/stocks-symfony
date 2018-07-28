<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: vax
 * Date: 28.07.18
 * Time: 19:52
 */

namespace App\Repository;

interface CurrencyPricesRepositoryInterface
{
    /**
     * Fetches an array with currency prices for all possible baseCurrency vs targetCurrency pairs
     * for a given date range.
     *
     * @param string $baseCurrency
     * @param string[] $targetCurrencies
     * @param string $startDate
     * @param string $endDate
     *
     * @return array
     */
    public function fetchPrices(
        string $baseCurrency,
        array $targetCurrencies,
        string $startDate,
        string $endDate
    ): array;
}