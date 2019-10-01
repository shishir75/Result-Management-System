@extends('layouts.backend.app')

@section('title', 'Show In-Course Marks')

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
                            <li class="breadcrumb-item active">Show In-Course Marks</li>
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
                                <h3 class="card-title text-uppercase">In-Course Marks of {{ $course_teacher->course->course_code }} - {{ $course_teacher->course->course_title }}</h3>
                            </div>
                            <!-- /.card-header -->

                            <!-- form start -->


                            <div class="card-body">
                                <div class="col-6 offset-3 text-center mb-4">
                                    <h3>Dept : {{ $course_teacher->course->dept->name }}</h3>
                                    <h5>Session : {{ $course_teacher->session->name }} | Subject : {{ $course_teacher->course->course_code }} - {{ $course_teacher->course->course_title }}</h5>
                                    <h5>Teacher Name : {{ $course_teacher->teacher->name }}</h5>
                                </div>

                                <a href="{{ route('teacher.course.index') }}" class="btn btn-info mb-4">View All Courses</a>
                                <a target="_blank" href="{{ route('teacher.marks.download', $course_teacher->id) }}" class="btn btn-success float-right mb-4">Download PDF</a>


                                <table id="example1" class="table table-bordered table-striped text-center">
                                    <thead>
                                    <tr>
                                        <th>Serial</th>
                                        <th>Class Roll</th>
                                        <th>Name</th>
                                        <th>Attendance (10)</th>
                                        <th>Tutorial (20)</th>
                                        <th>Assignment (10)</th>
                                        @if($course_teacher->course->is_lab == 1)
                                            <th>Report (10)</th>
                                            <th>Quiz (10)</th>
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
