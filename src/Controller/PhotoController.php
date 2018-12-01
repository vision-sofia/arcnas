<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\PhotoElement;
use App\Event\Events;
use App\Event\PhotoElementTouchEvent;
use App\Form\PhotoElementType;
use Doctrine\DBAL\Driver\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhotoController extends AbstractController
{
    protected $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }


    /**
     * @Route("/photos/{uuid}", name="app.photo", requirements={"uuid": "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     * @ParamConverter("photo", class="App\Entity\Photo", options={"mapping": {"uuid": "uuid"}})
     */
    public function index(Request $request, Photo $photo, \App\Services\Database\Photo $photoDatabaseService): Response
    {
        $photoElement = new PhotoElement();
        $photoElement->setPhoto($photo);

        $form = $this->createForm(PhotoElementType::class, $photoElement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
         #   $em->detach($photo);

            $em->persist($photoElement);
            $em->flush();

            $coordinates = $form->get('coordinates')->getData();

            $ex = explode(',', $coordinates);

            $z = [];
            $i = 0;

            foreach ($ex as $coord) {
                $i++;

                if ($i % 2 == 0) {
                    $coord .= ',';
                }

                $z[] = $coord;
            }

            $z[] = $z[0];
            $z[] = $z[1];

            $w = implode(' ', $z);
            $w = rtrim($w,',');

            if ($coordinates) {
                $event = new PhotoElementTouchEvent($photoElement, $w);
                $this->eventDispatcher->dispatch(Events::PHOTO_ELEMENT_INSERT, $event);
            }

            $photoDatabaseService->updateMetadata($photo->getId());

            return $this->redirectToRoute('app.photo', [
                'uuid' => $photo->getUuid(),
            ]);
        }


        return $this->render('photo/index.html.twig', [
            'photo' => $photo,
            'form'  => $form->createView(),
        ]);
    }
}
