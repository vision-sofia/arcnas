<?php

namespace App\Controller\API;

use App\Services\Utils;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function index(): JsonResponse
    {
        /** @var Connection $conn */
        $conn = $this->getDoctrine()->getConnection();

        $stmt = $conn->prepare('
            SELECT 
                uuid,
                name, 
                attributes, 
                ST_AsGeoJSON(coordinates) as coordinates
            FROM 
                arc_world_object.world_object        
        ');

        $stmt->execute();

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
