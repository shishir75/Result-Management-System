@extends('layouts.backend.app')

@section('title', 'Update Attendance')

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
                            <li class="breadcrumb-item active">Update Attendance</li>
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
                                <h3 class="card-title">Update Attendance</h3>
                            </div>
                            <!-- /.card-header -->

                            <div class="col-6 offset-3 text-center mt-4">
                                <h3>Dept : {{ \App\Models\Dept::findOrFail($attendances[0]->course->dept_id)->name }}</h3>
                                <h5>Session : {{ $attendances[0]->session->name }} | Subject : {{ $attendances[0]->course->course_code }} - {{ $attendances[0]->course->course_title }}</h5>
                                <h5>Teacher Name : {{ $attendances[0]->teacher->name }}</h5>
                                <h4>Date : {{ $attendances[0]->attend_date }}</h4>
                            </div>


                            <!-- form start -->
                            <form role="form" action="{{ route('teacher.attendance.update_by_date', [$attendances[0]->session->id,$attendances[0]->course->id,$attendances[0]->teacher->id, $attendances[0]->attend_date ]) }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="card-body">

                                    <a href="{{ route('teacher.attendance.show_all_attend',[$attendances[0]->session->id,$attendances[0]->course->id, $attendances[0]->teacher->id] ) }}" class="btn btn-success mb-4">View All Attendance</a>
                                    <a href="{{ route('teacher.attendance.show_all',[$attendances[0]->session->id,$attendances[0]->course->id, $attendances[0]->teacher->id] ) }}" class="btn btn-info float-right mb-4">View By Date</a>

                                    <table id="example1" class="table table-bordered table-striped text-center">
                                        <thead>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Class Roll</th>
                                            <th>Name</th>
                                            <th>Attendance</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Class Roll</th>
                                            <th>Name</th>
                                            <th>Attendance</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>
                                        @foreach($attendances as $key => $attendance)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $attendance->student->class_roll }}</td>
                                                <td>{{ $attendance->student->name }}</td>
                                                <td>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="p-option{{ $attendance->student->id }}"  name="attend[{{ $attendance->student->id }}]" value="P" class="custom-control-input" {{ $attendance->attend == 'P' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="p-option{{ $attendance->student->id }}">Present</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="a-option{{ $attendance->student->id }}" name="attend[{{ $attendance->student->id }}]" value="A" class="custom-control-input" {{ $attendance->attend == 'A' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="a-option{{ $attendance->student->id }}">Absent</label>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach


                                        </tbody>

                                    </table>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-md-right">Update Attendance</button>
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