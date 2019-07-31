@extends('layouts.backend.app')

@section('title', 'Add Course Teacher')

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
                            <li class="breadcrumb-item active">Course Teacher</li>
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
                                <h3 class="card-title">Add Course Teacher</h3>
                            </div>
                            <!-- /.card-header -->

                            <!-- form start -->
                            <form role="form" action="{{ route('dept_office.teacher-course.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Session</label>
                                                <select name="session_id" class="form-control" required>
                                                    <option value="" selected disabled>Select Session</option>
                                                    @foreach($sessions as $session)
                                                        <option value="{{ $session->id }}">{{ $session->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @if($dept->is_semester == 1)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Semester</label>
                                                    <select name="code" class="form-control dynamic" required>
                                                        <option value="" selected disabled>Select Semester</option>
                                                        @foreach($semesters as $semester)
                                                            <option value="{{ $semester->code }}">{{ $semester->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Year</label>
                                                    <select name="code" class="form-control dynamic" id="code" data-dependent="course_id" required>
                                                        <option value="" selected disabled>Select Year</option>
                                                        @foreach($years as $year)
                                                            <option value="{{ $year->code }}">{{ $year->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Course Name</label>
                                                <select name="course_id" class="form-control dynamic" id="course_id" required>
                                                    <option value="" selected disabled>Select Course Name</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Teacher</label>
                                                <select name="teacher_id" class="form-control" required>
                                                    <option value="" selected disabled>Select Teacher</option>
                                                    @foreach($teachers as $teacher)
                                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{ csrf_field() }}

                                    </div>

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-md-right">Add Course Teacher</button>
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