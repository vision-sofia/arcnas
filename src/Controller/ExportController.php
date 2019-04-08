<?php

namespace App\Controller;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/export/objects", name="app.export.")
 */
class ExportController extends AbstractController
{
    /**
     * @Route("", name="all")
     */
    public function export(Request $request): Response
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

        if ('yes' === $request->query->get('view')) {
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
