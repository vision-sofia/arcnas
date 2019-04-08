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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    protected $queryBuilder;
    protected $utils;
    protected $session;

    public function __construct(QueryBuilder $queryBuilder, Utils $utils, SessionInterface $session)
    {
        $this->queryBuilder = $queryBuilder;
        $this->utils = $utils;
        $this->session = $session;
    }

    /**
     * @Route("", name="app.index")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $request->request->has('clear');

            $this->session->remove('element_id');

            return $this->redirectToRoute('app.index');
        }

        $selected = $this->session->get('element_id');

        if ($selected) {
            $element = $this->getDoctrine()->getRepository(Element::class)->findOneBy([
                'id' => $selected
            ]);

            $form->get('element')->setData($element);


        }

        return $this->render('search/index.html.twig', [
            'form' => $form->createView(),
            'selected' => $selected,
        ]);
    }
}
