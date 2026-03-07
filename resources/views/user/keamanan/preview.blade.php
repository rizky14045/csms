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
		<h4 class="fs-18 fw-semibold m-0">KPI</h4>
	</div>

	<div class="text-end">
		<ol class="breadcrumb m-0 py-0">
			<li class="breadcrumb-item">
				<a href="{{ route('dashboard') }}">Dashboard</a>
			</li>
			<li class="breadcrumb-item active">Data KPI</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-xl-12">
		<div class="card">
			<div class="card-body">

				<a href="{{ route('user.keamanan.index') }}" class="btn btn-danger mb-3">
					Kembali
				</a>

				<div class="accordion" id="formAccordion">

					@foreach ($areas as $area)

						<div class="accordion-item">

							<h2 class="accordion-header bg-light" id="heading{{ $area['id'] }}">
								<button
									class="accordion-button collapsed"
									type="button"
									data-bs-toggle="collapse"
									data-bs-target="#collapse{{ $area['id'] }}"
								>
									{{ $area['name'] }}
								</button>
							</h2>

							<div
								id="collapse{{ $area['id'] }}"
								class="accordion-collapse collapse"
								data-bs-parent="#formAccordion"
							>

								<div class="accordion-body">

									<table class="table table-bordered">

										<thead class="table-light">
											<tr>
												<th class="text-center">No</th>
												<th class="text-center">Sub Area</th>
												<th class="text-center">Level</th>
												<th class="text-center">Uraian</th>
												<th class="text-center">Catatan Assesment (Eviden)</th>
												<th class="text-center">File</th>
											</tr>
										</thead>

										<tbody>

											@foreach ($area['sub_areas'] as $subArea)

												@php
													$totalRowspan = 0;

													foreach ($subArea['levels'] as $level) {
														$totalRowspan += 1 + count($level['notes']);
													}

													$firstLevel = true;
												@endphp

												<tr>

													<td rowspan="{{ $totalRowspan }}">
														{{ $loop->iteration }}
													</td>

													<td rowspan="{{ $totalRowspan }}" class="w-25">
														<h6 class="fw-bold">
															{{ $subArea['name'] }}
														</h6>

														<p>
															Deskripsi : {{ $subArea['description'] }}
														</p>

														<span>
															Referensi : {{ $subArea['reference'] }}
														</span>
													</td>

													@foreach ($subArea['levels'] as $level)

														@if (!$firstLevel)
															<tr>
														@endif

														<td rowspan="{{ count($level['notes']) + 1 }}">
															{{ $level['level'] }}
														</td>

														<td rowspan="{{ count($level['notes']) + 1 }}" class="w-25">
															<p>
																{{ $level['description'] }}
															</p>
														</td>

														</tr>

														@foreach ($level['notes'] as $note)

															<tr>

																<td>
																	{{ $note['note'] }}
																</td>

																<td>

																	<div class="d-flex gap-2 align-items-center">

																		@if ($note['attachment_file'])

																			<a
																				href="{{ asset('uploads/attachment_file_kpi_file/'.$note['attachment_file']) }}"
																				class="btn btn-info btn-sm"
																				download
																			>
																				Download
																			</a>

																		@endif

																	</div>

																</td>

															</tr>

														@endforeach

														@php
															$firstLevel = false;
														@endphp

													@endforeach

												</tr>

											@endforeach

										</tbody>

									</table>

								</div>
							</div>

						</div>

					@endforeach

				</div>

			</div>
		</div>
	</div>
</div>

@endsection