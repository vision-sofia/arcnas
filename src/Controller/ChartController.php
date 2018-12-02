<?php

namespace App\Controller;

use App\Entity\ConfigurationList\Element;
use App\Entity\Photo;
use App\Services\Utils;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChartController extends AbstractController
{


    /**
     * @Route("/charts", name="app.charts")
     */
    public function index(): Response
    {
        /** @var Connection $conn */
        $conn = $this->getDoctrine()->getConnection();

        $stmt = $conn->prepare('
            SELECT
                COUNT(*), e.name
            FROM
                arc_photo.element pe
                    INNER JOIN
                configuration_list.element e ON pe.element_id = e.id
            GROUP BY
                e.name
            ORDER BY count(*) DESC        
        ');

        $stmt->execute();

        $elementsStat = $stmt->fetchAll();



        return $this->render('charts/index.html.twig', [
            'elements_stats' => $elementsStat
        ]);
    }
}
