<?php


namespace App\Services\WorldObject;

use Doctrine\ORM\EntityManagerInterface;

class Attribute
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function update(int $worldObjectId):void
    {
        $conn = $this->entityManager->getConnection();

        $stmt = $conn->prepare('
            UPDATE
                arc_world_object.world_object
            SET
                attributes = \'{}\'
            WHERE
                id = :id 
                AND attributes IS NULL
        ');

        $stmt->bindValue('id', $worldObjectId);
        $stmt->execute();

        $stmt = $conn->prepare('
            UPDATE
                arc_world_object.world_object w
            SET
                attributes = attributes || (
                    SELECT
                        json_object_agg(attributeKey, attributeValue)::jsonb
                    FROM (
                        SELECT
                            le.attribute_name as attributeKey,
                            COUNT(*) as attributeValue
                        FROM
                            arc_photo.element e
                                INNER JOIN
                            arc_configuration_list.element le ON e.element_id = le.id
                        WHERE
                            e.world_object_id = w.id
                        GROUP BY
                            le.attribute_name
                    ) z
                )
            WHERE
                w.id = :id
        ');

        $stmt->bindValue('id', $worldObjectId);
        $stmt->execute();
    }
}
