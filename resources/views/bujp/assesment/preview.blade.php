@extends('layout.app')
@section('styles')
<style>
    .accordion-button::after {
        filter: invert(100%);
    }
</style>
@stop

@section('content')

<div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
    <div class="flex-grow-1">
        <h4 class="fs-18 fw-semibold m-0">Assesment</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('bujp.assesment.index', ['unit' => request()->query('unit')])}}">Assesment</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                <div class="accordion" id="formAccordion">

                    @foreach ($categories as $category)

                    <div class="accordion-item">

                        <h2 class="accordion-header bg-light" id="heading{{$category->id}}">
                            <button class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{$category->id}}">
                                {{$category->category_name}}
                            </button>
                        </h2>

                        <div id="collapse{{$category->id}}"
                             class="accordion-collapse collapse"
                             data-bs-parent="#formAccordion">

                            <div class="accordion-body">

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle text-center" style="white-space:nowrap;">

                                        <thead class="table-light">
                                            <tr>
                                                <th style="min-width:60px;">No</th>
                                                <th style="min-width:250px;">Indikator</th>
                                                <th style="min-width:120px;">Level</th>
                                                <th style="min-width:300px;">Level Penilaian</th>
                                                <th style="min-width:120px;">Nilai</th>
                                                <th style="min-width:200px;">Note</th>
                                                <th style="min-width:180px;">File</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @foreach ($category->questions as $question)
                                            <tr>

                                                <td>{{$loop->iteration}}</td>

                                                <td style="text-align:left;">
                                                    {{$question->indicator}}
                                                </td>

                                                <td class="text-start">
                                                    @foreach ($question->levels as $level)
                                                        <div>Level {{$level->level}}</div>
                                                    @endforeach
                                                </td>

                                                <td class="text-start">
                                                    @foreach ($question->levels as $level)
                                                        <div>{{$level->level_description}}</div>
                                                    @endforeach
                                                </td>

                                                <td style="
                                                    @php
                                                        $level = $question->level ?? 0;

                                                        switch ($level) {
                                                            case 5:
                                                                $bg = '#28a745'; // hijau
                                                                break;
                                                            case 4:
                                                                $bg = '#20c997'; // teal
                                                                break;
                                                            case 3:
                                                                $bg = '#ffc107'; // kuning
                                                                break;
                                                            case 2:
                                                                $bg = '#fd7e14'; // orange
                                                                break;
                                                            case 1:
                                                                $bg = '#dc3545'; // merah
                                                                break;
                                                            default:
                                                                $bg = '#6c757d'; // abu
                                                                break;
                                                        }
                                                    @endphp

                                                    background: {{ $bg }};
                                                    color: {{ $level == 3 ? '#000' : '#fff' }};
                                                    font-weight: 600;
                                                    text-align: center;
                                                    vertical-align: middle;
                                                ">
                                                    {{ $level ?: '-' }}
                                                </td>

                                                <td style="text-align:left;">
                                                    {{$question->note ?? '-'}}
                                                </td>

                                                <td>
                                                    @php
                                                        $files = [];
                                                        if ($question->attachment_file) {
                                                            $decoded = json_decode($question->attachment_file, true);
                                                            $files = is_array($decoded) ? $decoded : [$question->attachment_file];
                                                        }
                                                    @endphp
                                                    @if(count($files) > 0)
                                                        <div style="display:flex; flex-direction:column; gap:4px; align-items:center;">
                                                            @foreach($files as $i => $file)
                                                            <a href="{{ asset('uploads/attachment_file_question_file/' . $file) }}"
                                                               class="btn btn-success btn-sm"
                                                               target="_blank"
                                                               style="display:inline-flex; align-items:center; gap:4px;">
                                                                ⬇ File {{ $i + 1 }}
                                                            </a>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span style="color:#999; font-size:12px;">
                                                            Tidak ada file
                                                        </span>
                                                    @endif
                                                </td>

                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

                    @endforeach

                    {{-- BUTTON BAWAH --}}
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{route('bujp.assesment.index', ['unit' => request()->query('unit')])}}"
                           class="btn btn-danger">
                            Kembali
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

@endsection