@extends('layouts.backend.app')

@section('title', 'Update Report')

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
                            <li class="breadcrumb-item active">Update Report</li>
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
                                <h3 class="card-title">Update Report Marks</h3>
                            </div>
                            <!-- /.card-header -->

                            <div class="col-6 offset-3 text-center mt-4">
                                <h3>Dept : {{ \App\Models\Dept::findOrFail($reports[0]->course->dept_id)->name }}</h3>
                                <h5>Session : {{ $reports[0]->session->name }} | Subject : {{ $reports[0]->course->course_code }} - {{ $reports[0]->course->course_title }}</h5>
                                <h5>Teacher Name : {{ $reports[0]->teacher->name }}</h5>
                                <h4>Report No : {{ $reports[0]->report_no }}</h4>
                            </div>

                            <!-- form start -->
                            <form role="form" action="{{ route('teacher.report.update_by_report_no', [$reports[0]->session->id,$reports[0]->course->id,$reports[0]->teacher->id, $reports[0]->report_no ]) }}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped text-center">
                                        <thead>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Class Roll</th>
                                            <th>Name</th>
                                            <th width="20%">Marks</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>Serial</th>
                                            <th>Class Roll</th>
                                            <th>Name</th>
                                            <th>Marks</th>
                                        </tr>
                                        </tfoot>
                                        <tbody>
                                        @foreach($reports as $key => $report)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $report->student->class_roll }}</td>
                                                <td>{{ $report->student->name }}</td>
                                                <td>
                                                    <div class="form-group" style="margin-bottom: 0px">
                                                        <input type="number" name="report_marks[{{ $report->student->id }}]" value="{{ $report->marks }}" step="0.01" class="form-control" placeholder="Enter Report Marks" required>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach


                                        </tbody>

                                    </table>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-md-right">Update Report Marks</button>
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