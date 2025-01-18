<?php

namespace App\Controller;

use App\Service\ProductPipeline;
use ReflectionException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    private ProductPipeline $pipeline;
    public function __construct(ProductPipeline $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    /**
     * @throws ReflectionException
     */
    #[Route('/', name: 'main_page')]
    public function mainPage(): Response
    {
        $products = $this->pipeline->process();
        return $this->render(
            'index.html.twig',
            [
                'title' => 'Main page',
                'products' => $products,
            ]
        );
    }

}