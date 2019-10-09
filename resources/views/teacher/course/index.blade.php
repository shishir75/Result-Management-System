@extends('layouts.backend.app')

@section('title', 'Courses')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/backend/plugins/datatables/dataTables.bootstrap4.css') }}">
@endpush

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 offset-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Courses</li>
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
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ strtoupper('Course list of '.$teacher->name ) }}</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped text-center">
                                    <thead>
                                    <tr>
                                        <th>Serial</th>
                                        <th>Session</th>
                                        <th>Course Title</th>
                                        <th>{{ $teacher->dept->is_semester == 1 ? 'Semester' : 'Year' }}</th>
                                        <th>Credit Hour</th>
                                        <th>Lab / Viva</th>
                                        <th>Attend</th>
                                        <th>Tutorial</th>
                                        <th>Assignment</th>
                                        <th>Report</th>
                                        <th>Quiz/Viva</th>
                                        <th>Marks</th>
                                        <th>Submit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($course_teachers as $key => $course_teacher)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $course_teacher->session->name }}</td>
                                            <td>{{ $course_teacher->course->course_code .' - '. $course_teacher->course->course_title }}</td>
                                            <td>{{ $course_teacher->code }}</td>

                                            <td>{{ number_format($course_teacher->course->credit_hour, 1) }}</td>
                                            <td>
                                                @if($course_teacher->course->is_lab == true)
                                                    <i class="fa fa-check text-success" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-times text-danger" aria-hidden="true"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if($course_teacher->incourse_submit == 0)
                                                    <a href="{{ route('teacher.attendance.create', $course_teacher->id) }}" class="btn btn-success">
                                                        <i class="fa fa-male" aria-hidden="true"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('teacher.attendance.show_all_attend', [$course_teacher->session_id, $course_teacher->course_id, $course_teacher->teacher_id]) }}" class="btn btn-outline-success">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                @if($course_teacher->incourse_submit == 0)
                                                    <a href="{{ route('teacher.tutorial.create', $course_teacher->id) }}" class="btn btn-info">
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('teacher.tutorial.show', [$course_teacher->session_id, $course_teacher->course_id, $course_teacher->teacher_id]) }}" class="btn btn-outline-info">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                @if($course_teacher->incourse_submit == 0)
                                                    <a href="{{ route('teacher.assignment.create', $course_teacher->id) }}" class="btn btn-primary">
                                                        <i class="fa fa-bars" aria-hidden="true"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('teacher.assignment.show', [$course_teacher->session_id, $course_teacher->course_id, $course_teacher->teacher_id]) }}" class="btn btn-outline-primary">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                @if($course_teacher->course->is_lab == 1)
                                                    @if($course_teacher->incourse_submit == 0)
                                                        <a href="{{ route('teacher.report.create', $course_teacher->id) }}" class="btn btn-success">
                                                            <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('teacher.report.show', [$course_teacher->session_id, $course_teacher->course_id, $course_teacher->teacher_id]) }}" class="btn btn-outline-success">
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                @else
                                                    <i class="fa fa-times-circle btn btn-outline-danger" aria-hidden="true"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if($course_teacher->course->is_lab == 1)
                                                    @if($course_teacher->incourse_submit == 0)
                                                        <a href="{{ route('teacher.quiz.create', $course_teacher->id) }}" class="btn btn-info">
                                                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('teacher.quiz.show', [$course_teacher->session_id, $course_teacher->course_id, $course_teacher->teacher_id]) }}" class="btn btn-outline-info">
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                        </a>
                                                    @endif
                                                @else
                                                    <i class="fa fa-times-circle btn btn-outline-danger" aria-hidden="true"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('teacher.marks.index', $course_teacher->id) }}" class="btn btn-success">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                            <td>
                                                @if($course_teacher->incourse_submit == 0)
                                                    <button class="btn btn-warning" type="button" onclick="approvedItem({{ $course_teacher->course->id }})">
                                                        <i class="fa fa-question" aria-hidden="true"></i>
                                                    </button>
                                                    <form id="approved-form-{{ $course_teacher->course->id }}" action="{{ route('teacher.in-course.approved', [$course_teacher->course->id]) }}" method="post"
                                                          style="display:none;">
                                                        @csrf
                                                        @method('PUT')
                                                    </form>
                                                @else
                                                    <button class="btn btn-outline-success" type="button">
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                    </button>
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
    </div> <!-- Content Wrapper end -->
@endsection




@push('js')

    <!-- DataTables -->
    <script src="{{ asset('assets/backend/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/backend/plugins/datatables/dataTables.bootstrap4.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('assets/backend/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('assets/backend/plugins/fastclick/fastclick.js') }}"></script>

    <!-- Sweet Alert Js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.29.1/dist/sweetalert2.all.min.js"></script>


    <script>
        $(function () {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });
        });
    </script>


    <script type="text/javascript">
        function approvedItem(id) {
            const swalWithBootstrapButtons = swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
            })

            swalWithBootstrapButtons({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Submit it!',
                cancelButtonText: 'No, Cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    document.getElementById('approved-form-'+id).submit();
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons(
                        'Cancelled',
                        'Your data is not Submitted :)',
                        'error'
                    )
                }
            })
        }
    </script>



@endpush
