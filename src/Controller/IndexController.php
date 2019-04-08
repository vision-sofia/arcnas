<?php

namespace App\Controller;

use App\Entity\ConfigurationList\Element;
use App\Entity\Photo;
use App\Services\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    protected $queryBuilder;
    protected $utils;

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    /**
     * @Route("/photos", name="app.photos")
     */
    public function index(): Response
    {
        $photos = $this->getDoctrine()->getRepository(Photo::class)->findAll();

        $elements = $this->getDoctrine()->getRepository(Element::class)->findAll();

        $w = [];

        foreach ($elements as $element) {
            $id = $element->getId();
            $w[$id] = [
                'id'    => $element->getId(),
                'color' => $element->getPrimaryColor(),
                'name'  => $element->getName(),
            ];
        }

        $finalResult = [];

        foreach ($photos as $item) {
            $finalResult[] = [
                'item'  => $item,
                'marks' => $this->utils->transform($item->getMetadata(), $w),
            ];
        }

        return $this->render('index/index.html.twig', [
            'photos' => $finalResult,
        ]);
    }
}
