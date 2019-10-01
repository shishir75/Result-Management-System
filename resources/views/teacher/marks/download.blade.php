<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>In-Course Marks of {{ $course_teacher->course->course_code }} - {{ $course_teacher->course->course_title }}</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('assets/backend/plugins/font-awesome/css/font-awesome.min.css') }}">
    <!-- IonIcons -->
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/backend/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">

    <link rel="icon" href="{{ asset('assets/backend/img/policymaker.ico') }}" type="image/x-icon" />

    @stack('css')

</head>


<body>
<!-- Main content -->
<section class="content">
    <div class="container-fluid px-5">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12 mt-4">
                <div class="col-12 text-center mb-4">
                    <h3>Dept : {{ $course_teacher->course->dept->name }}</h3>
                    <h4 class="text-uppercase">In-Course Marks</h4>
                    <h5>Session : {{ $course_teacher->session->name }} | Subject : {{ $course_teacher->course->course_code }} - {{ $course_teacher->course->course_title }}</h5>
                    <h5>Teacher Name : {{ $course_teacher->teacher->name }}</h5>
                    <h5 class="text-right">Date: {{ \Carbon\Carbon::now()->format(' F d, Y') }}</h5>
                </div>

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
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<!-- /.content-wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{ asset('https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('assets/backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE -->
<script src="{{ asset('assets/backend/js/adminlte.js') }}"></script>

<script>
    window.print();
</script>


</body>




</html>>
