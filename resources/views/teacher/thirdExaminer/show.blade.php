@extends('layouts.backend.app')

@section('title', 'Show Third Examiner Marks')

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
                            <li class="breadcrumb-item active">Show Third Examiner Marks</li>
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
                                <h3 class="card-title text-uppercase">Third Examiner Marks of {{ $course->course_code }} - {{ $course->course_title }}</h3>
                            </div>
                            <!-- /.card-header -->

                            <!-- form start -->


                            <div class="card-body">
                                <div class="col-6 offset-3 text-center mb-4">
                                    <h3>Dept : {{ $course->dept->name }}</h3>
                                    <h5>Session : {{ $session->name }} | Subject : {{ $course->course_code }} - {{ $course->course_title }}</h5>
                                    <h5>Teacher Name : {{ \Illuminate\Support\Facades\Auth::user()->name }}</h5>
                                </div>

                                <a href="{{ route('teacher.third-examiner.index') }}" class="btn btn-info mb-4">View All Courses</a>
                                <a target="_blank" href="{{ route('teacher.third-examiner.download', [$session->id, $course->id]) }}" class="btn btn-success float-right mb-4">Download PDF</a>


                                <table id="example1" class="table table-bordered table-striped text-center">
                                    <thead>
                                    <tr>
                                        <th>Serial</th>
                                        <th>Registration No</th>
                                        <th>Exam Roll</th>
                                        <th>Marks</th>
                                        <th>Update</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($third_examiner_marks as $key => $third_examiner_mark)
                                            @if($third_examiner_mark->teacher_3_marks !== null)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $third_examiner_mark->reg_no }}</td>
                                                    <td>{{ $third_examiner_mark->exam_roll }}</td>
                                                    <td>{{ number_format(round($third_examiner_mark->teacher_3_marks), 2) }}</td>
                                                    <td>
                                                        <a href="{{ route('teacher.third-examiner.edit', [$third_examiner_mark->session_id, $third_examiner_mark->course_id, $third_examiner_mark->exam_roll]) }}" class="btn btn-success">
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
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
