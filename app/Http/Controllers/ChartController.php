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
                245, // British Masters
                354, // US PGA
                896, // Himmerland
                56, // Porsche
                61, // Scandinavian mix
                2751, // US Open
                547, // BWM International Open
                155, // Irish Open
                1135, // Scottish Open
                1947, // The Open
                357, // Gazoo Open
                192, // ISPS Handa
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
                166, // British Masters
                580, // US PGA
                558, // Himmerland
                249, // Porsche
                161, // Scandinavian mix
                4127, // US Open
                669, // BWM International Open
                213, // Irish Open
                368, // Scottish Open
                3049, // The Open
                120, // Gazoo Open
                97, // ISPS Handa
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
                477, // British Masters
                669, // US PGA
                532, // Himmerland
                302, // Porsche
                176, // Scandinavian mix
                4072, // US Open
                678, // BWM International Open
                147, // Irish Open
                1375, // Scottish Open
                1952, // The Open
                429, // Gazoo Open
                296, // ISPS Handa
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
                449, // British Masters
                823, // US PGA
                532, // Himmerland
                75, // Porsche
                410, // Scandinavian mix
                461, // US Open
                198, // BWM International Open
                192, // Irish Open
                500, // Scottish Open
                1390, // The Open
                76, // Gazoo Open
                124, // ISPS Handa
            ],
        ];
    }
}
