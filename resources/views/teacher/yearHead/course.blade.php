@extends('layouts.backend.app')

@section('title', 'Course')

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
                            <li class="breadcrumb-item active">Course</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>


        @php
            foreach($courses as $course)
            {
                $code = explode('-', $course->course_code);
                $year_id = substr($code[1], 0, 1);

                if($year_id == $year->code)
                {
                    $year_id_final = $year_id;
                    break;
                } else {
                    continue;
                }
            }

        @endphp

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
                                    {{ strtoupper('Course list of '. $year->name ) }}
                                    <a href="{{ route('teacher.year-head.result', [$session->id, $year_id_final, 1]) }}" class="btn btn-sm btn-info text-white ml-5">View 1st Semester Result</a>
                                    <a href="{{ route('teacher.year-head.result', [$session->id, $year_id_final, 2]) }}" class="btn btn-sm btn-info text-white ml-5">View 2nd Semester Result</a>
                                    <span class="float-right">SESSION : {{ $session->name }}</span>
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped text-center">
                                    <thead>
                                    <tr>
                                        <th>Serial</th>
                                        <th>Course Title</th>
                                        <th>{{ $courses[0]->dept->is_semester == 1 ? 'Semester' : 'Year' }}</th>
                                        <th>Credit Hour</th>
                                        <th>Lab / Viva</th>
                                        <th>In-course Marks</th>
                                        <th>Final Marks</th>
                                        <th>In-Course Status</th>
                                        <th>Year Head Status</th>
                                        <th>Marks</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @php
                                        $i = 0;
                                    @endphp

                                    @foreach($courses as $key => $course)

                                        @php
                                            $code = explode('-', $course->course_code);
                                            $year_id = substr($code[1], 0, 1);
                                            $semester_id = substr($code[1], 1, 1);
                                        @endphp

                                        @if($year_id == $year->code)
                                            @php
                                                $i++;
                                            @endphp

                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $course->course_code .' - '. $course->course_title }}</td>
                                                <td>{{ $courses[0]->dept->is_semester == 1 ? $year_id .' - '. $semester_id : $year_id }}</td>

                                                <td>{{ number_format($course->credit_hour, 1) }}</td>
                                                <td>
                                                    @if($course->is_lab == true)
                                                        <i class="fa fa-check text-success" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-times text-danger" aria-hidden="true"></i>
                                                    @endif
                                                </td>
                                                <td>{{ $course->incourse_marks }}</td>
                                                <td>{{ $course->final_marks  }}</td>

                                                @php
                                                    $course_teacher_approval = \App\Models\CourseTeacher::where('dept_id', $course->dept->id)->where('session_id', $session->id)->where('course_id', $course->id)->first();
                                                    $second_examiner_approval = \App\Models\External::where('dept_id', $course->dept->id)->where('session_id', $session->id)->where('course_id', $course->id)->first();
                                                @endphp

                                                <td>
                                                    @if(isset($course_teacher_approval) && $course_teacher_approval->status == 1 && isset($second_examiner_approval) && $second_examiner_approval->external_1_status == 1)
                                                        <span class="badge badge-success">Submitted</span>
                                                    @else
                                                        <span class="badge badge-danger">Not Submitted</span>
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
                                                    <a href="{{ route('teacher.year-head.marks', [$session->id, $course->id]) }}" class="btn btn-info">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                    </a>
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
        function deleteItem(id) {
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
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    document.getElementById('delete-form-'+id).submit();
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons(
                        'Cancelled',
                        'Your data is safe :)',
                        'error'
                    )
                }
            })
        }
    </script>



@endpush
