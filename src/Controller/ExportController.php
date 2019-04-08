<?php

namespace App\Controller;

use App\Services\GeoJSON;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/export", name="app.export.")
 */
class ExportController extends AbstractController
{
    protected $session;
    protected $geoJSON;

    public function __construct(SessionInterface $session, GeoJSON $geoJSON)
    {
        $this->session = $session;
        $this->geoJSON = $geoJSON;
    }

    /**
     * @Route("/all", name="all")
     */
    public function all(Request $request): Response
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

        if ('yes' === $request->query->get('download')) {
            $response = new Response(json_encode($result));

            $disposition = HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_ATTACHMENT,
                'objects.json'
            );

            $response->headers->set('Content-Disposition', $disposition);

            return $response;
        }

        return new JsonResponse($result);
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(): Response
    {
        $elementId = (int)$this->session->get('element_id');

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
                        INNER JOIN
                    arc_photo.element e ON w.id = e.world_object_id
                WHERE
                    e.element_id = :element_id
                
            ');

        $stmt->bindValue('element_id', $elementId);
        $stmt->execute();

        $result = $this->geoJSON->generateFromResult($stmt);

        return new JsonResponse($result);
    }
}
