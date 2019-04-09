<?php


namespace App\Services;

use Doctrine\DBAL\Driver\Statement;

class GeoJSON
{
    public function generateFromResult(Statement $stmt): array
    {
        $result = [];

        while ($row = $stmt->fetch()) {
            $attributes = json_decode($row['attributes'], true);

            if (empty($attributes)) {
                $attributes = [];
            }

            $properties = [
                    'id' => $row['uuid'],
                    'name' => $row['name'],
                ] + $attributes;

            $result[] = [
                'type' => 'Feature',
                'properties' => $properties,
                'geometry' => json_decode($row['coordinates']),
            ];
        }

        return $result;
    }
}
