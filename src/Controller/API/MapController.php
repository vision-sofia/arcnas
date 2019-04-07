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
                name, 
                attributes, 
                ST_AsGeoJSON(coordinates)  as coordinates
            FROM 
                arc_world_object.world_object        
        ');

        $stmt->execute();

        $result = [];

        while ($row = $stmt->fetch()) {
            # $result['name'] = $row['name'];
            # $result['attributes'] = json_decode($row['attributes']);

            $attr = json_decode($row['attributes'], true);

            $z = json_decode($row['coordinates']);
            $z->properties = $attr + ['name' => $row['name']];


            $result[] = [
                'type'       => 'Feature',
                'geometry'   => json_decode($row['coordinates']),
                'properties' => [
                    'name' => $row['name'],
                ]
            ];
        }

        return new JsonResponse($result);
    }
}
