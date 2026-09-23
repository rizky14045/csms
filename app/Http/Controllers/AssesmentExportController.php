<?php

namespace App\Http\Controllers;

use App\Exports\AssesmentReportExport;
use App\Models\Assesment;
use App\Services\Assesment\AssesmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AssesmentExportController extends Controller
{
    public function export(Request $request, Assesment $assesment, AssesmentService $service)
    {
        $user = auth()->user();
        $isCentral = in_array($user->type, ['admin', 'pusat'], true);

        if (!$isCentral && $assesment->unit_id != $user->unit_id) {
            abort(404);
        }

        if ((int) $assesment->send_status !== 2) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'signer1' => 'required|string|max:100',
            'signer2' => 'required|string|max:100',
        ], [
            'signer1.required' => 'Nama penanda tangan 1 wajib diisi!',
            'signer2.required' => 'Nama penanda tangan 2 wajib diisi!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $response = $service->getReportAssesment($assesment);
        if (!getStatus($response)) {
            abort(500);
        }

        $assesment->load('vendor', 'unit');
        $categories = \App\Models\SignCategoryAssesment::with(['questions', 'questions.levels'])
            ->where('assesment_id', $assesment->id)
            ->get()
            ->map(function ($category) {
                $category->average = number_format($category->questions->avg('evaluation_unit'), 2);
                return $category;
            });

        $filename = 'hasil-assesment-' . Str::slug($assesment->vendor->name ?? 'bujp')
            . '-tw' . $assesment->triwulan . '-' . $assesment->year . '.xlsx';

        return Excel::download(new AssesmentReportExport([
            'assesment'  => $assesment,
            'categories' => $categories,
            'signer1'    => $request->signer1,
            'signer2'    => $request->signer2,
        ]), $filename);
    }
}
