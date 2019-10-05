@extends('layouts.backend.app')

@section('title', 'Add Third Examiner Marks')

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
                            <li class="breadcrumb-item active">Add Third Examiner Marks</li>
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
                                <h3 class="card-title">Add Third Examiner Marks</h3>
                            </div>
                            <!-- /.card-header -->

                            <div class="card-body">
                                <div class="col-6 offset-3 text-center">
                                    <h3>Dept : {{ $course->dept->name }}</h3>
                                    <h5>Session : {{ $session->name }}  | Subject : {{ $course->course_code }} - {{ $course->course_title }}</h5>
                                    <h5>Teacher : {{ Auth::user()->name }}</h5>
                                </div>
                            </div>

                            @if(count($students) >= 1)
                                <!-- form start -->
                                <form role="form" action="{{ route('teacher.third-examiner.store', [$session->id, $course->id]) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped text-center">
                                        <thead>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Registration No</th>
                                            <th>Exam Roll</th>
                                            <th width="20%">Marks</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Registration No</th>
                                            <th>Exam Roll</th>
                                            <th width="20%">Marks</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>

                                            @php
                                                $i = 0;
                                            @endphp

                                            @foreach($students as $key => $student)
                                                @if($student->teacher_1_marks - $student->teacher_2_marks >= 12 | $student->teacher_2_marks - $student->teacher_1_marks >= 12)
                                                    @php
                                                        $i++;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td>{{ $student->reg_no }}</td>
                                                        <td>{{ $student->exam_roll }}</td>
                                                        <td>
                                                            <div class="form-group" style="margin-bottom: 0px">
                                                                <input type="number" name="teacher_3_marks[{{ $student->exam_roll }}]" step="0.01" class="form-control" placeholder="Enter Third Examiner Marks" required>
                                                            </div>
                                                        </td>

                                                    </tr>
                                                @else
                                                    @continue
                                                @endif
                                            @endforeach

                                        </tbody>

                                    </table>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-md-right">Add Third Examiner Marks</button>
                                </div>
                            </form>
                            @else
                                <h4 class="text-center text-danger my-5">No Student Found</h4>
                            @endif
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
