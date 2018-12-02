<?php


namespace App\Services;


class Utils
{
    public function transform(array $metadata, array $elements): array
    {
        $result = [];

        if (!empty($metadata['sectors'])) {
            foreach ($metadata['sectors'] as $k => $v) {

                $v['element'] = $elements[$v['element_id']];
                $v['geo'] = json_decode($v['geo'], true);
                $v['position'] = [
                    'top'    => $v['geo']['coordinates'][0][0][1],
                    'left'   => $v['geo']['coordinates'][0][0][0],
                    'width'  => $v['geo']['coordinates'][0][2][0] - $v['geo']['coordinates'][0][0][0],
                    'height' => $v['geo']['coordinates'][0][2][1] - $v['geo']['coordinates'][0][0][1],
                ];

                $result[] = $v;
            }
        }

        return $result;
    }
}