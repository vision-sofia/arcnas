<?php

namespace App\Controller;

use App\Entity\ConfigurationList\Element;
use App\Entity\Photo;

use App\Entity\WorldObject\WorldObject;
use App\Form\Type\WorldObjectType;
use App\Services\Utils;
use CrEOF\Spatial\PHP\Types\Geography\Point;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/world-objects", name="app.world-objects.")
 */
class WorldObjectController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(WorldObjectType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var WorldObject $worldObject */
            $worldObject = $form->getData();

            $point = new Point(1, 4);

            $worldObject->setCoordinates($point);

            $em = $this->getDoctrine()->getManager();
            $em->persist($worldObject);
            $em->flush();

            return $this->redirectToRoute('app.world-objects.index');
        }

        $worldObjects = $this->getDoctrine()->getRepository(WorldObject::class)->findAll();

        return $this->render('world-object/index.html.twig', [
            'form' => $form->createView(),
            'worldObjects' => $worldObjects
        ]);
    }
}
