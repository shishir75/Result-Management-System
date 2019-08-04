@extends('layouts.backend.app')

@section('title', 'Add Tutorial Marks')

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
                            <li class="breadcrumb-item active">Add Tutorial Marks</li>
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
                                <h3 class="card-title">Add Tutorial Marks</h3>
                            </div>
                            <!-- /.card-header -->

                            <div class="card-body">
                                <div class="col-6 offset-3 text-center">
                                    <h3>Dept : {{ $course->dept->name }}</h3>
                                    <h5>Session : {{ $course->session->name }} | Subject : {{ $course->course->course_code }} - {{ $course->course->course_title }}</h5>
                                    <h6>{{ $course->dept->is_semester == 1 ? 'Semester' : 'Year' }} : {{ $course->dept->is_semester == 1 ? $semester->name : $year->name }}   |  Teacher Name : {{ $course->teacher->name }}</h6>
                                    <h4>Date : {{ Carbon\Carbon::now()->format('D, d F Y') }}</h4>
                                </div>
                                <a href="{{ route('teacher.tutorial.show', [$course->session->id,$course->course->id, $course->teacher->id]) }}" class="btn btn-info">View Tutorial Marks</a>
                                <a href="{{ route('teacher.tutorial.show_all', [$course->session->id,$course->course->id, $course->teacher->id]) }}" class="btn btn-warning float-right">Edit Tutoral Marks</a>

                            </div>

                            <!-- form start -->
                            <form role="form" action="{{ route('teacher.tutorial.store') }}" method="post">
                                @csrf
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-2 offset-3">
                                            <h4 class="float-right">Tutorial No :</h4>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <select name="tutorial_no" class="form-control" required>
                                                <option value="">-----Select Tutorial No-----</option>
                                                    @if(isset($existing_tutorials[3]->tutorial_no) && $existing_tutorials[3]->tutorial_no == 4)
                                                        <option value="">All Tutorials are Taken</option>
                                                    @elseif(isset($existing_tutorials[2]->tutorial_no) && $existing_tutorials[2]->tutorial_no == 3)
                                                        <option value="4">Tutorial 4</option>
                                                    @elseif(isset($existing_tutorials[1]->tutorial_no) && $existing_tutorials[1]->tutorial_no == 2)
                                                        <option value="3">Tutorial 3</option>
                                                    @elseif(isset($existing_tutorials[0]->tutorial_no) && $existing_tutorials[0]->tutorial_no == 1)
                                                        <option value="2">Tutorial 2</option>
                                                    @else()
                                                        <option value="1">Tutorial 1</option>
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
                                            <th width="20%">Tutorial Marks</th>
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
                                                            <input type="number" name="tutorial_marks[{{ $student->id }}]" step="0.01" class="form-control" placeholder="Enter Tutorial Marks" required>
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
                                    <button type="submit" class="btn btn-primary float-md-right">Add Tutorial Marks</button>
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