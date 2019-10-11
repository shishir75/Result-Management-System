@extends('layouts.backend.app')

@section('title', 'Personal Details Result')

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
                            <li class="breadcrumb-item active">Personal Details Result</li>
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
                                    {{ strtoupper('Personal Details Result of '. $student->name ) }}
                                    @if(isset($check_approval) && $check_approval->status == 1)
                                        <a target="_blank" href="{{ route('exam_controller.marks.marks_sheet_download', [$student->dept->slug, $session->id, $year_semester_id, $student->exam_roll]) }}" class="btn btn-sm btn-info text-white ml-5 float-right">Download PDF</a>
                                        <span class="btn btn-sm btn-success float-right">Approved</span>
                                    @else
                                        <span class="btn btn-sm btn-danger float-right">Not Approved</span>
                                    @endif

                                    <span class="ml-5">SESSION : {{ $session->name }}</span>
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
                                        <th>Credit Hour</th>
                                        <th>Letter Grade</th>
                                        <th>Grade Point</th>
                                        <th>GPA</th>
                                        <th>Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($courses as $key => $course)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $course->course_code }}</td>
                                                <td>{{ $course->course_title }}</td>
                                                <td>{{ number_format($course->credit_hour, 1) }}</td>


                                                @php
                                                    $marks = \App\Models\IncourseMark::where('dept_id', $course->dept_id)->where('session_id', $session->id)->where('course_id', $course->id)->where('exam_roll', $student->exam_roll)->first();
                                                    if (isset($marks))
                                                    {
                                                        $gpa = $marks->grade_point / $course->credit_hour;
                                                        $latter_grade = latter_grade($gpa);

                                                        if ($latter_grade == 'F')
                                                        {
                                                            $remarks = 'FAIL';
                                                        } else {
                                                            $remarks = 'PASS';
                                                        }
                                                    }
                                                @endphp

                                                <td>{{ $latter_grade }}</td>
                                                <td>{{ number_format($gpa, 2) }}</td>
                                                @if($key == 0)
                                                    <td rowspan="{{ count($courses) }}" style="padding-top: 170px">{{ $cgpa }}</td>
                                                    <td rowspan="{{ count($courses) }}" style="padding-top: 170px">{{ $remarks }}</td>
                                                @endif

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
