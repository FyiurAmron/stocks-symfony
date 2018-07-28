<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: vax
 * Date: 28.07.18
 * Time: 16:43
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StocksController
{
    /**
     * @Route("/hello")
     */
    public function helloAction(): Response
    {
        return new Response('<html><body>Hello World</body></html>' );
    }
}