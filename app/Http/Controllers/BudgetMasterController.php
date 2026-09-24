<?php

namespace App\Http\Controllers;

use App\Models\BudgetMaster;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

/**
 * Master data penyerapan anggaran, terpisah per unit: diisi oleh unit masing-masing
 * (termasuk Pusat untuk kantor pusat) dan hanya untuk unit akun yang login.
 * Mengubah/menghapus master tidak memengaruhi laporan bulanan yang sudah ada
 * (baris laporan adalah salinan mandiri).
 */
class BudgetMasterController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view.budget.master')->only(['index']);
        $this->middleware('can:create.budget.master')->only(['create', 'store']);
        $this->middleware('can:edit.budget.master')->only(['edit', 'update']);
        $this->middleware('can:delete.budget.master')->only(['destroy']);
    }

    protected function unitId(): int
    {
        $unitId = auth()->user()->unit_id;
        abort_if(!$unitId, 403, 'Akun Anda belum terhubung ke unit.');

        return (int) $unitId;
    }

    protected function authorizeItem(BudgetMaster $item): void
    {
        if ((int) $item->unit_id !== $this->unitId()) {
            abort(404);
        }
    }

    protected function rules(): array
    {
        return [
            'type'                => 'required|in:pemeliharaan,administrasi',
            'kode_aktifitas'      => 'required|string|max:255',
            'kode_prk'            => 'required|string|max:255',
            'deskripsi_kegiatan'  => 'required|string|max:255',
            'jumlah_anggaran'     => 'required|numeric|min:0',
            'penyerapan_anggaran' => 'nullable|numeric|min:0',
            'keterangan'          => 'nullable|string|max:255',
        ];
    }

    protected function messages(): array
    {
        return [
            'type.required'               => 'Jenis Anggaran harus diisi!',
            'type.in'                     => 'Jenis Anggaran tidak sesuai!',
            'kode_aktifitas.required'     => 'Kode Aktifitas harus diisi!',
            'kode_prk.required'           => 'Kode PRK harus diisi!',
            'deskripsi_kegiatan.required' => 'Deskripsi Kegiatan harus diisi!',
            'jumlah_anggaran.required'    => 'Jumlah Anggaran harus diisi!',
            'jumlah_anggaran.numeric'     => 'Jumlah Anggaran harus berupa angka!',
        ];
    }

    public function index()
    {
        $items = BudgetMaster::where('unit_id', $this->unitId())
            ->orderBy('type')->orderBy('id')
            ->paginate(25);

        return view('budget-master.index', ['items' => $items]);
    }

    public function create()
    {
        $this->unitId();

        return view('budget-master.create');
    }

    public function store(Request $request)
    {
        $unitId = $this->unitId();
        $data = $request->validate($this->rules(), $this->messages());

        BudgetMaster::create($data + [
            'unit_id'    => $unitId,
            'user_id'    => auth()->id(),
            'created_by' => auth()->id(),
        ]);

        Alert::success('Tambah Berhasil', 'Master data penyerapan anggaran berhasil dibuat!');

        return redirect()->route('budget-master.index');
    }

    public function edit(BudgetMaster $item)
    {
        $this->authorizeItem($item);

        return view('budget-master.edit', ['item' => $item]);
    }

    public function update(Request $request, BudgetMaster $item)
    {
        $this->authorizeItem($item);
        $data = $request->validate($this->rules(), $this->messages());

        $item->update($data + ['updated_by' => auth()->id()]);

        Alert::success('Update Berhasil', 'Master data diperbarui. Laporan bulanan yang sudah ada tidak berubah.');

        return redirect()->route('budget-master.index');
    }

    public function destroy(BudgetMaster $item)
    {
        $this->authorizeItem($item);

        $item->update(['deleted_by' => auth()->id()]);
        $item->delete();

        Alert::success('Delete Berhasil', 'Master data dihapus. Laporan bulanan yang sudah ada tidak berubah.');

        return redirect()->route('budget-master.index');
    }
}
