<?php


namespace App\Services\Database;

use Doctrine\ORM\EntityManagerInterface;

class Photo
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateMetadata(int $photoId): void
    {
        $conn = $this->entityManager->getConnection();

        $stmt = $conn->prepare('
            UPDATE
                arc_photo.photo p
            SET
                metadata = \'{}\'
            WHERE
                id = :id
                AND metadata IS NULL
        ');

        $stmt->bindValue('id', $photoId);
        $stmt->execute();

        $stmt = $conn->prepare('
            UPDATE
                arc_photo.photo p
            SET
                metadata = jsonb_set(metadata, \'{"sectors"}\', (
                    SELECT
                        COALESCE((
                            SELECT
                                 COALESCE(jsonb_agg(row_to_json(z)), \'{}\')
                            FROM
                                (SELECT
                                    condition_id,
                                    element_id,
                                    world_object_id,
                                    ST_AsGeoJSON(sector) as geo
                                FROM
                                    arc_photo.element a
                                WHERE
                                    a.photo_id = p.id
                                ) z
                        ), \'null\')
                ) :: jsonb, true)
            WHERE
                p.id = :id          
        ');

        $stmt->bindValue('id', $photoId);
        $stmt->execute();
    }
}
