@extends('layouts.backend.app')

@section('title', 'Final Marks')

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
                            <li class="breadcrumb-item active">Final Marks</li>
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
                                    {{ strtoupper('Total Marks of '. $final_marks[0]->course->course_code .' - '. $final_marks[0]->course->course_title ) }}
                                    <span class="float-right">SESSION : {{ $final_marks[0]->session->name }}</span>
                                </h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped text-center">
                                    <thead>
                                    <tr>
                                        <th>Serial</th>
                                        <th>Reg No</th>
                                        <th>Exam Roll</th>
                                        <th>In-course Marks</th>
                                        <th>Course Teacher Marks</th>
                                        <th>2nd Examiner Marks</th>
                                        <th>3rd Examiner Marks</th>
                                        <th>Final Exam Marks</th>
                                        <th>Total Marks</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($final_marks as $key => $marks)

                                            @php
                                                $incourse = \App\Models\IncourseMark::where('dept_id', $marks->dept_id)->where('session_id', $marks->session_id)->where('course_id', $marks->course_id)->where('reg_no', $marks->reg_no)->where('exam_roll', $marks->exam_roll)->first();
                                                if (isset($incourse))
                                                {
                                                    $incourse_marks = $incourse->marks;
                                                } else {
                                                    $incourse_marks = 0;
                                                }

                                            @endphp

                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $marks->reg_no }}</td>
                                                <td>{{ $marks->exam_roll }}</td>
                                                <td>{{ number_format($incourse_marks, 1) }}</td>
                                                <td>{{ number_format($marks->teacher_1_marks, 1) }}</td>
                                                <td>{{ number_format($marks->teacher_2_marks, 1) }}</td>
                                                <td>
                                                    @if( $marks->teacher_1_marks - $marks->teacher_2_marks >= 12 | $marks->teacher_2_marks - $marks->teacher_1_marks >= 12 )
                                                        @if($marks->teacher_3_marks != null)
                                                            {{ number_format($marks->teacher_3_marks, 1) }}
                                                        @else
                                                            <span class="badge badge-danger">3rd Examiner Marks Not Found</span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-info">Not Applicable</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $numbers = array($marks->teacher_1_marks, $marks->teacher_2_marks, $marks->teacher_3_marks);
                                                        rsort($numbers);

                                                        $final_marks_average = ($numbers[0] + $numbers[1]) / 2;

                                                    @endphp

                                                    @if( $marks->teacher_1_marks - $marks->teacher_2_marks >= 12 | $marks->teacher_2_marks - $marks->teacher_1_marks >= 12 )
                                                        @if($marks->teacher_3_marks != null)
                                                            {{ number_format( $incourse_marks + $final_marks_average, 1 ) }}
                                                        @else
                                                            <span class="badge badge-danger">3rd Examiner Marks Not Found</span>
                                                        @endif


                                                    @else
                                                        {{ number_format($final_marks_average, 1) }}
                                                    @endif
                                                </td>

                                                <td>
                                                    @php
                                                        $numbers = array($marks->teacher_1_marks, $marks->teacher_2_marks, $marks->teacher_3_marks);
                                                        rsort($numbers);

                                                        $final_marks_average = ($numbers[0] + $numbers[1]) / 2;

                                                    @endphp

                                                    @if( $marks->teacher_1_marks - $marks->teacher_2_marks >= 12 | $marks->teacher_2_marks - $marks->teacher_1_marks >= 12 )
                                                        @if($marks->teacher_3_marks != null)
                                                            {{ number_format( $incourse_marks + $final_marks_average, 1 ) }}
                                                        @else
                                                            <span class="badge badge-danger">3rd Examiner Marks Not Found</span>
                                                        @endif


                                                    @else
                                                        {{ round($incourse_marks + $final_marks_average) }}
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
