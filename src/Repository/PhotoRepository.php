<?php

namespace App\Repository;

use App\Entity\ConfigurationList\Element;
use App\Entity\Photo;
use Doctrine\ORM\Query\ResultSetMapping;


class PhotoRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByCount(Element $element)
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(Photo::class, 'p');
        $rsm->addFieldResult('p', 'id', 'id');
        $rsm->addFieldResult('p', 'uuid', 'uuid');
        $rsm->addFieldResult('p', 'metadata', 'metadata');
        $rsm->addFieldResult('p', 'coordinates', 'coordinates');

        $query = $this->_em->createNativeQuery('
            SELECT
                p.id as id,
                p.uuid as uuid,
                p.metadata as metadata,
                ST_AsText(p.coordinates)  as coordinates
            FROM
                arc_photo.element e
                    INNER JOIN
                arc_photo.photo p ON e.photo_id = p.id
            WHERE
                element_id = :element_id
            GROUP BY
                p.id
            HAVING
                COUNT(*) > 2         
        ', $rsm);


        $query->setParameter('element_id', $element->getId());

        return $query->getResult();
    }
}
