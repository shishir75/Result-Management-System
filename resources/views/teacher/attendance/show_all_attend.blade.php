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
                                    <div class="col-6 offset-3 text-center my-4">
                                        <h3>Dept : {{ \App\Models\Dept::findOrFail($students_data[0]->course->dept_id)->name }}</h3>
                                        <h5>Session : {{ $students_data[0]->session->name }} | Subject : {{ $students_data[0]->course->course_code }} - {{ $students_data[0]->course->course_title }}</h5>
                                        <h5>Teacher Name : {{ $students_data[0]->teacher->name }}</h5>
                                    </div>

                                    @if($check_submit->incourse_submit == 0)
                                        <a href="{{ route('teacher.attendance.show_all',[$students_data[0]->session->id,$students_data[0]->course->id, $students_data[0]->teacher->id] ) }}" class="btn btn-info float-right mb-4">View By Date</a>
                                    @endif

                                    <table id="example1" class="table table-bordered table-striped text-center">
                                        <thead>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Class Roll</th>
                                            <th>Name</th>
                                            @foreach($dates as $date)
                                                <th>{{ $date->attend_date }}</th>
                                            @endforeach
                                            <th>Percentage</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($students_data as $key => $student_data)
                                            @php
                                                $present_count = \App\Models\Attendance::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->where('student_id',$student_data->student_id )->where('attend', 'P')->count();
                                                $absent_count = \App\Models\Attendance::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->where('student_id',$student_data->student_id )->where('attend', 'A')->count();
                                                $percentage = $present_count/($present_count + $absent_count) *100;
                                            @endphp

                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $student_data->student->class_roll }}</td>
                                                <td>{{ $student_data->student->name }}</td>
                                                @foreach($dates as $date)
                                                    @php
                                                        $attendance = \App\Models\Attendance::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->where('student_id',$student_data->student_id )->where('attend_date', $date->attend_date)->first();
                                                    @endphp
                                                    <td>
                                                        @if( $attendance->attend === 'P' )
                                                            <span class="badge badge-success">Present</span>
                                                        @else
                                                            <span class="badge badge-danger">Absent</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td>
                                                    @if($percentage >= 75)
                                                        <span class="badge badge-success">{{ number_format($percentage, 2) }} %</span>
                                                    @elseif($percentage >=50 && $percentage < 75)
                                                        <span class="badge badge-warning">{{ number_format($percentage, 2) }} %</span>
                                                    @else
                                                        <span class="badge badge-danger">{{ number_format($percentage, 2) }} %</span>
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
