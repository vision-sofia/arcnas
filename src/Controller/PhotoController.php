<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\PhotoElement;
use App\Form\PhotoElementType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhotoController extends AbstractController
{
    /**
     * @Route("/photos/{uuid}", name="app.photo", requirements={"uuid": "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     * @ParamConverter("photo", class="App\Entity\Photo", options={"mapping": {"uuid": "uuid"}})
     */
    public function index(Request $request, Photo $photo): Response
    {
        $photoElement = new PhotoElement();

        $form = $this->createForm(PhotoElementType::class, $photoElement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->detach($photo);

            $em->persist($photoElement);
            $em->flush();
/*
            return $this->redirectToRoute('app.photo', [
                'uuid' => $photo->getUuid(),
            ]);
*/
        }

        dump($photo);

        return $this->render('photo/index.html.twig', [
            'photo' => $photo,
            'form'  => $form->createView(),
        ]);
    }
}
