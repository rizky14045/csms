@extends('layout.app')
@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Master Penyerapan Anggaran</h4>
    </div>
    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Penyerapan Anggaran</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-end mb-3 flex-wrap gap-2">
                    @if ($isGlobal)
                        <form method="GET" class="d-flex gap-2 align-items-end">
                            <div>
                                <label class="form-label">Unit</label>
                                <select name="unit_id" class="form-select">
                                    <option value="">Semua Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </form>
                    @else
                        <div></div>
                    @endif
                    @can('create.budget.master')
                        <a href="{{ route('budget-master.create') }}" class="btn btn-success">Tambah Data</a>
                    @endcan
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                @if ($isGlobal) <th>Unit</th> @endif
                                <th>Jenis</th>
                                <th>Kode Aktifitas</th>
                                <th>Kode PRK</th>
                                <th>Deskripsi Kegiatan</th>
                                <th>Jumlah Anggaran</th>
                                <th>Penyerapan Anggaran</th>
                                <th>Keterangan</th>
                                @canany(['edit.budget.master', 'delete.budget.master']) <th>Action</th> @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td>{{ $items->firstItem() + $loop->index }}</td>
                                    @if ($isGlobal) <td class="text-start">{{ $item->unit->name ?? '-' }}</td> @endif
                                    <td>{{ ucfirst($item->type) }}</td>
                                    <td>{{ $item->kode_aktifitas }}</td>
                                    <td>{{ $item->kode_prk }}</td>
                                    <td class="text-start">{{ $item->deskripsi_kegiatan }}</td>
                                    <td>{{ number_format($item->jumlah_anggaran, 2, ',', '.') }}</td>
                                    <td>{{ $item->penyerapan_anggaran !== null ? number_format($item->penyerapan_anggaran, 2, ',', '.') : '-' }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                    @canany(['edit.budget.master', 'delete.budget.master'])
                                    <td>
                                        @can('edit.budget.master')
                                            <a href="{{ route('budget-master.edit', ['item' => $item->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                        @endcan
                                        @can('delete.budget.master')
                                            <form action="{{ route('budget-master.destroy', ['item' => $item->id]) }}" method="post" class="d-inline" id="del-budget-{{ $item->id }}"
                                                  onsubmit="return confirmAction('del-budget-{{ $item->id }}', 'Hapus data?', 'Laporan bulanan yang sudah ada tidak berubah.', 'Ya, Hapus')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        @endcan
                                    </td>
                                    @endcanany
                                </tr>
                            @empty
                                <tr><td colspan="10" class="text-muted">Belum ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
