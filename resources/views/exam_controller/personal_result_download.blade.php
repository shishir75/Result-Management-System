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

    <title>B.Sc (Honours) {{ $semester->name  }} Examination, {{ $year }}-{{ $student->exam_roll }}</title>

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

    <style>
        .info tbody tr td {
            padding: 0 !important;
        }
    </style>

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
                        <div class="col-6 text-left ml-3">
                            <h3>Jahangirnagar University</h3>
                            <h5 class="ml-4">Savar, Dhaka, Bangladesh</h5>
                        </div>
                    </div>

                    <h4>GRADE SHEET</h4>
                    <h4>{{ ucwords($dept->name) }} </h4>

                    <div class="row mt-5">
                        <div class="col-6">
                            <table class="table table-bordered info">
                                <tbody>
                                    <tr>
                                        <td>Student Name</td>
                                        <td>{{ $student->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Father's Name</td>
                                        <td>{{ $student->father_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Mother's Name</td>
                                        <td>{{ $student->mother_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Registration No.</td>
                                        <td>{{ $student->reg_no }}</td>
                                    </tr>
                                    <tr>
                                        <td>Session</td>
                                        <td>{{ $student->session }}</td>
                                    </tr>
                                    <tr>
                                        <td>Class Roll No.</td>
                                        <td>{{ $student->class_roll }}</td>
                                    </tr>
                                    <tr>
                                        <td>Exam Roll No.</td>
                                        <td>{{ $student->exam_roll }}</td>
                                    </tr>
                                    <tr>
                                        <td>Name of Hall</td>
                                        <td>{{ $student->hall }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-5 offset-1">
                            <table class="table table-bordered info">
                                <tbody>
                                <tr>
                                    <td colspan="3">Grading System</td>
                                </tr>
                                <tr>
                                    <td>Range of Marks</td>
                                    <td>Letter Grade</td>
                                    <td>Grade Point</td>
                                </tr>
                                <tr>
                                    <td>80% and above</td>
                                    <td>A+</td>
                                    <td>4.00</td>
                                </tr>
                                <tr>
                                    <td>75% to <80% </td>
                                    <td>A</td>
                                    <td>3.75</td>
                                </tr>
                                <tr>
                                    <td>70% to <75% </td>
                                    <td>A-</td>
                                    <td>3.50</td>
                                </tr>
                                <tr>
                                    <td>65% to <70% </td>
                                    <td>B+</td>
                                    <td>3.25</td>
                                </tr>
                                <tr>
                                    <td>60% to <65% </td>
                                    <td>B</td>
                                    <td>3.00</td>
                                </tr>
                                <tr>
                                    <td>75% to <80% </td>
                                    <td>A</td>
                                    <td>3.75</td>
                                </tr>
                                <tr>
                                    <td>55% to <60% </td>
                                    <td>B-</td>
                                    <td>2.75</td>
                                </tr>
                                <tr>
                                    <td>50% to <55% </td>
                                    <td>C+</td>
                                    <td>2.50</td>
                                </tr>
                                <tr>
                                    <td>45% to <50% </td>
                                    <td>C</td>
                                    <td>2.25</td>
                                </tr>
                                <tr>
                                    <td>40% to <45% </td>
                                    <td>D</td>
                                    <td>2.00</td>
                                </tr>
                                <tr>
                                    <td>Less than 40% </td>
                                    <td>F</td>
                                    <td>0.00</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <h4 class="text-center">B.Sc (Honours) {{ $semester->name  }} Examination, {{ $year }}</h4>

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
            <!--/.col (left) -->

            <div class="col-4 offset-8 text-right" style="margin-top: 60px;">
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
