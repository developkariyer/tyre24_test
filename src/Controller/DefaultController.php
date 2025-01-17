<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    #[Route('/', name: 'main_page')]
    public function mainPage(): Response
    {
        return new Response('Hello World!');
    }

}