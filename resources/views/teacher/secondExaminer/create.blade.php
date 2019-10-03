@extends('layouts.backend.app')

@section('title', 'Add Second Examiner Marks')

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
                            <li class="breadcrumb-item active">Add Second Examiner Marks</li>
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
                                <h3 class="card-title">Add Second Examiner Marks</h3>
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
                            <form role="form" action="{{ route('teacher.second-examiner.store', [$session->id, $course->id]) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="file">Second Examiner Marks (Excel File)</label>
                                        <input type="file" name="file" class="form-control-file" id="file" required>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <a href="{{ asset('assets/backend/file/Final Marks - format.xlsx') }}" download>Download Marks Excel Format</a>
                                    <button type="submit" class="btn btn-primary float-md-right">Add Second Examiner Marks</button>
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
