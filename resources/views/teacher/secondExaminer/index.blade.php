@extends('layouts.backend.app')

@section('title', 'Second Examiner Marks')

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
                            <li class="breadcrumb-item active">Second Examiner Marks</li>
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
                                <h3 class="card-title">{{ strtoupper('Course list of '.$teacher->name. ' for Second Examiner Marks' ) }}</h3>
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
                                        <th>Written Marks</th>
                                        <th>Add Written Marks</th>
                                        <th>View Written Marks</th>
                                        <th>Submit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($second_examiner_courses as $key => $second_examiner_course)

                                        @php
                                            if ($second_examiner_course->dept->is_semester == 1)
                                            {
                                                $code = explode('-', $second_examiner_course->course->course_code);
                                                $year = substr($code[1], 0, 1);
                                                $semester = substr($code[1], 1, 1);

                                            } else {
                                                $code = explode('-', $second_examiner_course->course->course_code);
                                                $year = substr($code[1], 0, 1);
                                            }
                                        @endphp


                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $second_examiner_course->session->name }}</td>
                                            <td>{{ $second_examiner_course->course->course_code .' - '. $second_examiner_course->course->course_title }}</td>

                                            <td>
                                                @if($second_examiner_course->dept->is_semester == 1)
                                                    {{ $year . ' - ' . $semester }}
                                                @else
                                                    {{ $year }}
                                                @endif
                                            </td>

                                            <td>{{ number_format($second_examiner_course->course->credit_hour, 1) }}</td>
                                            <td>
                                                @if($second_examiner_course->course->is_lab == true)
                                                    <i class="fa fa-check text-success" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-times text-danger" aria-hidden="true"></i>
                                                @endif
                                            </td>
                                            <td>{{ $second_examiner_course->course->final_marks  }}</td>
                                            <td>
                                                @if($second_examiner_course->external_1_status == 0)
                                                    <a href="{{ route('teacher.second-examiner.create', [$second_examiner_course->session->id, $second_examiner_course->course->id]) }}" class="btn btn-info">
                                                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                                                    </a>
                                                @else
                                                    <span class="badge badge-success">Submitted</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('teacher.second-examiner.show', [$second_examiner_course->session->id, $second_examiner_course->course->id]) }}" class="btn btn-info">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                            <td>
                                                @if($second_examiner_course->external_1_status == 0)
                                                    <button class="btn btn-warning" type="button" onclick="approvedItem({{ $second_examiner_course->id }})">
                                                        <i class="fa fa-question" aria-hidden="true"></i>
                                                    </button>
                                                    <form id="approved-form-{{ $second_examiner_course->id }}" action="{{ route('teacher.second-examiner.approved', [$second_examiner_course->id]) }}" method="post"
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
