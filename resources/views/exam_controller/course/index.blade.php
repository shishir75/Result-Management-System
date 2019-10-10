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
                                <h3 class="card-title">
                                    COURSE LIST OF  {{ strtoupper($courses[0]->dept->is_semester == 1 ? $semester->name : '' ) }} OF SESSION {{ $session->name }}
                                    <span class="ml-5">DEPT : {{ strtoupper($courses[0]->dept->name) }}</span>

                                    @if(isset($check_approval) && $check_approval->status == 1)
                                        <a href="{{ route('exam_controller.marks.result', [$dept->slug ,$session->id, $year_semester_id]) }}" class="btn btn-sm btn-info text-white ml-5 float-right">View Result</a>
                                        <a target="_blank" href="{{ route('exam_controller.marks.download', [$dept->slug ,$session->id, $year_semester_id]) }}" class="btn btn-sm btn-primary text-white ml-5 float-right">Download</a>
                                        <span class="btn btn-sm btn-outline-success float-right">Approved</span>
                                    @else
                                        <button class="btn btn-sm btn-warning text-bold float-right ml-5" type="button" onclick="approvedItem({{ $year_semester_id }})">
                                            Approve Me
                                        </button>
                                        <form id="approved-form-{{ $year_semester_id }}" action="{{ route('exam_controller.marks.approved', [$dept->slug, $session->id, $year_semester_id]) }}" method="post"
                                              style="display:none;">
                                            @csrf
                                            @method('PUT')
                                        </form>
                                    @endif

                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped text-center">
                                    <thead>
                                    <tr>
                                        <th>Serial</th>
                                        <th>Course Code</th>
                                        <th>Course Title</th>
                                        <th>Is Lab / Viva</th>
                                        <th>In-course Status</th>
                                        <th>Year Head Status</th>
                                        <th>Marks</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($courses as $key => $course)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $course->course_code  }}</td>
                                            <td>{{ $course->course_title  }}</td>
                                            <td>
                                                @if($course->is_lab == 1)
                                                    <i class="fa fa-check text-success" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-times text-danger" aria-hidden="true"></i>
                                                @endif
                                            </td>
                                            @php
                                                $course_teacher_status_check = \App\Models\CourseTeacher::where('dept_id', $course->dept->id)->where('session_id', $session->id)->where('course_id', $course->id)->where('status', 1)->get();

                                                $second_examiner_status_check = \App\Models\External::where('dept_id', $course->dept->id)->where('session_id', $session->id)->where('course_id', $course->id)->where('external_1_status', 1)->get();
                                            @endphp

                                            <td>
                                                @if(count($course_teacher_status_check) == 1 && count($second_examiner_status_check) == 1)
                                                    <span class="badge badge-info">Submitted</span>
                                                @else
                                                    <span class="badge badge-warning">Not Submitted</span>
                                                @endif
                                            </td>
                                            @php
                                                $check_approval = App\Models\YearHeadApproval::where('session_id', $session->id)->where('dept_id', $course->dept->id)->where('course_id', $course->id)->first();
                                            @endphp
                                            <td>
                                                @if(isset($check_approval) && $check_approval->approved == 1)
                                                    <span class="badge badge-success">Approved</span>
                                                @else
                                                    <span class="badge badge-danger">Not Approved</span>
                                                @endif
                                            </td>
                                            <td>

                                                @if(count($course_teacher_status_check) == 1 && count($second_examiner_status_check) == 1)
                                                    <a href="{{ route('exam_controller.marks.index', [$course->dept->slug, $session->id, $course->year_semester_id, $course->id ]) }}" class="btn btn-success">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </a>
                                                @else
                                                    <a href="#" class="btn btn-outline-danger">
                                                        <i class="fa fa-times text-danger" aria-hidden="true"></i>
                                                    </a>
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
                confirmButtonText: 'Yes, Approve!',
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
                        'Result is not Approved :)',
                        'error'
                    )
                }
            })
        }
    </script>



@endpush
