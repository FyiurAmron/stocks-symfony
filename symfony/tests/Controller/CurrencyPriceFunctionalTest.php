<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: vax
 * Date: 28.07.18
 * Time: 20:47
 */

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CurrencyPriceFunctionalTest extends WebTestCase
{
    public function testItProvidesValidResultForDefaultQuery(): void
    {
        $client = static::createClient();

        $client->request('GET', 'prices');

        $response = $client->getResponse();
        $responseContent = $response->getContent();
        $responseArray = \json_decode($responseContent, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($responseArray['success']);
        $this->assertNull($responseArray['problem']);

        $this->assertArrayHasKey('result', $responseArray);

        $result = $responseArray['result'];

        $this->assertArrayHasKey('PLN_USD', $result);
        $this->assertArrayHasKey('USD_PLN', $result);

        $plnUsd = \array_values($result['PLN_USD']);

        $this->assertTrue((int)$plnUsd > 0);
    }

    public function testItProvidesValidResultForStaticQueryWithFixedDate()
    {
        $client = static::createClient();

        $client->request('GET', 'prices?startDate=2018-07-20&endDate=2018-07-25'
            . '&baseCurrency=PLN&targetCurrencies=PLN,EUR,USD');

        $response = $client->getResponse();
        $responseContent = $response->getContent();
        $result = \json_decode($responseContent, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($result['success']);
        $this->assertNull($result['problem']);

        $this->assertJsonStringEqualsJsonFile(__DIR__ . '/result_1.json', $responseContent);
    }

    public function testItIgnoresInvalidParamNamesInQuery()
    {
        $client = static::createClient();

        $client->request('GET', 'prices?startaDate=1019-07-20&endaDate=018-07-25'
            . '&baseaCurrency=KAF&targetaCurrencies=PLAN,EURO,USID');

        $response = $client->getResponse();
        $responseContent = $response->getContent();
        $responseArray = \json_decode($responseContent, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($responseArray['success']);
        $this->assertNull($responseArray['problem']);

        $this->assertArrayHasKey('result', $responseArray);

        $result = $responseArray['result'];

        $this->assertArrayHasKey('PLN_USD', $result);
        $this->assertArrayHasKey('USD_PLN', $result);

        $plnUsd = \array_values($result['PLN_USD']);

        $this->assertTrue((int)$plnUsd > 0);
    }

    public function testItProvidesProblemResultForInvalidQuery()
    {
        $client = static::createClient();

        $client->request('GET', 'prices?startDate=1018-07-20&endDate=1018-07-25'
            . '&baseCurrency=PLN&targetCurrencies=PLN,EUR,USD');

        $response = $client->getResponse();
        $responseContent = $response->getContent();
        $result = \json_decode($responseContent, true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertNull($result['result']);
        $this->assertNotNull($result['problem']);
    }
}
