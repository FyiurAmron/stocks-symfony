<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: vax
 * Date: 28.07.18
 * Time: 19:49
 */

namespace App\Repository;

use GuzzleHttp\Client;

class RemoteHttpCurrencyPricesRepository implements CurrencyPricesRepositoryInterface
{
    /**
     * free.currencyconverterapi is limited to 2 currency pairs (single target, single base)
     * provide null if there's no actual limit for whatever reason.
     *
     * @var int
     */
    private static $providerPairLimit = 2;

    private const PROVIDER_URL = 'https://free.currencyconverterapi.com/api/v6/';
    private const ENDPOINT_NAME = 'convert';

    private const HTTP_TIMEOUT = 10.0;

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
        $responses = [];

        $currencyPairs = [];

        foreach ($targetCurrencies as $targetCurrency) {
            $currencyPairs[] = $targetCurrency . '_' . $baseCurrency;
            $currencyPairs[] = $baseCurrency . '_' . $targetCurrency;
        }

        $currencyPairChunks = null;

        if (self::$providerPairLimit !== null && \count($currencyPairs) > self::$providerPairLimit) {
            $currencyPairChunks = \array_chunk($currencyPairs, self::$providerPairLimit);
        } else {
            $currencyPairChunks[] = $currencyPairs;
        }

        foreach ($currencyPairChunks as $currencyPairChunk) {
            $endpointQueryData = [
                'compact' => 'ultra',
                'date' => $startDate,
                'endDate' => $endDate,
                'q' => \implode(',', $currencyPairChunk),
            ];

            $endpointQuery = \http_build_query($endpointQueryData);

            $httpClient = new Client([
                'base_uri' => self::PROVIDER_URL,
                'timeout' => self::HTTP_TIMEOUT,
            ]);

            $endpointUri = self::ENDPOINT_NAME . '?' . $endpointQuery;

            $requestResponse = $httpClient->request('GET', $endpointUri);

            $responseBodyJson = (string)$requestResponse->getBody();

            $chunkResponse = \json_decode($responseBodyJson, true);

            $responses[] = $chunkResponse;

            // MAYBE provide a storage mechanism for exchange rates that has been already queried?
        }

        $response = \array_merge(...$responses);

        return $response;
    }
}