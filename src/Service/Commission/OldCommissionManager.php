<?php

declare(strict_types=1);

namespace App\Service\Commission;

class OldCommissionManager implements CommissionManagerInterface
{

    /**
     * @param string $filepath
     * @return float[]
     */
    public function calculateFromFile(string $filepath): array
    {
        $output = [];
        foreach (explode("\n", file_get_contents($filepath)) as $row) {
            if (empty($row)) {
                break;
            }
            $p = explode(",", $row);
            $p2 = explode(':', $p[0]);
            $value[0] = trim($p2[1], '"');
            $p2 = explode(':', $p[1]);
            $value[1] = trim($p2[1], '"');
            $p2 = explode(':', $p[2]);
            $value[2] = trim($p2[1], '"}');

            $binResults = file_get_contents('https://lookup.binlist.net/' . $value[0]);
            if (!$binResults) {
                die('error!');
            }
            $r = json_decode($binResults);
            $isEu = $this->isEu($r->country->alpha2);

            $rate = @json_decode(file_get_contents('https://api.exchangeratesapi.io/latest'), true)['rates'][$value[2]];
            if ($value[2] == 'EUR' or $rate == 0) {
                $amntFixed = $value[1];
            }
            if ($value[2] != 'EUR' or $rate > 0) {
                $amntFixed = $value[1] / $rate;
            }

            $output[] = $amntFixed * ($isEu == 'yes' ? 0.01 : 0.02);
        }
        return $output;
    }

    private function isEu($c): string
    {
        $result = false;
        switch ($c) {
            case 'AT':
            case 'BE':
            case 'BG':
            case 'CY':
            case 'CZ':
            case 'DE':
            case 'DK':
            case 'EE':
            case 'ES':
            case 'FI':
            case 'FR':
            case 'GR':
            case 'HR':
            case 'HU':
            case 'IE':
            case 'IT':
            case 'LT':
            case 'LU':
            case 'LV':
            case 'MT':
            case 'NL':
            case 'PO':
            case 'PT':
            case 'RO':
            case 'SE':
            case 'SI':
            case 'SK':
                $result = 'yes';
                return $result;
            default:
                $result = 'no';
        }
        return $result;
    }
}