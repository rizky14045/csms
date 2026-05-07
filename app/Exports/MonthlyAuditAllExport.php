<?php

namespace App\Exports;

use App\Exports\AghtExport;
use App\Exports\BudgetAbsorptionExport;
use App\Exports\ForeignWorkerExport;
use App\Exports\FormAttributeExport;
use App\Exports\FormFormulirExport;
use App\Exports\SecurityFormExport;
use App\Exports\SecurityProgramExport;
use App\Exports\VulnerabilityExternalExport;
use App\Exports\VulnerabilityInternalExport;
use App\Exports\WorkerSumExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MonthlyAuditAllExport implements WithMultipleSheets
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [

            new FormFormulirExport($this->data),

            new WorkerSumExport([
                'persons' => $this->data['persons'],
                'securities' => $this->data['securities'],
                'agreements' => $this->data['agreements'],
            ]),

            new SecurityFormExport([
                'forms' => $this->data['forms']
            ]),

            new AghtExport([
                'aghts' => $this->data['aghts']
            ]),

            new FormAttributeExport([
                'attributes' => $this->data['attributes'],
                'administrations' => $this->data['administrations'],
                'saranas' => $this->data['saranas'],
            ]),

            new ForeignWorkerExport([
                'foreigns' => $this->data['foreigns']
            ]),

            new SecurityProgramExport([
                'programs' => $this->data['programs']
            ]),

            new VulnerabilityInternalExport([
                'internals' => $this->data['internals']
            ]),

            new VulnerabilityExternalExport([
                'externals' => $this->data['externals']
            ]),

            new BudgetAbsorptionExport([
                'administrasi' => $this->data['administrasi'],
                'pemeliharaan' => $this->data['pemeliharaan'],
            ]),
        ];
    }
}