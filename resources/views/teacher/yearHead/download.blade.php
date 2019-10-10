@php
    $code = explode('-', $session->name);
    $year_main = substr($code[0], 0, 4);
    $year = $year_main + $year->code;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $semester->name  }} Result - {{ $year }} - {{ $students[0]->dept->short_name }} - {{ $session->name }}</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('assets/backend/plugins/font-awesome/css/font-awesome.min.css') }}">
    <!-- IonIcons -->
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/backend/css/adminlte.min.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">

    <link rel="icon" href="{{ asset('assets/backend/img/policymaker.ico') }}" type="image/x-icon" />

    @stack('css')

</head>


<body>
<!-- Main content -->
<section class="content">
    <div class="container-fluid px-5">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12 mt-4">
                <div class="col-12 text-center mb-4">
                    <div class="row">
                        <div class="col-4 text-right">
                            <img width="50" height="50" src="{{ asset('assets/backend/img/logo.png') }}" alt="JU Logo">
                        </div>
                        <div class="col-4 text-center pl-4">
                            <h4>Jahangirnagar University</h4>
                            <h5>Savar, Dhaka</h5>
                        </div>
                    </div>

                    <h4>{{ $semester->name  }} Result {{ $year }}</h4>
                    <h5>Subject : {{ $students[0]->dept->short_name }} </h5>
                    <h5>Session : {{ $session->name }}</h5>
                    <div class="row">
                        <div class="col-6 offset-3">
                            <h5 class="text-center">
                                Passed Students List (According to Exam Roll)
                            </h5>
                        </div>
                        <div class="col-3">
                            <span class="btn btn-sm btn-outline-dark float-right">Grading Scale : 4.00</span>
                        </div>
                    </div>
                </div>

                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                    <tr>
                        <th>Serial</th>
                        <th>Hall Name</th>
                        <th>Class Roll</th>
                        <th>Exam Roll</th>
                        <th>Student Name</th>
                        <th>GPA</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $key => $student)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $student->hall }}</td>
                                <td>{{ $student->class_roll }}</td>
                                <td>{{ $student->exam_roll }}</td>
                                <td>{{ $student->name  }}</td>
                                <td>
                                    {{ gpa_calculate($student->session, $semester->id , $student->reg_no, $student->exam_roll) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
            <!--/.col (left) -->

            <div class="col-4 offset-8 text-right" style="margin-top: 100px;">
                @if(isset($check_approval) && $check_approval->status == 1)
                    <img class="text-center" width="100" height="60" src="{{ asset('assets/backend/img/sign.png') }}" alt="Exam Controller Sign">
                @endif
                <p>Examination Controller, JU</p>
                <p>
                    Date :
                    @if(isset($check_approval) && $check_approval->status == 1)
                        {{ $check_approval->created_at->format('F d, Y') }}
                    @endif
                </p>
            </div>

        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<!-- /.content-wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{ asset('https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('assets/backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE -->
<script src="{{ asset('assets/backend/js/adminlte.js') }}"></script>

<script>
    window.print();
</script>


</body>




</html>>
