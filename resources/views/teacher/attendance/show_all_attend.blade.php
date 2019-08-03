@extends('layouts.backend.app')

@section('title', 'View All Attendance')

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
                            <li class="breadcrumb-item active">View All Attendance</li>
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
                                <h3 class="card-title">View All Attendance</h3>
                            </div>
                            <!-- /.card-header -->

                            <!-- form start -->


                                <div class="card-body">
                                    <div class="col-6 offset-3 text-center">
{{--                                        <h3>Dept : {{ \App\Models\Dept::findOrFail($attendances->course->dept_id)->name }}</h3>--}}
{{--                                        <h5>Session : {{ $attendances[0]->session->name }} | Subject : {{ $attendances->course->course_code }} - {{ $attendances->course->course_title }}</h5>--}}
{{--                                        <h5>Teacher Name : {{ $attendances[0]->teacher->name }}</h5>--}}
{{--                                        <h4>Date : {{ $attendances[0]->attend_date }}</h4>--}}
                                    </div>

                                    <table id="example1" class="table table-bordered table-striped text-center">
                                        <thead>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Class Roll</th>
                                            <th>Name</th>
                                            @foreach($dates as $date)
                                                <th>{{ $date->attend_date }}</th>
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($students_data as $key => $student_data)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $student_data->student->class_roll }}</td>
                                                <td>{{ $student_data->student->name }}</td>

                                                @foreach($dates as $date)


                                                    <td>
                                                        @if( 1 === 'P' )
                                                            <span class="badge badge-success">Present</span>
                                                        @else
                                                            <span class="badge badge-danger">Absent</span>
                                                        @endif
                                                    </td>

                                                    @endforeach

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