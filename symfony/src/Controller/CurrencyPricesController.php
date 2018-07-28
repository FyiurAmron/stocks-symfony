<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: vax
 * Date: 28.07.18
 * Time: 16:43
 */

namespace App\Controller;

use App\Service\CurrencyPricesProvider;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CurrencyPricesController extends Controller
{
    /**
     * @Route("/prices")
     *
     * @param CurrencyPricesProvider $currencyPricesProvider
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function helloAction(CurrencyPricesProvider $currencyPricesProvider, Request $request): Response
    {
        $dateTimeFormat = $currencyPricesProvider->getDateTimeFormat();

        $baseCurrency = $request->get('baseCurrency') ?? 'PLN';
        $targetCurrenciesStr = $request->get('targetCurrencies') ?? 'USD';
        $startDate = $request->get('startDate')
            ?? (new \DateTime())->sub(new \DateInterval('P1D'))->format($dateTimeFormat);
        $endDate = $request->get('endDate') ?? (new \DateTime())->format($dateTimeFormat);

        $targetCurrenciesArr = \explode(',', $targetCurrenciesStr);

        $responseContent = null;
        $status = null;

        try {
            $responseContent = [
                'result' => $currencyPricesProvider->fetchPrices(
                    $baseCurrency, $targetCurrenciesArr, $startDate, $endDate
                ),
                'success' => true,
                'problem' => null,
            ];
            $status = 200;
        } catch (GuzzleException $ex) {
            $responseContent = [
                'result' => null,
                'success' => false,
                'problem' => $ex->getMessage(),
            ];
            $status = 400;
        }

        return new JsonResponse($responseContent,$status);
    }
}