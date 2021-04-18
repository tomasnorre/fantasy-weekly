<?php

declare(strict_types=1);

namespace App\Http\Controllers;


class ChartController
{
    public function index()
    {
        return view(
            'Chart.index',
            [
                'data' => $this->prepareData($this->getData()),
            ]
        );
    }

    private function prepareData(array $data): array
    {
        $result = [];
        foreach($data as $team => $value) {
            $total = 0;
            foreach($value as $val) {
                $total += $val;
                $result[$team][] = $total;
            }
        }

        return $result;
    }

    private function getData(): array
    {
        return [
            'Kasper' => [
                670, // WGC
                126, // Qatar
                313, // Magic Kenya
                603, // Dell Technology
                970, // The Masters
                178, // Austrian
            ],
            'Mathias' => [
                1478, // WGC
                76, // Qatar
                105, // Magic Kenya
                580, // Dell Technology
                460, // The Masters
                229, // Austrian
            ],
            'Morten' => [
                1094, // WGC
                116, // Qatar
                533, // Magic Kenya
                878, // Dell Technology
                846, // The Masters
                146, // Austrian
            ],
            'Tomas' => [
                1130, // WGC
                77, // Qatar
                68, // Magic Kenya
                525, // Dell Technology
                324, // The Masters
                247, // Austrian
            ]
        ];
    }
}
