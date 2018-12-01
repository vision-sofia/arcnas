<?php

namespace App\Controller;

use App\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("", name="app.index")
     */
    public function index(): Response
    {
        $photos = $this->getDoctrine()->getRepository(Photo::class)->findAll();

        return $this->render('index/index.html.twig', [
            'photos' => $photos,
        ]);
    }
}
