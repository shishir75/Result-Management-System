@extends('layouts.backend.app')

@section('title', 'Add Assignment Marks')

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
                            <li class="breadcrumb-item active">Add Assignment Marks</li>
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
                                <h3 class="card-title">Add Assignment Marks</h3>
                            </div>
                            <!-- /.card-header -->

                            <div class="card-body">
                                <div class="col-6 offset-3 text-center">
                                    <h3>Dept : {{ $course->dept->name }}</h3>
                                    <h5>Session : {{ $course->session->name }} | Subject : {{ $course->course->course_code }} - {{ $course->course->course_title }}</h5>
                                    <h6>{{ $course->dept->is_semester == 1 ? 'Semester' : 'Year' }} : {{ $course->dept->is_semester == 1 ? $semester->name : $year->name }}   |  Teacher Name : {{ $course->teacher->name }}</h6>
                                    <h4>Date : {{ Carbon\Carbon::now()->format('D, d F Y') }}</h4>
                                </div>
                                <a href="{{ route('teacher.assignment.show', [$course->session->id,$course->course->id, $course->teacher->id]) }}" class="btn btn-info">View Assignment Marks</a>
                                <a href="{{ route('teacher.assignment.show_all', [$course->session->id,$course->course->id, $course->teacher->id]) }}" class="btn btn-warning float-right">Edit Assignment Marks</a>

                            </div>

                            <!-- form start -->
                            <form role="form" action="{{ route('teacher.assignment.store') }}" method="post">
                                @csrf
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-2 offset-3">
                                            <h4 class="float-right">Assignment No :</h4>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <select name="assignment_no" class="form-control" required>
                                                <option value="">-----Select Assignment No-----</option>
                                                    @if(isset($existing_assignments[3]->assignment_no) && $existing_assignments[3]->assignment_no == 4)
                                                        <option value="">All Assignment are Taken</option>
                                                    @elseif(isset($existing_assignments[2]->assignment_no) && $existing_assignments[2]->assignment_no == 3)
                                                        <option value="4">Assignment 4</option>
                                                    @elseif(isset($existing_assignments[1]->assignment_no) && $existing_assignments[1]->assignment_no == 2)
                                                        <option value="3">Assignment 3</option>
                                                    @elseif(isset($existing_assignments[0]->assignment_no) && $existing_assignments[0]->assignment_no == 1)
                                                        <option value="2">Assignment 2</option>
                                                    @else()
                                                        <option value="1">Assignment 1</option>
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
                                            <th width="20%">Assignment Marks (10)</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Class Roll</th>
                                            <th>Name</th>
                                            <th>Tutorial Marks</th>
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
                                                            <input type="number" name="assignment_marks[{{ $student->id }}]" step="0.01" class="form-control" placeholder="Enter Assignment Marks" required>
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
                                    <button type="submit" class="btn btn-primary float-md-right">Add Assignment Marks</button>
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