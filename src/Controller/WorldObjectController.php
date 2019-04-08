<?php

namespace App\Controller;

use App\Entity\ConfigurationList\Element;
use App\Entity\Photo;
use App\Entity\PhotoElement;
use App\Entity\WorldObject\WorldObject;
use App\Form\Type\WorldObjectType;
use App\Services\Utils;
use CrEOF\Spatial\PHP\Types\Geography\Point;
use Doctrine\DBAL\Driver\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/objects", name="app.objects.")
 */
class WorldObjectController extends AbstractController
{
    protected $utils;

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    /**
     * @Route("", name="index")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(WorldObjectType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var WorldObject $worldObject */
            $worldObject = $form->getData();

            $point = new Point(1, 4);

            $worldObject->setCoordinates($point);

            $em = $this->getDoctrine()->getManager();
            $em->persist($worldObject);
            $em->flush();

            return $this->redirectToRoute('app.objects.index');
        }

        $worldObjects = $this->getDoctrine()->getRepository(WorldObject::class)->findAll();

        return $this->render('world-object/index.html.twig', [
            'form' => $form->createView(),
            'worldObjects' => $worldObjects,
        ]);
    }

    /**
     * @Route("/{uuid}", name="view", methods={"GET"})
     * @ParamConverter("element", class="App\Entity\WorldObject\WorldObject", options={"mapping": {"uuid": "uuid"}})
     */
    public function view(WorldObject $worldObject): Response
    {
        $photos = $this->getDoctrine()
            ->getRepository(Photo::class)
            ->findAll();

        $elements = $this->getDoctrine()
            ->getRepository(Element::class)
            ->findAll();

        $w = [];

        foreach ($elements as $element) {
            $id = $element->getId();
            $w[$id] = [
                'id' => $element->getId(),
                'color' => $element->getPrimaryColor(),
                'name' => $element->getName(),
            ];
        }

        $finalResult = [];

        foreach ($photos as $item) {
            $finalResult[] = [
                'item' => $item,
                'marks' => $this->utils->transform($item->getMetadata(), $w),
            ];
        }

        $attributes = $this->getDoctrine()
            ->getRepository(PhotoElement::class)
            ->findBy(['worldObject' => $worldObject]);

        return $this->render('world-object/view.html.twig', [
            'photos' => $finalResult,
            'worldObject' => $worldObject,
            'attributes' => $attributes,
            'center' => [
                'x' => $worldObject->getCoordinates()->getX(),
                'y' => $worldObject->getCoordinates()->getY(),
            ],
        ]);
    }

    /**
     * @Route("/{uuid}/download", name="download.one", methods={"GET"})
     * @ParamConverter("element", class="App\Entity\WorldObject\WorldObject", options={"mapping": {"uuid": "uuid"}})
     */
    public function download(WorldObject $worldObject): Response
    {
        /** @var Connection $conn */
        $conn = $this->getDoctrine()->getConnection();

        $stmt = $conn->prepare('
                SELECT 
                    w.uuid,
                    w.name, 
                    w.attributes, 
                    ST_AsGeoJSON(w.coordinates) as coordinates
                FROM 
                    arc_world_object.world_object w
                WHERE
                    id = :id
            ');

        $stmt->bindValue('id', $worldObject->getId());
        $stmt->execute();

        $result = [];

        while ($row = $stmt->fetch()) {
            $attributes = json_decode($row['attributes'], true);

            if (empty($attributes)) {
                $attributes = [];
            }

            $properties = [
                    'id' => $row['uuid'],
                    'name' => $row['name'],
                ] + $attributes;

            $result[] = [
                'type' => 'Feature',
                'properties' => $properties,
                'geometry' => json_decode($row['coordinates']),
            ];
        }

        return new JsonResponse($result);
    }
}
