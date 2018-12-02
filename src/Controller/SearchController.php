<?php

namespace App\Controller;

use App\Entity\ConfigurationList\Element;
use App\Entity\Photo;
use App\Form\SearchType;
use App\Services\Utils;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    protected $queryBuilder;
    protected $utils;

    public function __construct(QueryBuilder $queryBuilder, Utils $utils)
    {
        $this->queryBuilder = $queryBuilder;
        $this->utils = $utils;
    }


    /**
     * @Route("/search", name="app.search")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);


        $result = [];
        if ($form->isSubmitted() && $form->isValid()) {

            $element = $form->get('element')->getData();
            $condition = $form->get('condition')->getData();
            $count = (int)$form->get('count')->getData();

            if ($element) {
                $result = $this->getDoctrine()
                               ->getRepository(Photo::class)
                               ->findByCount($element, $condition, $count, $this->queryBuilder)
                ;
            }
        }

        $finalResult = [];

        if ($result) {

            $elements = $this->getDoctrine()->getRepository(Element::class)->findAll();

            $w = [];

            foreach ($elements as $elementObj) {
                $id = $elementObj->getId();
                $w[$id] = [
                    'id'    => $elementObj->getId(),
                    'color' => $elementObj->getPrimaryColor(),
                    'name'  => $elementObj->getName(),
                ];
            }

            foreach ($result as $item) {
                $finalResult[] = [
                    'item'  => $item,
                    'marks' => $this->utils->transform($item->getMetadata(), $w),
                ];
            }
        }
        
        return $this->render('search/index.html.twig', [
            'form'   => $form->createView(),
            'result' => $finalResult,
            'submitted' => $form->isSubmitted() && $form->isValid(),
            'searchedElement' => $form->isSubmitted() && $form->isValid() ? $element : null
        ]);
    }
}
