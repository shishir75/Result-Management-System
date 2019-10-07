@extends('layouts.backend.app')

@section('title', 'Marks')

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
                            <li class="breadcrumb-item active">Marks</li>
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
                                    MARKS LIST OF  {{ strtoupper($marks[0]->dept->is_semester == 1 ? $semester->name : $year->name ) }} OF SESSION {{ $marks[0]->session->name }}
                                    <span class="float-right text-info">COURSE : {{ $marks[0]->course->course_code }} - {{ strtoupper($marks[0]->course->course_title) }}</span>
                                    <span class="ml-5">DEPT : {{ strtoupper($marks[0]->dept->name) }}</span>

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
                                        <th>1st Examiner Marks</th>
                                        <th>2nd Examiner Marks</th>
                                        <th>3rd Examiner Marks</th>
                                        <th>Final Marks</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Serial</th>
                                        <th>Reg No</th>
                                        <th>Exam Roll</th>
                                        <th>1st Examiner Marks</th>
                                        <th>2nd Examiner Marks</th>
                                        <th>3rd Examiner Marks</th>
                                        <th>Final Marks</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    @foreach($marks as $key => $mark)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $mark->reg_no  }}</td>
                                            <td>{{ $mark->exam_roll  }}</td>
                                            <td>{{ $mark->teacher_1_marks  }}</td>
                                            <td>{{ $mark->teacher_2_marks  }}</td>
                                            <td>
                                                @if( $mark->teacher_1_marks - $mark->teacher_2_marks >= 12 | $mark->teacher_2_marks - $mark->teacher_1_marks >= 12 )
                                                    @if($mark->teacher_3_marks != null)
                                                        {{ $mark->teacher_3_marks  }}
                                                    @else
                                                       <span class="badge badge-warning">Not Found</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-info">No Applicable</span>
                                                @endif
                                            </td>

                                            @php
                                                $mark1 = $mark->teacher_1_marks;
                                                $mark2 = $mark->teacher_2_marks;
                                                $mark3 = $mark->teacher_3_marks;

                                                if( ($mark1 - $mark2) >= 12 | ($mark2 - $mark1) >= 12 )
                                                {
                                                    $numbers = array($mark1, $mark2, $mark3);
                                                    rsort($numbers);

                                                    $final_marks = ($numbers[0] + $numbers[1]) / 2;

                                                } else {

                                                    $final_marks = ($mark1 + $mark2) / 2;
                                                }
                                            @endphp

                                            <td>{{ $final_marks }}</td>
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
