@extends('layouts.backend.app')

@section('title', 'Update Third Examiner Marks')

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
                            <li class="breadcrumb-item active">Update Third Examiner Marks</li>
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
                                <h3 class="card-title">Update Third Examiner Marks</h3>
                            </div>
                            <!-- /.card-header -->

                            <div class="card-body">
                                <div class="col-6 offset-3 text-center">
                                    <h3>Dept : {{ $course->dept->name }}</h3>
                                    <h5>Session : {{ $session->name }}  | Subject : {{ $course->course_code }} - {{ $course->course_title }}</h5>
                                    <h5>Teacher : {{ Auth::user()->name }}</h5>
                                </div>
                            </div>

                            <!-- form start -->
                            <form role="form" action="{{ route('teacher.third-examiner.update', [$session->id, $course->id, $student->exam_roll]) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped text-center">
                                        <thead>
                                        <tr>
                                            <th>Registration No</th>
                                            <th>Exam Roll</th>
                                            <th width="20%">Marks</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tbody>
                                            <tr>
                                                <td>{{ $student->reg_no }}</td>
                                                <td>{{ $student->exam_roll }}</td>
                                                <td>
                                                    <div class="form-group" style="margin-bottom: 0px">
                                                        <input type="number" name="teacher_3_marks" step="0.01" class="form-control" value="{{ $student->teacher_3_marks }}" placeholder="Enter Third Examiner Marks" required>
                                                    </div>
                                                </td>
                                            </tr>

                                        </tbody>

                                    </table>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-md-right">Update Third Examiner Marks</button>
                                </div>
                            </form>
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
