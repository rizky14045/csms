<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class AssesmentReportExport implements FromView, WithColumnWidths, WithTitle, WithEvents, WithCharts
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.assesment-report', $this->data);
    }

    public function title(): string
    {
        return 'Hasil Assesment';
    }

    public function columnWidths(): array
    {
        return ['A' => 8, 'B' => 45, 'C' => 60, 'D' => 15];
    }

    /**
     * Chart is actually built and attached to the sheet in the AfterSheet
     * event below (it needs to locate the report table's rows first).
     * This just needs to exist so the writer enables chart output.
     */
    public function charts()
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $categoryCount = count($this->data['categories']);

                if ($categoryCount === 0) {
                    return;
                }

                $chartAnchorRow = null;
                $reportHeaderRow = null;
                $highestRow = $sheet->getHighestRow();

                for ($row = 1; $row <= $highestRow; $row++) {
                    if ($chartAnchorRow === null && trim((string) $sheet->getCell("A{$row}")->getValue()) === 'Grafik Hasil Assesment per Proses Bisnis') {
                        $chartAnchorRow = $row;
                    }
                    if ($reportHeaderRow === null && trim((string) $sheet->getCell("B{$row}")->getValue()) === 'Proses Bisnis') {
                        $reportHeaderRow = $row;
                    }
                    if ($chartAnchorRow !== null && $reportHeaderRow !== null) {
                        break;
                    }
                }

                if ($chartAnchorRow === null || $reportHeaderRow === null) {
                    return;
                }

                $dataStartRow = $reportHeaderRow + 1;
                $dataEndRow = $reportHeaderRow + $categoryCount;
                $sheetTitle = $this->title();

                $dataSeriesLabels = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'{$sheetTitle}'!\$C\${$reportHeaderRow}", null, 1, ['Nilai']),
                ];
                $dataSeriesCategories = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, "'{$sheetTitle}'!\$B\${$dataStartRow}:\$B\${$dataEndRow}", null, $categoryCount),
                ];
                $dataSeriesValues = [
                    new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, "'{$sheetTitle}'!\$C\${$dataStartRow}:\$C\${$dataEndRow}", null, $categoryCount),
                ];

                $series = new DataSeries(
                    DataSeries::TYPE_RADARCHART,
                    null,
                    range(0, count($dataSeriesValues) - 1),
                    $dataSeriesLabels,
                    $dataSeriesCategories,
                    $dataSeriesValues
                );
                $series->setPlotStyle(DataSeries::STYLE_FILLED);

                $plotArea = new PlotArea(null, [$series]);
                $legend = new Legend(Legend::POSITION_BOTTOM, null, false);
                $title = new Title('Assesment Maturity Level Pengelolaan Proses Bisnis Pengamanan');

                $chart = new Chart('chart_assesment', $title, $legend, $plotArea);
                $chart->setTopLeftPosition('A' . ($chartAnchorRow + 1));
                $chart->setBottomRightPosition('H' . ($chartAnchorRow + 15));

                $sheet->addChart($chart);

                $triwulan = $this->data['assesment']->triwulan ?? null;
                if ($triwulan) {
                    $sheet->setCellValue('A' . ($chartAnchorRow + 16), 'TW - ' . $triwulan);
                    $sheet->getStyle('A' . ($chartAnchorRow + 16))->getFont()->setBold(true);
                }

                $sheet->getStyle('B:C')->getAlignment()->setWrapText(true)->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
            },
        ];
    }
}
