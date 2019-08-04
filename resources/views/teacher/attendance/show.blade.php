@extends('layouts.backend.app')

@section('title', 'Show Attendance')

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
                            <li class="breadcrumb-item active">Show Attendance</li>
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
                                <h3 class="card-title">Show Attendance</h3>
                            </div>
                            <!-- /.card-header -->

                            <!-- form start -->


                                <div class="card-body">
                                    <div class="col-6 offset-3 text-center">
                                        <h3>Dept : {{ \App\Models\Dept::findOrFail($attendances[0]->course->dept_id)->name }}</h3>
                                        <h5>Session : {{ $attendances[0]->session->name }} | Subject : {{ $attendances[0]->course->course_code }} - {{ $attendances[0]->course->course_title }}</h5>
                                        <h5>Teacher Name : {{ $attendances[0]->teacher->name }}</h5>
                                        <h4>Date : {{ $attendances[0]->attend_date }}</h4>
                                    </div>

                                    <a href="{{ route('teacher.attendance.show_all_attend',[$attendances[0]->session->id,$attendances[0]->course->id, $attendances[0]->teacher->id] ) }}" class="btn btn-success mb-4">View All Attendance</a>
                                    <a href="{{ route('teacher.attendance.show_all',[$attendances[0]->session->id,$attendances[0]->course->id, $attendances[0]->teacher->id] ) }}" class="btn btn-info float-right mb-4">View By Date</a>


                                    <table id="example1" class="table table-bordered table-striped text-center">
                                        <thead>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Class Roll</th>
                                            <th>Name</th>
                                            <th>Attendance</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($attendances as $key => $attendance)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $attendance->student->class_roll }}</td>
                                                <td>{{ $attendance->student->name }}</td>
                                                <td>
                                                    @if( $attendance->attend === 'P' )
                                                        <span class="badge badge-success">Present</span>
                                                    @else
                                                        <span class="badge badge-danger">Absent</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>

                                    </table>
                                </div>
                                <!-- /.card-body -->

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