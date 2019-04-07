<?php

namespace App\Controller\API;

use App\Services\Utils;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/map", name="api.map.")
 */
class MapController extends AbstractController
{
    protected $queryBuilder;
    protected $utils;

    public function __construct(Utils $utils)
    {
        $this->utils = $utils;
    }

    /**
     * @Route("", name="index")
     */
    public function index(Request $request): JsonResponse
    {
        $elementId = $request->query->get('element');

        /** @var Connection $conn */
        $conn = $this->getDoctrine()->getConnection();

        if($elementId) {
            $stmt = $conn->prepare('
                SELECT 
                    w.uuid,
                    w.name, 
                    w.attributes, 
                    ST_AsGeoJSON(w.coordinates) as coordinates
                FROM 
                    arc_world_object.world_object w
                        INNER JOIN
                    arc_photo.element e ON w.id = e.world_object_id
                WHERE
                    e.id = :element_id
                
            ');

            $stmt->bindValue('element_id', $elementId);
            $stmt->execute();

        } else {
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
        }

        $result = [];

        while ($row = $stmt->fetch()) {
            $attributes = json_decode($row['attributes'], true);

            if(empty($attributes)) {
                $attributes = [];
            }

            $properties = [
                'id' => $row['uuid'],
                'name' => $row['name'],
            ] + $attributes;

            $result[] = [
                'type'       => 'Feature',
                'properties' => $properties,
                'geometry'   => json_decode($row['coordinates']),
            ];
        }

        return new JsonResponse($result);
    }
}
