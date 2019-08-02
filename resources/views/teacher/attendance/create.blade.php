@extends('layouts.backend.app')

@section('title', 'Add Attendance')

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
                            <li class="breadcrumb-item active">Add Attendance</li>
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
                                <h3 class="card-title">Add Attendance</h3>
                            </div>
                            <!-- /.card-header -->

                            <div class="card-body">
                                <div class="col-6 offset-3 text-center">
                                    <h3>Dept : {{ $course->dept->name }}</h3>
                                    <h5>Session : {{ $course->session->name }} | Subject : {{ $course->course->course_code }} - {{ $course->course->course_title }}</h5>
                                    <h6>{{ $course->dept->is_semester == 1 ? 'Semester' : 'Year' }} : {{ $course->dept->is_semester == 1 ? $semester->name : $year->name }}   |  Teacher Name : {{ $course->teacher->name }}</h6>
                                    <h4>Date : {{ Carbon\Carbon::now()->format('D, d F Y') }}</h4>
                                </div>
                                <a href="" class="btn btn-info float-right">View Attendance</a>

                            </div>

                            <!-- form start -->
                            <form role="form" action="{{ route('teacher.attendance.store') }}" method="post">
                                @csrf
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped text-center">
                                        <thead>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Class Roll</th>
                                            <th>Name</th>
                                            <th>Attendance</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Class Roll</th>
                                            <th>Name</th>
                                            <th>Attendance</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>
                                            @foreach($students as $key => $student)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $student->class_roll }}</td>
                                                    <td>{{ $student->name }}</td>
                                                    <td>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="p-option{{ $student->id }}"  name="attend[{{ $student->id }}]" value="P" class="custom-control-input" checked>
                                                            <label class="custom-control-label" for="p-option{{ $student->id }}">Present</label>
                                                        </div>
                                                        <div class="custom-control custom-radio custom-control-inline">
                                                            <input type="radio" id="a-option{{ $student->id }}" name="attend[{{ $student->id }}]" value="A" class="custom-control-input">
                                                            <label class="custom-control-label" for="a-option{{ $student->id }}">Absent</label>
                                                        </div>
                                                    </td>

                                                </tr>
                                            @endforeach
                                            <input type="hidden" name="course_teacher" value="{{ $course->id }}">

                                        </tbody>

                                    </table>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-md-right">Add Attendance</button>
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