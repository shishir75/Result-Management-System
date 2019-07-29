@extends('layouts.backend.app')

@section('title', 'Update Department')

@push('css')

@endpush

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 offset-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('register.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Update Department</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Update Department</h3>
                            </div>
                            <!-- /.card-header -->

                            <!-- form start -->
                            <form role="form" action="{{ route('register.dept.update',$dept->id ) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Department Name</label>
                                                <input type="text" class="form-control" name="name" value="{{ $dept->name }}" placeholder="Enter Department Name">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Department Short Name</label>
                                                <input type="text" class="form-control" name="short_name" value="{{ $dept->short_name }}" placeholder="Enter Department Short Name">
                                            </div>
                                        </div>

                                        <div class="col-auto my-1">
                                            <div class="custom-control custom-checkbox mr-sm-2">
                                                <input type="checkbox" name="is_semester" {{ $dept->is_semester == 1 ? 'checked' : '' }} class="custom-control-input" id="customControlAutosizing">
                                                <label class="custom-control-label" for="customControlAutosizing">It's a Semester Based Dept</label>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-md-right">Update Department</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (left) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection

@push('js')

@endpush