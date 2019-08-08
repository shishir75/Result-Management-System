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
                                <h3 class="card-title">{{ strtoupper('In-Course Marks of '.$course->course_code .' - '.$course->course_title ) }}</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-3 col-sm-6 col-12">
                                        <div class="info-box
                                            @if(($present_count/$present_count + $absent_count)*100 >= 75)
                                                {{ 'bg-success' }}
                                            @elseif(($present_count/$present_count + $absent_count)*100 < 75 && ($present_count/$present_count + $absent_count)*100 >= 60)
                                                {{ 'bg-primary' }}
                                            @elseif(($present_count/$present_count + $absent_count)*100 < 60 && ($present_count/$present_count + $absent_count)*100 >= 50)
                                                {{ 'bg-info' }}
                                            @elseif(($present_count/$present_count + $absent_count)*100 < 50 && ($present_count/$present_count + $absent_count)*100 >= 40)
                                                {{ 'bg-warning' }}
                                            @else
                                                {{ 'bg-danger' }}
                                            @endif
                                                ">
                                            <span class="info-box-icon"><i class="fa fa-male"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Attendance (Present)</span>
                                                <span class="info-box-number">{{ $present_count }} of {{ $present_count + $absent_count }} ({{ ($present_count/$present_count + $absent_count)*100 }}%)</span>
                                                <div class="progress">
                                                    <div class="progress-bar" style="width: {{ ($present_count/$present_count + $absent_count)*100 }}%"></div>
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

                                </div>




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






