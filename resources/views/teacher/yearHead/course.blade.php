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

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ strtoupper('Course list of '. $year->name ) }}</h3>
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
                                        <th>Marks</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Serial</th>
                                        <th>Course Title</th>
                                        <th>{{ $courses[0]->dept->is_semester == 1 ? 'Semester' : 'Year' }}</th>
                                        <th>Credit Hour</th>
                                        <th>Lab / Viva</th>
                                        <th>In-course Marks</th>
                                        <th>Final Marks</th>
                                        <th>Marks</th>
                                    </tr>
                                    </tfoot>
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
                                                        <p class="btn btn-sm btn-success"><i class="fa fa-check" aria-hidden="true"></i></p>
                                                    @else
                                                        <p class="btn btn-sm btn-warning"><i class="fa fa-times" aria-hidden="true"></i></p>
                                                    @endif
                                                </td>
                                                <td>{{ $course->incourse_marks }}</td>
                                                <td>{{ $course->final_marks  }}</td>
                                                <td>
                                                    <a href="#" class="btn btn-success">
                                                        <i class="fa fa-male" aria-hidden="true"></i>
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
