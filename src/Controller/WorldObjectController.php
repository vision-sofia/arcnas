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
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        if ($form->isSubmitted() && $form->isValid()) {
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

    /**
     * @Route("/download", name="download")
     */
    public function download(Request $request): Response
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
            ');

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

        if ($request->query->get('view') === 'yes') {
            return new JsonResponse($result);
        }

        $response = new Response(json_encode($result));

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'objects.json'
        );

        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}
