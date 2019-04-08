<?php

namespace App\Controller\API;

use App\Services\GeoJSON;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/search", name="api.search.")
 */
class SearchController extends AbstractController
{
    protected $session;
    protected $geoJSON;

    public function __construct(SessionInterface $session, GeoJSON $geoJSON)
    {
        $this->session = $session;
        $this->geoJSON = $geoJSON;
    }

    /**
     * @Route("", name="index")
     */
    public function index(Request $request): JsonResponse
    {
        $queryElementId = $request->query->get('element');

        if ($queryElementId) {
            $queryElementId = (int)$queryElementId;

            $this->session->set('element_id', $queryElementId);

            $elementId = $queryElementId;
        } else {
            $elementId = $this->session->get('element_id');
        }

        /** @var Connection $conn */
        $conn = $this->getDoctrine()->getConnection();

        if ($elementId) {
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
                    e.element_id = :element_id
                
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

        $result = $this->geoJSON->generateFromResult($stmt);

        return new JsonResponse($result);
    }
}
