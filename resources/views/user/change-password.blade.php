@extends('user.layout.app')
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
        <h4 class="fs-18 fw-semibold m-0">Ubah Password</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{route('user.home.index')}}">Dashboard</a></li>
            <li class="breadcrumb-item active">Ubah Password</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            {{ $error }} <br>
                        @endforeach
                    </div>
                @endif
                <form action="{{route('user.updatePassword')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="form-group pb-3 col-md-6">
                        <label class="form-label" for="email-id">Password Lama</label>
                        <input type="password" required class="form-control mb-0" id="text-id" placeholder="Masukan Password" name="old_password">
                    </div>
                    <div class="form-group pb-3 col-md-6">
                        <label class="form-label" for="email-id">Password Baru</label>
                        <input type="password" required class="form-control mb-0" id="text-id" placeholder="Masukan Password" name="new_password">
                    </div>
                    <div class="form-group pb-3 col-md-6">
                        <label class="form-label" for="email-id">Ulangi Password Baru</label>
                        <input type="password" required class="form-control mb-0" id="text-id" placeholder="Masukan Password" name="repeat_password">
                    </div>
    
                    <div class="text-center pb-3">
                        <a href="{{route('admin.home.index')}}" class="btn btn-danger">Back</a>

                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
         
            </div> <!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div> <!-- end row -->
@endsection
@section('scripts')
@endsection

