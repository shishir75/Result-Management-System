<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Student([
            'name'     => $row[1],
            'session'    => $row[2],
            'class_roll'    => $row[3],
            'reg_no'    => $row[4],
            'exam_roll'    => $row[5],
            'hall'    => $row[6],
            'father_name'    => $row[7],
            'mother_name'    => $row[8],
        ]);
    }
}
