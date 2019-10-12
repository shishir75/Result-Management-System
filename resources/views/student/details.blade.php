@extends('layouts.backend.app')

@section('title', 'Course Details')



@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 offset-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Course Details</li>
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
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    {{ strtoupper('In-Course Marks of '.$course->course_code .' - '.$course->course_title ) }}
                                    @if($check_submit->incourse_submit == 1)
                                        <span class="badge badge-success float-right">Submitted</span>
                                    @else
                                        <span class="badge badge-info float-right">Not Submitted</span>
                                    @endif
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">

                                    @if($total_count == 0)
                                        <div class="col-2 offset-5">
                                            <h4 class="text-center bg-danger py-3 px-5 rounded">No Data Yet</h4>
                                        </div>
                                    @else
                                        <div class="col-md-3 col-sm-6 col-12">
                                        <div class="info-box
                                            @if(($present_count/$total_count)*100 >= 75)
                                                {{ 'bg-success' }}
                                            @elseif(($present_count/$total_count)*100 < 75 && ($present_count/$total_count)*100 >= 60)
                                                {{ 'bg-primary' }}
                                            @elseif(($present_count/$present_count + $absent_count)*100 < 60 && ($present_count/$total_count)*100 >= 50)
                                                {{ 'bg-info' }}
                                            @elseif(($present_count/$total_count)*100 < 50 && ($present_count/$total_count)*100 >= 40)
                                                {{ 'bg-warning' }}
                                            @else
                                                {{ 'bg-danger' }}
                                            @endif
                                                ">
                                            <span class="info-box-icon"><i class="fa fa-male"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Attendance (Present)</span>
                                                <span class="info-box-number">{{ $present_count }} of {{ $total_count }} ({{ number_format(($present_count/$total_count)*100, 2) }}%)</span>
                                                <div class="progress">
                                                    <div class="progress-bar" style="width: {{ ($present_count/$total_count)*100 }}%"></div>
                                                </div>
                                            </div>
                                            <!-- /.info-box-content -->
                                        </div>
                                        <!-- /.info-box -->
                                    </div>

                                        @foreach($tutorials as $tutorial)
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="info-box
                                                    @if($tutorial->marks >= 16)
                                                        {{ 'bg-success' }}
                                                    @elseif($tutorial->marks < 16 && $tutorial->marks >= 12)
                                                        {{ 'bg-primary' }}
                                                    @elseif($tutorial->marks < 12 && $tutorial->marks >= 10)
                                                        {{ 'bg-info' }}
                                                    @elseif($tutorial->marks < 10 && $tutorial->marks >= 6)
                                                        {{ 'bg-warning' }}
                                                    @else
                                                        {{ 'bg-danger' }}
                                                    @endif
                                                    ">
                                                    <span class="info-box-icon"><i class="fa fa-bars"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Tutorial - {{ $tutorial->tutorial_no }}</span>
                                                        <span class="info-box-number">{{ number_format($tutorial->marks, 2) }}</span>
                                                        <div class="progress">
                                                            <div class="progress-bar" style="width: {{ ($tutorial->marks/20)*100 }}%"></div>
                                                        </div>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                        @endforeach

                                        @foreach($assignments as $assignment)
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="info-box
                                                @if($assignment->marks >= 8)
                                                {{ 'bg-success' }}
                                                @elseif($assignment->marks < 8 && $assignment->marks >= 6)
                                                {{ 'bg-primary' }}
                                                @elseif($assignment->marks < 6 && $assignment->marks >= 5)
                                                {{ 'bg-info' }}
                                                @elseif($assignment->marks < 5 && $assignment->marks >= 3)
                                                {{ 'bg-warning' }}
                                                @else
                                                {{ 'bg-danger' }}
                                                @endif
                                                        ">
                                                    <span class="info-box-icon"><i class="fa fa-book"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Assignment - {{ $assignment->assignment_no }}</span>
                                                        <span class="info-box-number">{{ number_format($assignment->marks, 2) }}</span>
                                                        <div class="progress">
                                                            <div class="progress-bar" style="width: {{ ($assignment->marks/10)*100 }}%"></div>
                                                        </div>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                        @endforeach

                                        @foreach($reports as $report)
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="info-box
                                                @if($report->marks >= 8)
                                                {{ 'bg-success' }}
                                                @elseif($report->marks < 8 && $report->marks >= 6)
                                                {{ 'bg-primary' }}
                                                @elseif($report->marks < 6 && $report->marks >= 5)
                                                {{ 'bg-info' }}
                                                @elseif($report->marks < 5 && $report->marks >= 3)
                                                {{ 'bg-warning' }}
                                                @else
                                                {{ 'bg-danger' }}
                                                @endif
                                                        ">
                                                    <span class="info-box-icon"><i class="fa fa-book"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Report - {{ $report->report_no }}</span>
                                                        <span class="info-box-number">{{ number_format($report->marks, 2) }}</span>
                                                        <div class="progress">
                                                            <div class="progress-bar" style="width: {{ ($report->marks/10)*100 }}%"></div>
                                                        </div>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                        @endforeach

                                        @foreach($quizzes as $quiz)
                                            <div class="col-md-3 col-sm-6 col-12">
                                                <div class="info-box
                                                @if($quiz->marks >= 8)
                                                {{ 'bg-success' }}
                                                @elseif($quiz->marks < 8 && $quiz->marks >= 6)
                                                {{ 'bg-primary' }}
                                                @elseif($quiz->marks < 6 && $quiz->marks >= 5)
                                                {{ 'bg-info' }}
                                                @elseif($quiz->marks < 5 && $quiz->marks >= 3)
                                                {{ 'bg-warning' }}
                                                @else
                                                {{ 'bg-danger' }}
                                                @endif
                                                        ">
                                                    <span class="info-box-icon"><i class="fa fa-book"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Quiz/Viva - {{ $quiz->quiz_no }}</span>
                                                        <span class="info-box-number">{{ number_format($quiz->marks, 2) }}</span>
                                                        <div class="progress">
                                                            <div class="progress-bar" style="width: {{ ($quiz->marks/10)*100 }}%"></div>
                                                        </div>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (left) -->

                </div>
                <!-- /.row -->

                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ strtoupper('In-Course Marks of '.$course->course_code .' - '.$course->course_title ) }}</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped text-center">
                                    <thead>
                                    <tr>
                                        <th>Attendance (10)</th>
                                        <th>Tutorial (20)</th>
                                        <th>Assignment (10)</th>
                                        @if($course->is_lab === 1)
                                            <th>Report (10)</th>
                                            <th>Quiz/Viva (10)</th>
                                        @endif
                                        <th>Total ({{ $course->incourse_marks }})</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>{{ number_format($attendance, 2) }}</td>
                                            <td>{{ isset($tutorial_marks) ? number_format($tutorial_marks, 2) : '-' }}</td>
                                            <td>{{ isset($assignment_marks) ? number_format($assignment_marks, 2) : '-' }}</td>
                                            @if($course->is_lab === 1)
                                                <td>{{ isset($report_marks) ? number_format($report_marks, 2) : '-' }}</td>
                                                <td>{{ isset($quiz_marks) ? number_format($quiz_marks, 2) : '-' }}</td>
                                                <td>
                                                    {{ (isset($attendance) ? $attendance : 0) + (isset($tutorial_marks) ? $tutorial_marks : 0) + (isset($assignment_marks) ? $assignment_marks : 0) + (isset($report_marks) ? $report_marks : 0) + (isset($quiz_marks) ? $quiz_marks : 0)   }}
                                                </td>

                                            @else
                                                <td>
                                                    {{ number_format((isset($attendance) ? $attendance : 0) + (isset($tutorial_marks) ? $tutorial_marks : 0) + (isset($assignment_marks) ? $assignment_marks : 0), 2)  }}
                                                </td>
                                            @endif

                                        </tr>
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
    </div> <!-- Content Wrapper end -->
@endsection






