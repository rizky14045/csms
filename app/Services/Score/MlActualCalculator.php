<?php

namespace App\Services\Score;

class MlActualCalculator
{
    /**
     * @param array $areas   hasil getAlMarturityArea (array, key sub_areas/levels)
     * @param array $checked map "levelId|filename" => true
     */
    public static function marturity(array $areas, array $checked)
    {
        $totalSubAreas = collect($areas)->sum(fn($a) => count($a['sub_areas']));
        $bobot = $totalSubAreas > 0 ? 1 / $totalSubAreas : 0;

        $result = ['subAreas' => [], 'total' => 0, 'bobot' => $bobot];

        foreach ($areas as $area) {
            foreach ($area['sub_areas'] as $subArea) {
                $chainOk = true;
                $hasil = 0;
                $levels = [];

                foreach ($subArea['levels'] as $lvl) {
                    $files = json_decode($lvl['attachment_files'] ?? '[]', true) ?: [];
                    $totalEv = max(1, (int)($lvl['total_evidence'] ?? 1));
                    $checkedCount = 0;
                    foreach ($files as $f) {
                        if (isset($checked[$lvl['id'] . '|' . $f])) {
                            $checkedCount++;
                        }
                    }

                    $counted = $chainOk ? round($checkedCount / $totalEv, 4) : 0;
                    $levels[$lvl['id']] = [
                        'canCheck' => $chainOk,
                        'checkedCount' => $checkedCount,
                        'counted' => $counted,
                    ];
                    $hasil += $counted;

                    if (!(count($files) > 0 && $checkedCount === count($files))) {
                        $chainOk = false;
                    }
                }

                $hasil = round($hasil, 4);
                $score = round($hasil * $bobot, 4);
                $result['subAreas'][$subArea['id']] = ['hasil' => $hasil, 'score' => $score, 'levels' => $levels];
                $result['total'] += $score;
            }
        }

        $result['total'] = round($result['total'], 4);
        return $result;
    }

    /**
     * @param array $checked map levelId => true
     */
    public static function kpi(array $areas, array $checked)
    {
        $totalSubAreas = collect($areas)->sum(fn($a) => count($a['sub_areas']));
        $bobot = $totalSubAreas > 0 ? 1 / $totalSubAreas : 0;

        $result = ['subAreas' => [], 'total' => 0, 'bobot' => $bobot];

        foreach ($areas as $area) {
            foreach ($area['sub_areas'] as $subArea) {
                $chainOk = true;
                $counted = 0;
                $levels = [];
                $levelCount = count($subArea['levels']);

                foreach ($subArea['levels'] as $lvl) {
                    $isChecked = !empty($lvl['attachment_file']) && isset($checked[$lvl['id']]);
                    $levels[$lvl['id']] = [
                        'canCheck' => $chainOk && !empty($lvl['attachment_file']),
                        'checked' => $isChecked,
                        'counted' => ($chainOk && $isChecked) ? 1 : 0,
                    ];
                    if ($chainOk && $isChecked) {
                        $counted++;
                    }
                    if (!$isChecked) {
                        $chainOk = false;
                    }
                }

                $hasil = $levelCount > 0 ? round($counted / $levelCount, 4) : 0;
                $score = round($hasil * $bobot, 4);
                $result['subAreas'][$subArea['id']] = ['hasil' => $hasil, 'score' => $score, 'levels' => $levels];
                $result['total'] += $score;
            }
        }

        $result['total'] = round($result['total'], 4);
        return $result;
    }
}
