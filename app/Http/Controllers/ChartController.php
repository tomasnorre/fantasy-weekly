<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ChartController
{
    /**
     * @return Factory|View
     */
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
        foreach ($data as $team => $value) {
            $total = 0;
            foreach ($value as $val) {
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
                113, // Gran Canaria Open
                91, // Tenerife Open
                696, // Canary Islands Championship
            ],
            'Mathias' => [
                1478, // WGC
                76, // Qatar
                105, // Magic Kenya
                580, // Dell Technology
                460, // The Masters
                229, // Austrian
                297, // Gran Canaria Open
                163, // Tenerife Open
                1180, // Canary Islands Championship
            ],
            'Morten' => [
                1094, // WGC
                116, // Qatar
                533, // Magic Kenya
                878, // Dell Technology
                846, // The Masters
                146, // Austrian
                244, // Gran Canaria Open
                104, // Tenerife Open
                656, // Canary Islands Championship
            ],
            'Tomas' => [
                1130, // WGC
                77, // Qatar
                68, // Magic Kenya
                525, // Dell Technology
                324, // The Masters
                247, // Austrian
                333, // Gran Canaria Open
                66, // Tenerife Open
                779, // Canary Islands Championship
            ],
        ];
    }
}
