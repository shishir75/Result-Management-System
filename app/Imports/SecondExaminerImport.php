<?php

namespace App\Imports;

use App\Models\FinalMarks;
use Maatwebsite\Excel\Concerns\ToModel;

class SecondExaminerImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new FinalMarks([
            'reg_no'     => $row[1],
            'exam_roll'    => $row[2],
            'teacher_2_marks'    => $row[3],
        ]);
    }
}
