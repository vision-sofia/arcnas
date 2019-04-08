<?php

namespace App\Controller;

use App\Entity\ConfigurationList\Element;
use App\Form\Type\ElementType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/attributes", name="app.attributes.")
 */
class AttributeController extends AbstractController
{
    /**
     * @Route("", name="index", methods={"GET"})
     */
    public function index(): Response
    {
        $attributes = $this->getDoctrine()
                           ->getRepository(Element::class)
                           ->findBy([], ['name' => 'ASC'])
        ;

        return $this->render('attributes/index.html.twig', [
            'attributes' => $attributes,
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET", "POST"})
     */
    public function add(Request $request): Response
    {
        $form = $this->createForm(ElementType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->redirectToRoute('app.attributes.index');
        }

        return $this->render('attributes/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{uuid}", name="edit", methods={"GET", "POST"})
     * @ParamConverter("element", class="App\Entity\ConfigurationList\Element", options={"mapping": {"uuid": "uuid"}})
     */
    public function edit(Request $request, Element $element): Response
    {
        $form = $this->createForm(ElementType::class, $element);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->redirectToRoute('app.attributes.index');
        }

        return $this->render('attributes/edit.html.twig', [
            'form'      => $form->createView(),
            'attribute' => $element,
        ]);
    }

    /**
     * @Route("/{uuid}", name="delete", methods={"DELETE"})
     * @ParamConverter("element", class="App\Entity\ConfigurationList\Element", options={"mapping": {"uuid": "uuid"}})
     */
    public function delete(Request $request, Element $element): Response
    {
        // TODO: csrf check

        $em = $this->getDoctrine()->getManager();
        $em->remove($element);
        $em->flush();

        return $this->redirectToRoute('app.attributes.index');
    }
}
