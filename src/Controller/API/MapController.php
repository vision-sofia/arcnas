<?php

namespace App\Controller\API;

use App\Services\GeoJSON;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/map", name="api.map.")
 */
class MapController extends AbstractController
{
    protected $geoJSON;

    public function __construct(GeoJSON $geoJSON)
    {
        $this->geoJSON = $geoJSON;
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
                    w.uuid,
                    w.name, 
                    w.attributes, 
                    ST_AsGeoJSON(w.coordinates) as coordinates
                FROM 
                    arc_world_object.world_object w
            ');

        $stmt->execute();

        $result = $this->geoJSON->generateFromResult($stmt);

        return new JsonResponse($result);
    }
}
