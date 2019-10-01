@extends('layouts.backend.app')

@section('title', 'Update Course External Teacher')

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
                            <li class="breadcrumb-item"><a href="{{ route('register.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Update Course External Teacher</li>
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
                                <h3 class="card-title">Update Course External Teacher</h3>
                            </div>
                            <!-- /.card-header -->

                            <!-- form start -->
                            <form role="form" action="{{ route('dept_office.external.update',$external->id ) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Session</label>
                                                <select name="session_id" class="form-control" required>
                                                    <option value="" selected disabled>Select Session</option>
                                                    @foreach($sessions as $session)
                                                        <option value="{{ $session->id }}" {{ $external->session_id == $session->id ? 'selected' : '' }}>{{ $session->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        @php

                                            if ($dept->is_semester == 1)
                                            {
                                                $code = explode('-', $external->course->course_code);

                                                $year = substr($code[1], 0, 1);
                                                $semester = substr($code[1], 1, 1);

                                                $code = $year.'-'.$semester;

                                            } else {
                                                $code = explode('-', $external->course->course_code);
                                                $year_code = substr($code[1], 0, 1);
                                            }

                                        @endphp

                                        @if($dept->is_semester == 1)
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Semester</label>
                                                    <select name="code" class="form-control dynamic" required>
                                                        <option value="" disabled>Select Semester</option>
                                                        @foreach($semesters as $semester)
                                                            <option value="{{ $semester->code }}" {{ $semester->code == $code  ? 'selected' : '' }} >{{ $semester->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Year</label>
                                                    <select name="code" class="form-control dynamic" id="code" data-dependent="course_id" required>
                                                        <option value="" selected disabled>Select Year</option>
                                                        @foreach($years as $year)
                                                            <option value="{{ $year->code }}" {{ $year == $year_code ? 'selected' : '' }}>{{ $year->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif

                                        @php
                                            if ($dept->is_semester == 1)
                                            {
                                                $data = App\Models\Semester::where('code', $code)->first();

                                            } else {
                                                $data = App\Models\Year::where('code', $year_code)->first();
                                            }

                                            $courses = App\Models\Course::where('dept_id', $dept->id)->where('year_semester_id', $data->id)->get();

                                        @endphp

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Course Name</label>
                                                <select name="course_id" class="form-control dynamic" id="course_id" required>
                                                    <option value="" selected disabled>Select Course Name</option>
                                                    @foreach($courses as $course)
                                                        <option value="{{ $course->id }}" {{ $course->id == $external->course_id ? 'selected' : '' }}>{{ $course->course_code .' - '. $course->course_title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>2nd Examiner</label>
                                                <select name="external_1" class="form-control" required>
                                                    <option value="" selected disabled>Select Teacher</option>
                                                    @foreach($teachers as $teacher)
                                                        <option value="{{ $teacher->id }}" {{ $external->external_1 == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>3rd Examiner</label>
                                                <select name="external_2" class="form-control" required>
                                                    <option value="" selected disabled>Select Teacher</option>
                                                    @foreach($teachers as $teacher)
                                                        <option value="{{ $teacher->id }}" {{ $external->external_2 == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{ csrf_field() }}

                                    </div>

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-md-right">Update Course External Teacher</button>
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

    <script>
        $(document).ready(function () {

            $('.dynamic').change(function () {

                if ($(this).val() != '')
                {
                    var value = $(this).val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('dept_office.fetch_course') }}",
                        method: "POST",
                        data: { value:value, _token:_token },
                        success: function (result) {

                            $('#course_id').html(result);
                        }
                    });
                }
            });
        });

    </script>

@endpush
