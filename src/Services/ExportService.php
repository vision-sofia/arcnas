<?php


namespace App\Services;


use App\Entity\ConfigurationList\Element;
use App\Entity\Photo;

class ExportService
{
    public function buildItem(Photo $photo, string $photoBaseUrl, array $elements): array
    {
        return [
            'photo' => [
                'id' => $photo->getUuid(),
                'filename' => $photo->getFile(),
                'url' => $photoBaseUrl . '/' . $photo->getFile(),
                'addedAt' => $photo->getAddedAt()->format('Y-m-d'),
            ],
            'sectors' => $this->buildSectors($photo->getMetadata()['sectors'], $elements)
        ];
    }

    public function remapElements(array $elements)
    {
        return array_reduce($elements,
            static function (&$result, Element $item) {
                $result[$item->getId()] = [
                    'id' => $item->getUuid(),
                    'code' => $item->getAttributeName(),
                    'name' => $item->getName(),
                ];

                return $result;
            }, []
        );
    }

    private function buildSectors(array $sectors, array $elements): array
    {
        return array_map(static function ($sector) use ($elements) {
            return [
                'tag' => $elements[$sector['element_id']],
                'geometry' => json_decode($sector['geo'], false),
            ];
        }, $sectors);
    }
}