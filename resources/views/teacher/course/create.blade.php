@extends('layouts.backend.app')

@section('title', 'Add Course')

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
                            <li class="breadcrumb-item active">Course</li>
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
                                <h3 class="card-title">Add Course</h3>
                            </div>
                            <!-- /.card-header -->

                            <!-- form start -->
                            <form role="form" action="{{ route('dept_office.course.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Course Title</label>
                                                <input type="text" class="form-control" id="name" name="course_title" value="{{ old('course_title') }}" placeholder="Enter Course Title" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Course Code</label>
                                                <input type="text" class="form-control" id="name" name="course_code" value="{{ old('course_code') }}" placeholder="Enter Course Code" required>
                                            </div>
                                        </div>
                                        @if($dept->is_semester == 1)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Semester</label>
                                                    <select name="year_semester_id" class="form-control" required>
                                                        <option value="" selected disabled>Select Semester</option>
                                                        @foreach($semesters as $semester)
                                                            <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Year</label>
                                                    <select name="year_semester_id" class="form-control" required>
                                                        <option value="" selected disabled>Select Year</option>
                                                        @foreach($years as $year)
                                                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Credit Hour</label>
                                                <input type="number" min="0.5" max="4.0" step=".1" class="form-control" name="credit_hour" value="{{ old('credit_hour') }}" placeholder="Enter Credit Hour">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>In-course Marks</label>
                                                <input type="number" class="form-control" name="incourse_marks" value="{{ old('incourse_marks') }}" placeholder="Enter In-course Marks">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Final Marks</label>
                                                <input type="number" class="form-control" name="final_marks" value="{{ old('final_marks') }}" placeholder="Enter Final Marks">
                                            </div>
                                        </div>

                                        <div class="col-auto my-1">
                                            <div class="custom-control custom-checkbox mr-sm-2">
                                                <input type="checkbox" name="is_lab" class="custom-control-input" id="customControlAutosizing">
                                                <label class="custom-control-label" for="customControlAutosizing">It's a Lab Course / Viva</label>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-md-right">Add Course</button>
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