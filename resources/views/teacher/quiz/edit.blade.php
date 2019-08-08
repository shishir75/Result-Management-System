@extends('layouts.backend.app')

@section('title', 'Update Quiz/Viva')

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
                            <li class="breadcrumb-item active">Update Quiz/Viva</li>
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
                                <h3 class="card-title">Update Quiz/Viva Marks</h3>
                            </div>
                            <!-- /.card-header -->

                            <div class="col-6 offset-3 text-center mt-4">
                                <h3>Dept : {{ \App\Models\Dept::findOrFail($quizzes[0]->course->dept_id)->name }}</h3>
                                <h5>Session : {{ $quizzes[0]->session->name }} | Subject : {{ $quizzes[0]->course->course_code }} - {{ $quizzes[0]->course->course_title }}</h5>
                                <h5>Teacher Name : {{ $quizzes[0]->teacher->name }}</h5>
                                <h4>Report No : {{ $quizzes[0]->quiz_no }}</h4>
                            </div>

                            <!-- form start -->
                            <form role="form" action="{{ route('teacher.quiz.update_by_quiz_no', [$quizzes[0]->session->id,$quizzes[0]->course->id,$quizzes[0]->teacher->id, $quizzes[0]->quiz_no ]) }}" method="post">
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
                                        @foreach($quizzes as $key => $quiz)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $quiz->student->class_roll }}</td>
                                                <td>{{ $quiz->student->name }}</td>
                                                <td>
                                                    <div class="form-group" style="margin-bottom: 0px">
                                                        <input type="number" name="quiz_marks[{{ $quiz->student->id }}]" value="{{ $quiz->marks }}" step="0.01" class="form-control" placeholder="Enter Quiz/Viva Marks" required>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach


                                        </tbody>

                                    </table>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-md-right">Update Quiz/Viva Marks</button>
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