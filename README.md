A simple webapp relaying the responses of a currency price API.

It provides an automatic workaround for remote API pair limit
(by means of chunking the request), and, through modular design, allows
implementing response caching (in memory or database) later on.

##### INSTALLATION

Clone this repository and use the provided Docker container to set up a local
 nginx server listening on
 <http://symfony.localhost:8080> .
 
 `docker-compose up`

##### USAGE

Base endpoint path is

<http://symfony.localhost:8080/prices>

There are four options accepted (passed as query string parts), all are optional:

 - startDate (`YYYY-mm-dd`) - the date of first requested currency price (inclusive); yesterday if omitted
 - endDate (`YYYY-mm-dd`) - the date of last requested currency price (inclusive); today if omitted
 - baseCurrency (`XXX`) - an ISO 4217 currency code of the requested base currency; `PLN` if omitted
 - targetCurrency (`XXX,YYY,...`) - a comma-separated list of ISO 4217 currency codes of the requested
   target currencies; `USD` if omitted
 
All pairs that can be created of given currencies are provided, e.g.

<http://symfony.localhost:8080/prices?startDate=2018-07-20&endDate=2018-07-25&baseCurrency=PLN&targetCurrencies=PLN,EUR,USD>

... should return `PLN_PLN`, `EUR_PLN`, `PLN_EUR`, `USD_PLN` and `PLN_USD` pairs.

##### TESTING

This app uses the official `symfony/test-pack`, providing `symfony/phpunit-bridge`, which in turn provide a complete PHPUnit
test environment, which can be used either in a Docker context, or as a standalone application. E.g.,
for a standalone execution, simply run

`symfony/bin/phpunit`

... to execute the provided example functional test.