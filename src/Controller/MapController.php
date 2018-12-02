<?php

namespace App\Controller;

use App\Entity\ConfigurationList\Element;
use App\Entity\Photo;
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    protected $queryBuilder;
    protected $utils;

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    /**
     * @Route("/map", name="app.map")
     */
    public function index(): Response
    {

        return $this->render('map/index.html.twig', []);
    }
}
