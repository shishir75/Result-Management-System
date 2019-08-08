@extends('layouts.backend.app')

@section('title', 'Add Report Marks')

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
                            <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add Report Marks</li>
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
                                <h3 class="card-title">Add Report Marks</h3>
                            </div>
                            <!-- /.card-header -->

                            <div class="card-body">
                                <div class="col-6 offset-3 text-center">
                                    <h3>Dept : {{ $course->dept->name }}</h3>
                                    <h5>Session : {{ $course->session->name }} | Subject : {{ $course->course->course_code }} - {{ $course->course->course_title }}</h5>
                                    <h6>{{ $course->dept->is_semester == 1 ? 'Semester' : 'Year' }} : {{ $course->dept->is_semester == 1 ? $semester->name : $year->name }}   |  Teacher Name : {{ $course->teacher->name }}</h6>
                                    <h4>Date : {{ Carbon\Carbon::now()->format('D, d F Y') }}</h4>
                                </div>
                                <a href="{{ route('teacher.report.show', [$course->session->id,$course->course->id, $course->teacher->id]) }}" class="btn btn-info">View Report Marks</a>
                                <a href="{{ route('teacher.report.show_all', [$course->session->id,$course->course->id, $course->teacher->id]) }}" class="btn btn-warning float-right">Edit Report Marks</a>

                            </div>

                            <!-- form start -->
                            <form role="form" action="{{ route('teacher.report.store') }}" method="post">
                                @csrf
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-2 offset-3">
                                            <h4 class="float-right">Report No :</h4>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <select name="report_no" class="form-control" required>
                                                <option value="">-----Select Report No-----</option>
                                                    @if(isset($existing_reports[3]->report_no) && $existing_reports[3]->report_no == 4)
                                                        <option value="">All Report are Taken</option>
                                                    @elseif(isset($existing_reports[2]->report_no) && $existing_reports[2]->report_no == 3)
                                                        <option value="4">Report 4</option>
                                                    @elseif(isset($existing_reports[1]->report_no) && $existing_reports[1]->report_no == 2)
                                                        <option value="3">Report 3</option>
                                                    @elseif(isset($existing_reports[0]->report_no) && $existing_reports[0]->report_no == 1)
                                                        <option value="2">Report 2</option>
                                                    @else()
                                                        <option value="1">Report 1</option>
                                                    @endif

                                            </select>
                                        </div>
                                    </div>

                                    <table id="example1" class="table table-bordered table-striped text-center">
                                        <thead>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Class Roll</th>
                                            <th>Name</th>
                                            <th width="20%">Report Marks (10)</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Class Roll</th>
                                            <th>Name</th>
                                            <th>Report Marks</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>
                                            @foreach($students as $key => $student)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $student->class_roll }}</td>
                                                    <td>{{ $student->name }}</td>
                                                    <td>
                                                        <div class="form-group" style="margin-bottom: 0px">
                                                            <input type="number" name="report_marks[{{ $student->id }}]" step="0.01" class="form-control" placeholder="Enter Report Marks" required>
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
                                    <button type="submit" class="btn btn-primary float-md-right">Add Report Marks</button>
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