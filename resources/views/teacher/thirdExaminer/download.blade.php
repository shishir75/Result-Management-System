<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Course Teacher Final Marks of {{ $course->course_code }} - {{ $course->course_title }}</title>

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
                    <h3>Dept : {{ $course->dept->name }}</h3>
                    <h4 class="text-uppercase">Second Examiner Marks</h4>
                    <h5>Session : {{ $session->name }} | Subject : {{ $course->course_code }} - {{ $course->course_title }}</h5>
                    <h5>Teacher Name : {{ \Illuminate\Support\Facades\Auth::user()->name }}</h5>
                    <h5 class="text-right">Date: {{ \Carbon\Carbon::now()->format(' F d, Y') }}</h5>
                </div>

                <table id="example1" class="table table-bordered table-striped text-center">
                    <thead>
                    <tr>
                        <th>Serial</th>
                        <th>Registration No</th>
                        <th>Exam Roll</th>
                        <th>Marks</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($second_examiner_marks as $key => $second_examiner_mark)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $second_examiner_mark->reg_no }}</td>
                            <td>{{ $second_examiner_mark->exam_roll }}</td>
                            <td>{{ number_format(round($second_examiner_mark->teacher_2_marks), 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
            <!--/.col (left) -->
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
