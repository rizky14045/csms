@extends('bujp.layout.app')
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
            <li class="breadcrumb-item"><a href="{{route('bujp.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Tambah Data Assesment</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <!-- Komitmen Management -->
                <div class="accordion" id="formAccordion">

                    @foreach ($categories as $category)

                    <!-- Section for Each Category -->
                    <div class="accordion-item">
                        <h2 class="accordion-header bg-light" id="heading{{$category->id}}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$category->id}}" aria-expanded="false" aria-controls="collapse{{$category->id}}">
                                {{$category->category_name}}
                            </button>
                        </h2>
                        <div id="collapse{{$category->id}}" class="accordion-collapse collapse {{request('signCategoryId') == $category->id ? 'show' :''}}" aria-labelledby="heading{{$category->id}}" data-bs-parent="#formAccordion">
                            <div class="accordion-body">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Indikator</th>
                                            <th scope="col">Level</th>
                                            <th scope="col">Level Penilian</th>
                                            <th scope="col">Nilai</th>
                                            <th scope="col">Note</th>
                                            <th scope="col">File Upload</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($category->questions as $question)
                                            <tr>
                                                <form action="{{ route('bujp.assesment.updateQuestion', ['questionId' => $question->id]) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PATCH')
                
                                                    <td class="text-center">{{$loop->iteration}}</td>
                                                    <td>{{$question->indicator}}</td>
                                                    <td class="text-nowrap">
                                                        <ol class="list-unstyled">
                                                            @foreach ($question->levels as $level)
                                                                <li>Level {{$level->level}}</li>
                                                            @endforeach
                                                        </ol>
                                                    </td>
                                                    <td>
                                                        <ol>
                                                            @foreach ($question->levels as $level)
                                                                <li>{{$level->level_description}}</li>
                                                            @endforeach
                                                        </ol>
                                                    </td>
                                                    <td>
                                                        <select class="form-select" name="level_{{$question->id}}" required>
                                                            <option value="">Pilih Level</option>
                                                            <option value="1" {{$question->level == 1 ? 'selected' : ''}}>1</option>
                                                            <option value="2" {{$question->level == 2 ? 'selected' : ''}}>2</option>
                                                            <option value="3" {{$question->level == 3 ? 'selected' : ''}}>3</option>
                                                            <option value="4" {{$question->level == 4 ? 'selected' : ''}}>4</option>
                                                            <option value="5" {{$question->level == 5 ? 'selected' : ''}}>5</option>
                                                        </select>
                                                        @if ($errors->has('level_'.$question->id))
                                                            <div class="text-danger">{{ $errors->first('level_'.$question->id) }}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="note_{{$question->id}}" value="{{$question->note}}">
                                                        @if ($errors->has('note_'.$question->id))
                                                            <div class="text-danger">{{ $errors->first('note_'.$question->id) }}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input type="file" class="form-control" name="attachment_file_{{$question->id}}" accept=".pdf">
                                                        @if ($errors->has('attachment_file_'.$question->id))
                                                            <div class="text-danger">{{ $errors->first('attachment_file_'.$question->id) }}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            @if ($question->attachment_file)
                                                                <a href="{{ asset('uploads/attachment_file_question_file/'.$question->attachment_file) }}" class="btn btn-info btn-sm" download>Download</a>
                                                            @endif
                                                            <button type="submit" class="btn btn-success btn-sm">Update</button>
                                                        </div>
                                                    </td>
                                                </form>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                        <!-- Add more sections as needed -->
                        <div class="form-group row mt-5">
                            <div class="col-12">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="{{route('bujp.assesment.index')}}" class="btn btn-danger"> Back</a>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection

