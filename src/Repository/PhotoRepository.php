<?php

namespace App\Repository;

use App\Entity\ConfigurationList\Condition;
use App\Entity\ConfigurationList\Element;
use App\Entity\Photo;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\Query\ResultSetMapping;


class PhotoRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByCount(Element $element, ?Condition $condition, int $count, QueryBuilder $queryBuilder)
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(Photo::class, 'p');
        $rsm->addFieldResult('p', 'id', 'id');
        $rsm->addFieldResult('p', 'uuid', 'uuid');
        $rsm->addFieldResult('p', 'metadata', 'metadata');
        $rsm->addFieldResult('p', 'coordinates', 'coordinates');
        $rsm->addFieldResult('p', 'file', 'file');


        $query = $queryBuilder
            ->addSelect('p.id as id,
                p.uuid as uuid,
                p.metadata as metadata,
                p.file as file,
                ST_AsText(p.coordinates)  as coordinates')
            ->from('arc_photo.element', 'e')
            ->innerJoin('e', 'arc_photo.photo', 'p', 'e.photo_id = p.id')
            ->andWhere('element_id = :element_id')
            ->groupBy('p.id')
            ->andHaving('COUNT(*) >= :count')
        ;

        if ($condition) {
            $query->andWhere('condition_id = :condition_id');
        }

        $sql = $query->getSQL();

        $query = $this->_em->createNativeQuery($sql, $rsm);

        $query->setParameter('count', $count);
        $query->setParameter('element_id', $element->getId());

        if ($condition) {
            $query->setParameter('condition_id', $condition->getId());
        }

        return $query->getResult();
    }
}
