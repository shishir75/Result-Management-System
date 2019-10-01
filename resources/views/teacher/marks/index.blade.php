@extends('layouts.backend.app')

@section('title', 'Show Marks')

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
                            <li class="breadcrumb-item active">Show Marks</li>
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
                                <h3 class="card-title">Show Marks</h3>
                            </div>
                            <!-- /.card-header -->

                            <!-- form start -->


                            <div class="card-body">
{{--                                <div class="col-6 offset-3 text-center">--}}
{{--                                    <h3>Dept : {{ \App\Models\Dept::findOrFail($attendances[0]->course->dept_id)->name }}</h3>--}}
{{--                                    <h5>Session : {{ $attendances[0]->session->name }} | Subject : {{ $attendances[0]->course->course_code }} - {{ $attendances[0]->course->course_title }}</h5>--}}
{{--                                    <h5>Teacher Name : {{ $attendances[0]->teacher->name }}</h5>--}}
{{--                                    <h4>Date : {{ $attendances[0]->attend_date }}</h4>--}}
{{--                                </div>--}}

{{--                                <a href="{{ route('teacher.attendance.show_all_attend',[$attendances[0]->session->id,$attendances[0]->course->id, $attendances[0]->teacher->id] ) }}" class="btn btn-success mb-4">View All Attendance</a>--}}
{{--                                <a href="{{ route('teacher.attendance.show_all',[$attendances[0]->session->id,$attendances[0]->course->id, $attendances[0]->teacher->id] ) }}" class="btn btn-info float-right mb-4">View By Date</a>--}}


                                <table id="example1" class="table table-bordered table-striped text-center">
                                    <thead>
                                    <tr>
                                        <th>Serial</th>
                                        <th>Class Roll</th>
                                        <th>Name</th>
                                        <th>Attendance</th>
                                        <th>Tutorial</th>
                                        <th>Assignment</th>
                                        @if($course_teacher->course->is_lab == 1)
                                            <th>Report</th>
                                            <th>Quiz</th>
                                        @endif
                                        <th>Total ({{ $course_teacher->course->is_lab == 1 ? 60 : 40 }})</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($students as $key => $student)

                                        @php
                                            $attendance_count = App\Models\Attendance::where('course_id', $course_teacher->course_id)->where('session_id', $course_teacher->session_id)->where('teacher_id', $course_teacher->teacher_id)->where('student_id', $student->id)->count();
                                            $present_count =App\Models\Attendance::where('course_id', $course_teacher->course_id)->where('session_id', $course_teacher->session_id)->where('teacher_id', $course_teacher->teacher_id)->where('student_id', $student->id)->where('attend', 'P')->count();
                                            if ($attendance_count > 0)
                                            {
                                                $attendance = ($present_count/$attendance_count)*10;
                                            } else
                                            {
                                                $attendance = 0;
                                            }


                                            $tutorials = App\Models\Tutorial::where('course_id', $course_teacher->course_id)->where('session_id', $course_teacher->session_id)->where('student_id', $student->id)->orderBy('tutorial_no', 'asc')->get();
                                            $tutorials_best_two = App\Models\Tutorial::where('course_id', $course_teacher->course_id)->where('session_id', $course_teacher->session_id)->where('student_id', $student->id)->orderBy('marks', 'desc')->take(2)->get();
                                            $assignments = App\Models\Assignment::where('course_id', $course_teacher->course_id)->where('session_id', $course_teacher->session_id)->where('student_id', $student->id)->orderBy('assignment_no', 'asc')->get();
                                            $assignments_best_two = App\Models\Assignment::where('course_id', $course_teacher->course_id)->where('session_id', $course_teacher->session_id)->where('student_id', $student->id)->orderBy('marks', 'desc')->take(2)->get();
                                            $reports = App\Models\Report::where('course_id', $course_teacher->course_id)->where('session_id', $course_teacher->session_id)->where('student_id', $student->id)->orderBy('report_no', 'asc')->get();
                                            $reports_best_two = App\Models\Report::where('course_id', $course_teacher->course_id)->where('session_id', $course_teacher->session_id)->where('student_id', $student->id)->orderBy('marks', 'desc')->take(2)->get();
                                            $quizzes = App\Models\Quiz::where('course_id', $course_teacher->course_id)->where('session_id', $course_teacher->session_id)->where('student_id', $student->id)->orderBy('quiz_no', 'asc')->get();
                                            $quizzes_best_two = App\Models\Quiz::where('course_id', $course_teacher->course_id)->where('session_id', $course_teacher->session_id)->where('student_id', $student->id)->orderBy('marks', 'desc')->take(2)->get();

                                            if (count($tutorials_best_two) >= 2)
                                            {
                                                $tutorial_marks = ($tutorials_best_two[0]->marks + $tutorials_best_two[1]->marks )/2;
                                                if (!isset($tutorial_marks))
                                                {
                                                    $tutorial_marks = 0;
                                                }

                                            } else
                                            {
                                                $tutorial_marks = 0;
                                            }

                                            if (count($assignments_best_two) >= 2)
                                            {
                                                $assignment_marks = ($assignments_best_two[0]->marks + $assignments_best_two[1]->marks )/2;
                                                if (!isset($assignment_marks))
                                                {
                                                    $assignment_marks = 0;
                                                }
                                            } else
                                            {
                                                $assignment_marks = 0;
                                            }

                                            if (count($reports_best_two) >= 2)
                                            {
                                                $report_marks = ($reports_best_two[0]->marks + $reports_best_two[1]->marks )/2;
                                                if (!isset($report_marks))
                                                {
                                                    $report_marks = 0;
                                                }
                                            } else
                                            {
                                                $report_marks = 0;
                                            }

                                            if (count($quizzes_best_two) >= 2)
                                            {
                                                $quiz_marks = ($quizzes_best_two[0]->marks + $quizzes_best_two[1]->marks )/2;
                                                if (!isset($quiz_marks))
                                                {
                                                    $quiz_marks = 0;
                                                }
                                            } else
                                            {
                                                $quiz_marks = 0;
                                            }

                                        @endphp

                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $student->class_roll }}</td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ number_format($attendance, 2) }}</td>
                                            <td>{!!  $tutorial_marks == 0 ? "<span class='badge badge-warning'>Not Enough Data</span>" : number_format($tutorial_marks, 2)  !!}</td>
                                            <td>{!! $assignment_marks == 0 ? "<span class='badge badge-warning'>Not Enough Data</span>" : number_format($assignment_marks, 2)  !!}</td>
                                            @if($course_teacher->course->is_lab == 1)
                                                <td>{!! $report_marks == 0 ? "<span class='badge badge-warning'>Not Enough Data</span>" : number_format($report_marks, 2)  !!}</td>
                                                <td>{!! $quiz_marks == 0 ? "<span class='badge badge-warning'>Not Enough Data</span>" : number_format($quiz_marks, 2)  !!}</td>
                                            @endif
                                            <td>
                                                @if($course_teacher->course->is_lab == 1)
                                                    @if($tutorial_marks == 0 | $assignment_marks == 0 | $report_marks == 0 | $quiz_marks == 0)
                                                        <span class="badge badge-warning">Not Enough Data</span>
                                                    @else
                                                        {{ round($attendance + $tutorial_marks + $assignment_marks + $report_marks + $quiz_marks, 2) }}
                                                    @endif
                                                @else
                                                    @if($tutorial_marks == 0 | $assignment_marks == 0)
                                                        <span class="badge badge-warning">Not Enough Data</span>
                                                    @else
                                                        {{ round($attendance + $tutorial_marks + $assignment_marks, 2) }}
                                                    @endif
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
