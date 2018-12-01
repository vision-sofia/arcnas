<?php

namespace App\Controller;

use App\Entity\ConfigurationList\Element;
use App\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="app.search")
     */
    public function index(): Response
    {
        $element = $this->getDoctrine()->getRepository(Element::class)->find(34);

        $result= $this->getDoctrine()->getRepository(Photo::class)->findByCount($element);

        dump($result);

        return $this->render('search/index.html.twig', [

        ]);
    }
}
