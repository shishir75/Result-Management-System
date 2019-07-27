@extends('layouts.backend.app')

@section('title', 'Add Teacher')

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
                            <li class="breadcrumb-item active">Teacher</li>
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
                                <h3 class="card-title">Add Teacher</h3>
                            </div>
                            <!-- /.card-header -->

                            <!-- form start -->
                            <form role="form" action="{{ route('dept_office.teacher.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Teacher Name</label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Teacher Name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Teacher Designation</label>
                                                <select name="designation_id" class="form-control" required>
                                                    <option value="" selected disabled>Select Designation</option>
                                                    @foreach($designations as $designation)
                                                        <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Teacher Image</label>
                                                <input type="file" class="form-control" name="image" value="{{ old('image') }}" placeholder="Enter Teacher Image">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Research Interest</label>
                                                <input type="text" class="form-control" name="research" value="{{ old('research') }}" placeholder="Enter Teacher Research Interest">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Teacher About</label>
                                                <input type="text" class="form-control" name="about" value="{{ old('about') }}" placeholder="Enter Teacher About">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-md-right">Add Teacher</button>
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