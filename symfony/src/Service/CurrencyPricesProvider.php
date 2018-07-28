<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: vax
 * Date: 28.07.18
 * Time: 19:10
 */

namespace App\Service;

use App\Repository\CurrencyPricesRepositoryInterface;
use App\Repository\RemoteHttpCurrencyPricesRepository;

class CurrencyPricesProvider
{
    /** @var CurrencyPricesRepositoryInterface */
    private $currencyPricesRepository;

    /**
     * CurrencyPricesProvider constructor.
     *
     * @param RemoteHttpCurrencyPricesRepository $currencyPricesRepository
     */
    public function __construct(RemoteHttpCurrencyPricesRepository $currencyPricesRepository)
    {
        $this->currencyPricesRepository = $currencyPricesRepository;
    }

    /**
     * Returns the required formatting of the input dates as string suitable for DateTime->format($format)
     *
     * @return string
     */
    public function getDateTimeFormat(): string
    {
        return 'Y-m-d';
    }

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
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchPrices(
        string $baseCurrency,
        array $targetCurrencies,
        string $startDate,
        string $endDate
    ): array
    {
        return $this->currencyPricesRepository->fetchPrices($baseCurrency, $targetCurrencies, $startDate, $endDate);
    }
}