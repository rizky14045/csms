<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;

class MarturityExport implements FromView, WithTitle
{
    protected array  $areas;
    protected object $marturity;

    public function __construct(array $areas, object $marturity)
    {
        $this->areas     = $areas;
        $this->marturity = $marturity;
    }

    public function view(): View
    {
        return view('exports.marturity', [
            'areas'    => $this->areas,
            'marturity' => $this->marturity,
        ]);
    }

    public function title(): string
    {
        return 'Maturity ' . $this->marturity->year . ' S' . $this->marturity->semester;
    }
}
