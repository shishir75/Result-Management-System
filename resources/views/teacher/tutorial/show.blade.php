@extends('layouts.backend.app')

@section('title', 'Show Tutorial Marks')

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
                            <li class="breadcrumb-item active">Show Tutorial Marks</li>
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
                                <h3 class="card-title">Show Tutorial Marks</h3>
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
                                    <a href="{{ route('teacher.tutorial.show_all', [$students_data[0]->session->id,$students_data[0]->course->id, $students_data[0]->teacher->id]) }}" class="btn btn-warning float-right mb-4">Edit Tutoral Marks</a>
                                @endif

                                <table id="example1" class="table table-bordered table-striped text-center">
                                    <thead>
                                    <tr>
                                        <th>Serial</th>
                                        <th>Class Roll</th>
                                        <th>Name</th>
                                        @foreach($tutorial_nos as $tutorial_no)
                                            <th>Tutorial - {{ $tutorial_no->tutorial_no }}</th>
                                        @endforeach
                                        <th>Best Two</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($students_data as $key => $student_data)

                                        @php
                                            $best_two = \App\Models\Tutorial::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->where('student_id',$student_data->student_id )->orderBy('marks', 'desc')->take(2)->get();

                                            if (count($best_two) >= 2)
                                            {
                                                $avarage = ($best_two[0]->marks + $best_two[1]->marks)/2;
                                                $percantage = ($avarage/20)*100;
                                            }
                                        @endphp


                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $student_data->student->class_roll }}</td>
                                            <td>{{ $student_data->student->name }}</td>
                                            @foreach($tutorial_nos as $tutorial_no)
                                                @php
                                                    $tutorial_marks = \App\Models\Tutorial::where('course_id', $course_id)->where('session_id', $session_id)->where('teacher_id', $teacher_id)->where('student_id',$student_data->student_id )->where('tutorial_no', $tutorial_no->tutorial_no)->first();
                                                @endphp

                                                <td>{{ number_format($tutorial_marks->marks, 2) }}</td>
                                            @endforeach
                                            <td>
                                                @if(isset($percantage))
                                                    @if($percantage >= 80)
                                                        <span class="badge badge-success">{{ number_format($avarage, 2) }}</span> &rarr;
                                                        <span class="badge badge-success"> {{ number_format($percantage, 2) }} %</span>
                                                    @elseif($percantage < 80 && $percantage >= 60 )
                                                        <span class="badge badge-info">{{ number_format($avarage, 2) }}</span> &rarr;
                                                        <span class="badge badge-info"> {{ number_format($percantage, 2) }} %</span>
                                                    @elseif($percantage < 60 && $percantage >= 40)
                                                        <span class="badge badge-warning">{{ number_format($avarage, 2) }}</span> &rarr;
                                                        <span class="badge badge-warning"> {{ number_format($percantage, 2) }} %</span>
                                                    @else
                                                        <span class="badge badge-danger">{{ number_format($avarage, 2) }}</span> &rarr;
                                                        <span class="badge badge-danger"> {{ number_format($percantage, 2) }} %</span>
                                                    @endif

                                                @else
                                                    <span class="badge badge-warning">Not Enough Data</span>
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
