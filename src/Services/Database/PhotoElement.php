<?php


namespace App\Services\Database;


use Doctrine\ORM\EntityManagerInterface;

class PhotoElement
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateSector(int $sectorId, string $coordinates): void
    {
        $conn = $this->entityManager->getConnection();

        $stmt = $conn->prepare('
            UPDATE
                arc_photo.element
            SET
                sector = ST_GeometryFromText(:sector)
            WHERE
                id = :id
        ');

        $stmt->bindValue('id', $sectorId);
        $stmt->bindValue('sector', sprintf('POLYGON((%s))', $coordinates));
        $stmt->execute();
    }
}