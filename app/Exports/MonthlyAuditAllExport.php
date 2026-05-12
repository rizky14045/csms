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
                'monthlyReport' => $this->data['monthlyReport'],
                'securities' => $this->data['securities'],
                'agreements' => $this->data['agreements'],
            ]),

            new SecurityFormExport([
                'forms' => $this->data['forms'],
                'monthlyReport' => $this->data['monthlyReport']
            ]),

            new AghtExport([
                'monthlyReport' => $this->data['monthlyReport'],
                'aghts' => $this->data['aghts']
            ]),

            new FormAttributeExport([
                'attributes' => $this->data['attributes'],
                'administrations' => $this->data['administrations'],
                'saranas' => $this->data['saranas'],
                'monthlyReport' => $this->data['monthlyReport'],
            ]),

            new ForeignWorkerExport([
                'monthlyReport' => $this->data['monthlyReport'],
                'foreigns' => $this->data['foreigns']
            ]),

            new SecurityProgramExport([
                'monthlyReport' => $this->data['monthlyReport'],
                'programs' => $this->data['programs']
            ]),

            new VulnerabilityInternalExport([
                'monthlyReport' => $this->data['monthlyReport'],
                'internals' => $this->data['internals']
            ]),

            new VulnerabilityExternalExport([
                'monthlyReport' => $this->data['monthlyReport'],
                'externals' => $this->data['externals']
            ]),

            new BudgetAbsorptionExport([
                'monthlyReport' => $this->data['monthlyReport'],
                'administrasi' => $this->data['administrasi'],
                'pemeliharaan' => $this->data['pemeliharaan'],
            ]),
        ];
    }
}