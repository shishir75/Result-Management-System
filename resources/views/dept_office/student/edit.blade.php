@extends('layouts.backend.app')

@section('title', 'Update Student')

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
                            <li class="breadcrumb-item active">Update Student</li>
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
                                <h3 class="card-title">Update Student</h3>
                            </div>
                            <!-- /.card-header -->

                            <!-- form start -->
                            <form role="form" action="{{ route('dept_office.student.update',$student->id ) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Student Name</label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ $student->name }}" placeholder="Enter Teacher Name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Session</label>
                                                <select name="session" class="form-control" required>
                                                    <option value="" disabled>Select Session</option>
                                                    @foreach($sessions as $session)
                                                        <option value="{{ $session->name }}" {{ $student->session == $session->name ? 'selected' : '' }}>{{ $session->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="class_roll">Class Roll</label>
                                                <input type="text" class="form-control" id="class_roll" name="class_roll" value="{{ $student->class_roll }}" placeholder="Enter Class Roll" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="reg_no">Reg No</label>
                                                <input type="text" class="form-control" id="reg_no" name="reg_no" value="{{ $student->reg_no }}" placeholder="Enter Reg No" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exam_roll">Exam Roll</label>
                                                <input type="text" class="form-control" id="exam_roll" name="exam_roll" value="{{ $student->exam_roll }}" placeholder="Enter Exam Roll" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="hall">Hall Name</label>
                                                <select name="hall" class="form-control" required>
                                                    <option value="" disabled>Select Hall</option>
                                                    @foreach($halls as $hall)
                                                        <option value="{{ $hall->name }}" {{ $student->hall == $hall->name ? 'selected' : '' }}>{{ $hall->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="father_name">Father's Name</label>
                                                <input type="text" class="form-control" id="father_name" name="father_name" value="{{ $student->father_name }}" placeholder="Enter Father's Name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mother_name">Mother's Name</label>
                                                <input type="text" class="form-control" id="mother_name" name="mother_name" value="{{ $student->mother_name }}" placeholder="Enter Mother's Name" required>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-md-right">Update Student</button>
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